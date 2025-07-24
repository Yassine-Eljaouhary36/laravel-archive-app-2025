<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Boxes waiting for validation (pending)
        $pendingValidation = Box::whereNull('validated_at')
            ->count();
            
        // Recently validated boxes - cast validated_at to Carbon
        $recentlyValidated = Box::with('validator')
            ->whereNotNull('validated_at')
            ->where('validated_by', $userId)
            ->orderBy('validated_at', 'desc')
            ->limit(5)
            ->get();
            
        // Validation performance stats
        $totalValidated = Box::where('validated_by', $userId)
            ->whereNotNull('validated_at')
            ->count();
            
        $validationRate = Box::count() > 0 
            ? ($totalValidated / Box::count()) * 100 
            : 0;
            
        return view('controller.dashboard', compact(
            'pendingValidation',
            'recentlyValidated',
            'totalValidated',
            'validationRate'
        ));
    }
}