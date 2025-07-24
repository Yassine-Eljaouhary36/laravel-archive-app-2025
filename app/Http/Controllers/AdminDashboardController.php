<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\File;
use App\Models\Tribunal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Box statistics
        $boxStats = [
            'total' => Box::count(),
            'validated' => Box::whereNotNull('validated_at')->count(),
            'pending' => Box::whereNull('validated_at')->count(),
            'today' => Box::whereDate('created_at', Carbon::today())->count(),
            'this_week' => Box::whereBetween('created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'validation_rate' => $this->calculateValidationRate()
        ];

        // Add File statistics
        $fileStats = [
            'total' => File::count(),
            'today' => File::whereDate('created_at', Carbon::today())->count(),
            'this_week' => File::whereBetween('created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'avg_per_box' => Box::withCount('files')
                        ->having('files_count', '>', 0)
                        ->avg('files_count'),
            'largest_box' => Box::withCount('files')
                        ->orderBy('files_count', 'desc')
                        ->first()
        ];
        // User statistics
        $userStats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count()
        ];

        // Performance statistics
        $performanceStats = [
            'top_performers' => User::withCount(['boxes' => function($query) {
                $query->whereNotNull('validated_at');
            }])
            ->having('boxes_count', '>', 0)
            ->orderBy('boxes_count', 'desc')
            ->with('boxes') // Eager load boxes for additional stats
            ->paginate(5, ['*'], 'top_page'),
            
            'slow_performers' => User::withCount(['boxes' => function($query) {
                $query->whereNull('validated_at');
            }])
            ->having('boxes_count', '>', 0)
            ->orderBy('boxes_count', 'desc')
            ->paginate(5, ['*'], 'slow_page'),
            
            'avg_validation_time' => $this->calculateAvgValidationTime()
        ];

        // Tribunal statistics
        $tribunalStats = Tribunal::withCount('boxes')
            ->having('boxes_count', '>', 0)
            ->orderBy('boxes_count', 'desc')
            ->with(['boxes' => function($query) {
                $query->whereNotNull('validated_at');
            }])
            ->paginate(5, ['*'], 'tribunal_page');

        // Recent activity
        $recentActivity = Box::with(['user', 'validator', 'tribunal'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'boxStats',
            'fileStats',
            'userStats',
            'performanceStats',
            'tribunalStats',
            'recentActivity'
        ));
    }

    protected function calculateValidationRate()
    {
        $total = Box::count();
        $validated = Box::whereNotNull('validated_at')->count();
        
        return $total > 0 ? round(($validated / $total) * 100, 2) : 0;
    }

    protected function calculateAvgValidationTime()
    {
        return Box::whereNotNull('validated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, validated_at)) as avg_hours')
            ->first()
            ->avg_hours;
    }
}