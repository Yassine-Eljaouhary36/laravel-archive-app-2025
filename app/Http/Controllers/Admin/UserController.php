<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\File;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get all roles except 'admin'
        $roles = Role::where('name', '!=', 'admin')->pluck('name');
        
        // Base query for non-admin users
        $query = User::with(['roles'])
            ->whereDoesntHave('roles', function($q) {
                $q->where('name', 'admin');
            })
            ->withCount([
                'boxes as boxes_validated_count' => function($q) use ($request) {
                    $q->whereNotNull('validated_at');
                    if ($request->filled(['date_from', 'date_to'])) {
                        $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                        $dateTo = Carbon::parse($request->date_to)->endOfDay();
                        
                        $q->whereBetween('validated_at', [$dateFrom, $dateTo]);
                    }
                }
            ])
            ->with(['boxes' => function($q) use ($request) {
                $q->whereNotNull('validated_at');
                if ($request->filled(['date_from', 'date_to'])) {
                    $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                    $dateTo = Carbon::parse($request->date_to)->endOfDay();
                    
                    $q->whereBetween('validated_at', [$dateFrom, $dateTo]);
                }
                $q->withCount('files');
            }]);
        
        // Apply role filter if provided
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Get and process results
        $users = $query->get()
            ->each(function($user) {
                $user->valid_files_count = $user->boxes->sum('files_count');
            })
            ->sortByDesc('valid_files_count');
        

        // Get performance metrics data
        $metrics = [
            'total_users' => User::whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'active_users' => User::where('is_active', true)->whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))->count(),
            'total_validated_boxes' => Box::whereNotNull('validated_at')->when($request->filled(['date_from', 'date_to']), function($q) use ($request) {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                $q->whereBetween('validated_at', [$dateFrom, $dateTo]);
            })->count(),
            'total_validated_files' => Box::whereNotNull('validated_at')
                ->when($request->filled(['date_from', 'date_to']), function($q) use ($request) {
                    $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                    $dateTo = Carbon::parse($request->date_to)->endOfDay();
                    $q->whereBetween('validated_at', [$dateFrom, $dateTo]);
                })
                ->withCount('files')
                ->get()
                ->sum('files_count'),
        ];
            
        // Paginate results
        $page = request('page', 1);
        $perPage = 10;
        $users = new LengthAwarePaginator(
            $users->forPage($page, $perPage),
            $users->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('admin.users.index', compact('users', 'roles', 'metrics'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function show(User $user)
    {
        // Load counts for boxes and validated boxes
        $user->loadCount([
            'boxes',
            'boxes as validated_boxes_count' => function($query) {
                $query->whereNotNull('validated_at');
            }
        ]);

        // Get files count through boxes
        $filesCount = File::whereHas('box', function($query) use ($user) {
            $query->whereNotNull('validated_at');
            $query->where('user_id', $user->id);
        })->count();

        // Load recent boxes with their relationships
        $user->load(['boxes' => function($query) {
            $query->with(['tribunal', 'files'])
                ->latest()
                ->take(5);
        }]);

        // Calculate additional statistics
        $stats = [
            'validation_rate' => $user->boxes_count > 0 
                ? round(($user->validated_boxes_count / $user->boxes_count) * 100, 2)
                : 0,
            'pending_boxes_count' => $user->boxes_count - $user->validated_boxes_count,
            'files_count' => $filesCount,
            'monthly_activity' => $this->getMonthlyActivity($user),
            'weekly_activity' => $this->getWeeklyActivity($user),
            'avg_box_creation_time' => $this->getAverageBoxCreationTime($user)
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    protected function getMonthlyActivity(User $user)
    {
        $currentYear = now()->year;
        $monthlyData = [];
        
        // Get boxes created each month
        $boxesByMonth = $user->boxes()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Get validated boxes each month
        $validatedByMonth = $user->boxes()
            ->whereNotNull('validated_at')
            ->selectRaw('MONTH(validated_at) as month, COUNT(*) as count')
            ->whereYear('validated_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Prepare data for all months
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = [
                'month' => $month,
                'boxes' => $boxesByMonth[$month] ?? 0,
                'validated' => $validatedByMonth[$month] ?? 0
            ];
        }

        return $monthlyData;
    }

    protected function getWeeklyActivity(User $user)
    {
        $today = now();

        if ($today->dayOfWeek === 0) {
            // Sunday → move to next week (don't mutate $today)
            $startDate = $today->copy()->addDay()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $endDate = $startDate->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
        } else {
            $startDate = $today->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $endDate = $today->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
        }

        // Arabic days ordered to match DAYOFWEEK() (1=Sunday, 7=Saturday)
        $daysOrdered = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        
        // Initialize arrays with 0 counts for all days
        $boxesAdded = array_fill(1, 7, 0);
        $boxesValidated = array_fill(1, 7, 0);
        
        // Get and merge actual data
        $boxesAddedData = $user->boxes()
            ->selectRaw('DAYOFWEEK(updated_at) as day, COUNT(*) as count')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();
        
        $boxesValidatedData = $user->boxes()
            ->whereNotNull('validated_at')
            ->selectRaw('DAYOFWEEK(validated_at) as day, COUNT(*) as count')
            ->whereBetween('validated_at', [$startDate, $endDate])
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();
        
        // Merge with initialized arrays
        foreach ($boxesAddedData as $day => $count) {
            $boxesAdded[$day] = $count;
        }
        
        foreach ($boxesValidatedData as $day => $count) {
            $boxesValidated[$day] = $count;
        }
        
        return [
            'days' => $daysOrdered,
            'boxes_added' => $boxesAdded,
            'boxes_validated' => $boxesValidated
        ];
    }

    protected function getAverageBoxCreationTime(User $user)
    {
        $startOfWeek = now()->startOfWeek(); // Monday
        $endOfFriday = now()->startOfWeek()->addDays(4)->endOfDay(); // Friday
        $today = now();
        $boxCount = $user->boxes()
            ->whereBetween('updated_at', [$startOfWeek, $endOfFriday])
            ->count();
        
        if ($boxCount === 0) {
            return null; // or 0
        }
        
        if($today->dayOfWeek <= 4){
            $workingMinutes = $today->dayOfWeek * 6 * 60; // 4 days × 6 hours/day
        }else{
            $workingMinutes = 4 * 6 * 60; // 4 days × 6 hours/day
        }
        

        return round($workingMinutes / $boxCount, 2); // avg minutes per box
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated successfully.');
    }
}
