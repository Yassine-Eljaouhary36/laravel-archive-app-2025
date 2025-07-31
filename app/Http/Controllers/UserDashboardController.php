<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Basic box statistics
        $boxStats = [
            'total' => Box::where('user_id', $userId)->count(),
            'validated' => Box::where('user_id', $userId)
                ->whereNotNull('validated_at')
                ->count(),
            'pending' => Box::where('user_id', $userId)
                ->whereNull('validated_at')
                ->count(),
            'recent' => Box::where('user_id', $userId)
                ->with(['tribunal', 'validator']) // Eager load relationships
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'validation_rate' => $this->calculateValidationRate($userId),
            'files_count' => $this->getUserFilesCount($userId),
            'tribunal_distribution' => $this->getTribunalDistribution($userId)
        ];
            
        return view('user.dashboard', $boxStats);
    }

    protected function calculateValidationRate($userId)
    {
        $total = Box::where('user_id', $userId)->count();
        $validated = Box::where('user_id', $userId)
            ->whereNotNull('validated_at')
            ->count();
            
        return $total > 0 ? round(($validated / $total) * 100, 2) : 0;
    }

    protected function getUserFilesCount($userId)
    {
        return Box::where('user_id', $userId)
            ->whereNotNull('validated_at')
            ->withCount('files')
            ->get()
            ->sum('files_count');
    }

    protected function getTribunalDistribution($userId)
    {
        return Box::where('user_id', $userId)
            ->whereNotNull('validated_at')
            ->with('tribunal')
            ->get()
            ->groupBy('tribunal.tribunal')
            ->map->count()
            ->sortDesc();
    }
}