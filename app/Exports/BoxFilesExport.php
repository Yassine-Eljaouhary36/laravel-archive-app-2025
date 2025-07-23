<?php

namespace App\Exports;

use App\Models\Box;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BoxFilesExport implements FromCollection, WithHeadings, WithStyles, WithDrawings
{
    protected $box;
    
    public function __construct(Box $box)
    {
        $this->box = $box->load(['files', 'tribunal']);
    }

    public function collection()
    {
        return $this->box->files->map(function ($file, $index) {
            return [
                'A' => $index + 1,                            // الرقم الترتيبي
                'B' => $file->file_number,                     // رقم الملف
                'C' => $file->year_of_opening,                 // سنة فتح الملف
                'D' => $file->symbol,                          // رمز الملف
                'E' => $file->judgment_number ?? '',           // رقم الحكم
                'F' => $file->judgment_date 
                    ? \Carbon\Carbon::parse($file->judgment_date)->format('Y-m-d')
                    : '',                                      // تاريخ الحكم
                'G' => ''                                     // ملاحظات
            ];
        });
    }

    public function headings(): array
    {
        return []; // Empty to prevent automatic headers
    }

    public function styles(Worksheet $sheet)
    {
        // Clear any automatic data that might appear in A1
        $sheet->setCellValue('A1', '');

        // Set RTL direction for the entire sheet
        $sheet->setRightToLeft(true);

        // Set default font
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(14);

        // ======= LOGO SECTION ========
        $sheet->mergeCells('A1:G1');
        $sheet->getRowDimension(1)->setRowHeight(150);
        $sheet->getStyle('A1:G1')->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getRowDimension(2)->setRowHeight(50);
        $sheet->getRowDimension(3)->setRowHeight(50);
        $sheet->getRowDimension(4)->setRowHeight(50);
        $sheet->getRowDimension(5)->setRowHeight(50);
        // ======= BOX INFORMATION SECTION ========
        // Court Information (A2:D2)
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'المحكمة: ' . ($this->box->tribunal->tribunal ?? ''));
        $sheet->getStyle('A2:D2')->applyFromArray($this->getInfoCellStyle());


        // Saving Base Number (E2:F2)
        $sheet->mergeCells('E2:F2');
        $sheet->setCellValue('E2', 'رقم قاعدة الحفظ: ' . $this->box->saving_base_number);
        $sheet->getStyle('E2:F2')->applyFromArray($this->getInfoCellStyle());

        // File Type (A3:D3)
        $sheet->mergeCells('A3:D3');
        $sheet->setCellValue('A3', 'نوع الملف: ' . $this->box->file_type);
        $sheet->getStyle('A3:D3')->applyFromArray($this->getInfoCellStyle());

        // Box Type (E3:F3)
        $sheet->mergeCells('E3:F3');
        $sheet->setCellValue('E3', 'النوع: ' . $this->box->type);
        $sheet->getStyle('E3:F3')->applyFromArray($this->getInfoCellStyle());

        // Box Number (A4:D4)
        $sheet->mergeCells('A4:D4');
        $sheet->setCellValue('A4', 'رقم العلبة: ' . $this->box->box_number);
        $sheet->getStyle('A4:D4')->applyFromArray($this->getInfoCellStyle());

        // Files Count (E4:F4)
        $sheet->mergeCells('E4:F4');
        $sheet->setCellValue('E4', 'عدد الملفات: ' . $this->box->files->count());
        $sheet->getStyle('E4:F4')->applyFromArray($this->getInfoCellStyle());

        // Judgment Year (A5:D5)
        $sheet->mergeCells('A5:D5');
        $sheet->setCellValue('A5', 'سنة الحكم: ' . $this->box->year_of_judgment);
        $sheet->getStyle('A5:D5')->applyFromArray($this->getInfoCellStyle());

        // Empty space for balance (E5:F5)
        $sheet->mergeCells('E5:F5');
        $sheet->getStyle('E5:F5')->applyFromArray($this->getInfoCellStyle());

        // ======= FILES DATA HEADERS ========
        $headerRow = 7;
        $headers = [
            'A' => 'الرقم الترتيبي',
            'B' => 'رقم الملف',
            'C' => 'سنة فتح الملف',
            'D' => 'رمز الملف',
            'E' => 'رقم الحكم / القرار',
            'F' => 'تاريخ الحكم/ القرار',
            'G' => 'ملاحظات'
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValue($col.$headerRow, $header);
        }

        // Style for header row
        $sheet->getStyle('A'.$headerRow.':G'.$headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => Color::COLOR_WHITE]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2F5496']
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

        // ======= FILES DATA ========
        $dataStartRow = 8;
        $filesData = $this->collection()->toArray();

        foreach ($filesData as $rowIndex => $rowData) {
            foreach ($rowData as $col => $cellData) {
                $sheet->setCellValue($col.($dataStartRow + $rowIndex), $cellData);
            }
        }

        // Style the data rows
        $dataEndRow = $dataStartRow + count($filesData) - 1;
        if ($dataEndRow >= $dataStartRow) {
            // Apply borders to all data cells
            $sheet->getStyle('A'.$dataStartRow.':G'.$dataEndRow)->applyFromArray([
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

            // Alternate row coloring
            for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                $fillColor = $row % 2 == 0 ? 'FFFFFF' : 'E7E6E6';
                $sheet->getStyle('A'.$row.':G'.$row)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($fillColor);
                $sheet->getRowDimension($row)->setRowHeight(50);
            }
        }

        // Auto-size columns
        foreach(range('A','G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set print area
        $sheet->getPageSetup()->setPrintArea('A1:G'.$dataEndRow);
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
                'horizontal' => Alignment::HORIZONTAL_RIGHT
            ]
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Court Logo');
        $drawing->setPath(public_path('images/court_logo.png'));
        $drawing->setHeight(200);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(650);
        $drawing->setOffsetY(0);
        
        return [$drawing];
    }

    
}