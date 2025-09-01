<?php

namespace App\Exports;

use App\Models\Box;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BoxesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        return Box::query()
            ->when(!auth()->user()->hasRole(['admin', 'controller']), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->when(isset($this->filters['box_number']), function ($query) {
                return $query->where('box_number', 'like', '%'.$this->filters['box_number'].'%');
            })
            ->when(isset($this->filters['year_of_judgment']), function ($query) {
                $years = is_array($this->filters['year_of_judgment']) 
                    ? $this->filters['year_of_judgment']
                    : [$this->filters['year_of_judgment']];
                return $query->whereIn('year_of_judgment', $years);
            })
            ->when(isset($this->filters['file_type']), function ($query) {
                return $query->where('file_type', $this->filters['file_type']);
            })
            ->when(isset($this->filters['type']), function ($query) {
                return $query->where('type', $this->filters['type']);
            })
            ->when(isset($this->filters['tribunal_id']), function ($query) {
                return $query->where('tribunal_id', $this->filters['tribunal_id']);
            })
            ->when(isset($this->filters['validated']), function ($query) {
                if ($this->filters['validated'] === '1') {
                    return $query->whereNotNull('validated_at');
                } elseif ($this->filters['validated'] === '0') {
                    return $query->whereNull('validated_at');
                }
            })
            ->with(['user:id,name', 'tribunal:id,tribunal', 'savingBase:id,number,description'])
            ->withCount('files')
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'رقم العلبة',
            'رقم قاعدة الحفظ',
            'المصلحة',
            'نوع الملفات',
            'المحكمة',
            'سنة الحكم',
            'عدد الملفات',
        ];
    }

    public function map($box): array
    {
        return [
            $box->box_number,
            sprintf('="%s"', $box->savingBase->number ?? $box->saving_base_number),
            $box->file_type,
            $box->type,
            $box->tribunal->tribunal ?? '',
            $box->year_of_judgment,
            $box->files_count,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => Color::COLOR_WHITE]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2F5496'] // Dark blue background
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Data style
        $sheet->getStyle('A2:G' . ($sheet->getHighestRow()))->applyFromArray([
            'font' => [
                'size' => 12
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Auto-filter
        $sheet->setAutoFilter('A1:G1');

        // Freeze first row
        $sheet->freezePane('A2');

        // Set right-to-left direction for Arabic text
        $sheet->setRightToLeft(true);
    }

    protected function getInfoCellStyle()
    {
        return [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
    }
}