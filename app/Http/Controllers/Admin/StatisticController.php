<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Tribunal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

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

    public function exportPDF(Request $request)
    {
        // Reuse your existing index query logic to get filtered data
        $tribunals = Tribunal::all();
        $years = Box::selectRaw('DISTINCT year_of_judgment')
                ->orderBy('year_of_judgment', 'desc')
                ->pluck('year_of_judgment');

        $query = Box::whereNotNull('validated_at')
                ->withCount('files')
                ->with('tribunal');

        // Apply the same filters as your index method
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

        // Get the statistics data
        $statsByType = $query->get()
            ->groupBy('type')
            ->map(function($boxes) {
                return [
                    'total_boxes' => $boxes->count(),
                    'total_files' => $boxes->sum('files_count')
                ];
            });

        $totalStats = [
            'total_boxes' => $query->count(),
            'total_files' => $query->get()->sum('files_count')
        ];

        // Get current filters for the report title
        $filters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'tribunal' => $request->tribunal_id ? Tribunal::find($request->tribunal_id)->tribunal : 'الكل',
            'year' => $request->year_of_judgment ?: 'الكل'
        ];

        // Font configuration
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'direction' => 'rtl',
            'fontDir' => array_merge($fontDirs, [
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'xbriyaz' => [  // Using XB Riyaz font
                    'R' => 'XB Riyaz.ttf',
                    'B' => 'XB RiyazBd.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'default_font' => 'xbriyaz',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_top' => 20,
            'margin_right' => 15,
            'margin_left' => 15,
            'margin_bottom' => 20,
        ]);

        // HTML content
        $html = view('admin.statistics.pdf', compact(
            'statsByType',
            'totalStats',
            'filters'
        ))->render();

        $mpdf->WriteHTML($html);
        
        $filename = 'تقرير-الإحصائيات-' . now()->format('Y-m-d') . '.pdf';
        return $mpdf->Output($filename, 'D');
    }
}
