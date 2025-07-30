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
                'G' => $file->remark ?? ''                                      // ملاحظات
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
        // Clear any automatic data that might appear in E2,E3,E4,E5
        $sheet->setCellValue('E2', '');
        $sheet->setCellValue('E3', '');
        $sheet->setCellValue('E4', '');
        $sheet->setCellValue('E5', '');

        // Clear any automatic data that might appear in F2,F3,F4,F5
        $sheet->setCellValue('F2', '');
        $sheet->setCellValue('F3', '');
        $sheet->setCellValue('F4', '');
        $sheet->setCellValue('F5', '');

        // Clear any automatic data that might appear in G2,G3,G4,G5
        $sheet->setCellValue('G2', '');
        $sheet->setCellValue('G3', '');
        $sheet->setCellValue('G4', '');
        $sheet->setCellValue('G5', '');

        // Clear any automatic data that might appear in G2,G3,G4,G5
        $sheet->setCellValue('A2', '');
        $sheet->setCellValue('B2', '');
        $sheet->setCellValue('C3', '');
        $sheet->setCellValue('D4', '');
        $sheet->setCellValue('E5', '');
        $sheet->setCellValue('F5', '');
        $sheet->setCellValue('G5', '');

        $sheet->setCellValue('C2', '');
        $sheet->setCellValue('D2', '');

        // Set RTL direction for the entire sheet
        $sheet->setRightToLeft(true);

        // Set default font
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Sakkal Majalla')->setSize(14);

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

        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'جرد تفصيلي للملفات المحالة من '.($this->box->tribunal->tribunal ?? '').' إلى المركز الجهوي للحفظ ( '.($this->box->tribunal->centres_de_conservation ?? '').')');
        $sheet->getStyle('A3:G3')->applyFromArray($this->getInfoCellStyle());

        // Court Information (A2|B2)
        $sheet->setCellValue('A5', 'المحكمة: ');
        $sheet->getStyle('A5')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('B5:C5');
        $sheet->setCellValue('B5', ($this->box->tribunal->tribunal ?? ''));
        $sheet->getStyle('B5:C5')->applyFromArray($this->getInfoCellStyle());

        // Saving Base Number (C2|D2)
        $sheet->setCellValue('E5', 'رقم قاعدة الحفظ: ');
        $sheet->getStyle('E5')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('F5:G5');
        $sheet->setCellValue('F5', $this->box->saving_base_number);
        $sheet->getStyle('F5:G5')->applyFromArray($this->getInfoCellStyle());

        // File Type (A3|B3)
        $sheet->setCellValue('A6', 'نوع الملف: ');
        $sheet->getStyle('A6')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('B6:C6');
        $sheet->setCellValue('B6', $this->box->file_type);
        $sheet->getStyle('B6:C6')->applyFromArray($this->getInfoCellStyle());

        // Box Type (C3:D3)

        $sheet->setCellValue('E6', 'النوع: ');
        $sheet->getStyle('E6')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('F6:G6');
        $sheet->setCellValue('F6', $this->box->type);
        $sheet->getStyle('F6:G6')->applyFromArray($this->getInfoCellStyle());

        // Box Number (A4|D4)
        $sheet->setCellValue('A7', 'رقم العلبة: ');
        $sheet->getStyle('A7')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('B7:C7');
        $sheet->setCellValue('B7', $this->box->box_number);
        $sheet->getStyle('B7:C7')->applyFromArray($this->getInfoCellStyle());

        // Files Count (C4:D4)
 
        $sheet->setCellValue('E7', 'عدد الملفات: ');
        $sheet->getStyle('E7')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('F7:G7');
        $sheet->setCellValue('F7', $this->box->files->count());
        $sheet->getStyle('F7:G7')->applyFromArray($this->getInfoCellStyle());

        // Judgment Year (A5|D5)
        $sheet->setCellValue('A8', 'سنة الحكم: ');
        $sheet->getStyle('A8')->applyFromArray($this->getInfoCellStyle());
        $sheet->mergeCells('B8:C8');
        $sheet->setCellValue('B8',$this->box->year_of_judgment);
        $sheet->getStyle('B8:C8')->applyFromArray($this->getInfoCellStyle());

        // Empty space for balance (C5:D5)
        // $sheet->mergeCells('E8:F8');
        // $sheet->getStyle('E8:F8')->applyFromArray($this->getInfoCellStyle());

        // ======= FILES DATA HEADERS ========
        $headerRow = 10;
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
             $sheet->getRowDimension($headerRow)->setRowHeight(80);
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
        $dataStartRow = 11;
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
        // $sheet->getPageSetup()->setPrintArea('A1:G'.$dataEndRow);
        // Fit to 1 page wide (A to G) and auto height
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
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

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Court Logo');
        $drawing->setPath(public_path('images/court_logo.png'));
        $drawing->setHeight(180);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(0);
        $drawing->setOffsetY(0);
        
        return [$drawing];
    }

    
}