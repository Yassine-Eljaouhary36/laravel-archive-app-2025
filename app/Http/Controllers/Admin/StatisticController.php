<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Tribunal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticController extends Controller
{
        public function index(Request $request)
    {
        $tribunals = Tribunal::all();
        $years = Box::selectRaw('DISTINCT year_of_judgment')
                  ->orderBy('year_of_judgment', 'desc')
                  ->pluck('year_of_judgment');

        $query = Box::whereNotNull('validated_at')
                  ->withCount('files')
                  ->with('tribunal');

        // Apply filters
        if ($request->filled(['date_from', 'date_to'])) {
            $query->whereBetween('validated_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);
        }

        if ($request->filled('tribunal_id')) {
            $query->where('tribunal_id', $request->tribunal_id);
        }

        if ($request->filled('year_of_judgment')) {
            $query->where('year_of_judgment', $request->year_of_judgment);
        }

        // Get grouped statistics
        $statsByType = $query->get()
            ->groupBy('type')
            ->map(function($boxes) {
                return [
                    'total_boxes' => $boxes->count(),
                    'total_files' => $boxes->sum('files_count')
                ];
            });

        // Get overall totals
        $totalStats = [
            'total_boxes' => $query->count(),
            'total_files' => $query->get()->sum('files_count')
        ];

        return view('admin.statistics.index', compact(
            'statsByType',
            'totalStats',
            'tribunals',
            'years'
        ));
    }
}
