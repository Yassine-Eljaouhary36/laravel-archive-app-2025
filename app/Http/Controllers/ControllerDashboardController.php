<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ControllerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Box statistics
        $stats = [
            'pending_validation' => Box::whereNull('validated_at')->count(),
            'pending_assigned' => Box::whereNull('validated_at')
                ->count(),
            'total_validated' => Box::where('validated_by', $userId)
                ->whereNotNull('validated_at')
                ->count(),
            'today_validated' => Box::where('validated_by', $userId)
                ->whereNotNull('validated_at')
                ->whereDate('validated_at', Carbon::today())
                ->count(),
            'validation_rate' => $this->calculateValidationRate($userId),
            'avg_validation_time' => $this->calculateAvgValidationTime($userId),
            'recently_validated' => Box::with(['validator', 'user', 'tribunal'])
                ->whereNotNull('validated_at')
                ->where('validated_by', $userId)
                ->orderBy('validated_at', 'desc')
                ->limit(10)
                ->get(),
            'files_stats' => [
                'total' => File::whereHas('box', function($q) use ($userId) {
                    $q->where('validated_by', $userId);
                })->count(),
                'validated' => File::whereHas('box', function($q) use ($userId) {
                    $q->where('validated_by', $userId)
                      ->whereNotNull('validated_at');
                })->count()
            ]
        ];
            
        return view('controller.dashboard', $stats);
    }

    protected function calculateValidationRate($userId)
    {
        $totalAssigned = Box::count();
        $validated = Box::where('validated_by', $userId)
            ->whereNotNull('validated_at')
            ->count();
            
        return $totalAssigned > 0 ? round(($validated / $totalAssigned) * 100, 2) : 0;
    }

    protected function calculateAvgValidationTime($userId)
    {
        return Box::where('validated_by', $userId)
            ->whereNotNull('validated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, validated_at)) as avg_hours')
            ->first()
            ->avg_hours;
    }
}