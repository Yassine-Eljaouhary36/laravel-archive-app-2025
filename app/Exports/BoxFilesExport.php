<?php

namespace App\Exports;

use App\Models\Box;
use Carbon\Carbon;
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
    
    // Constants for dynamic row positioning
    protected const LOGO_ROW = 1;
    protected const TITLE_ROW = 3;
    protected const INFO_START_ROW = 5;
    protected const HEADER_ROW = 10;
    protected const DATA_START_ROW = 11;

    public function __construct(Box $box)
    {
        $this->box = $box->load(['files', 'tribunal']);
    }

    public function collection()
    {
        return $this->box->files->map(function ($file, $index) {
            return [
                'A' => $index + 1, // Sequential number
                'B' => $file->file_number,
                'C' => $file->year_of_opening,
                'D' => $file->symbol,
                'E' => $file->judgment_number ?? 'غير متوفر',
                'F' => $file->judgment_date 
                    ? Carbon::parse($file->judgment_date)->format('Y-m-d')
                    : 'غير متوفر',
                'G' => $file->remark ?? ''
            ];
        });
    }

    public function headings(): array
    {
        return []; // Disable automatic headers
    }

    public function styles(Worksheet $sheet)
    {
        // Clear any residual data in rows 1-9
        $this->clearUnusedCells($sheet);

        // Set RTL direction and default font
        $sheet->setRightToLeft(true);
        $sheet->getParent()->getDefaultStyle()->getFont()
            ->setName('Sakkal Majalla')
            ->setSize(14);

        // ======= LOGO & TITLE SECTION ========
        $this->addLogoAndTitle($sheet);

        // ======= BOX INFORMATION SECTION ========
        $this->addBoxInfoSection($sheet);

        // ======= FILES DATA HEADERS ========
        $this->addDataHeaders($sheet);

        // ======= FILES DATA ROWS ========
        $this->addDataRows($sheet);

        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Configure print settings
        $sheet->getPageSetup()
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
    }

    protected function clearUnusedCells(Worksheet $sheet)
    {
        for ($row = 1; $row < self::HEADER_ROW; $row++) {
            for ($col = 'A'; $col <= 'G'; $col++) {
                $sheet->setCellValue($col.$row, '');
            }
        }
    }

    protected function addLogoAndTitle(Worksheet $sheet)
    {
        // Logo row (A1:G1)
        $sheet->mergeCells('A1:G1');
        $sheet->getRowDimension(self::LOGO_ROW)->setRowHeight(150);
        $sheet->getStyle('A1:G1')->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Title row (A3:G3)
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'جرد تفصيلي للملفات المحالة على المركز الجهوي للحفظ');
        $sheet->getStyle('A3:G3')->applyFromArray($this->getInfoCellStyle());
    }

    protected function addBoxInfoSection(Worksheet $sheet)
    {
        // Set row heights for info section
        foreach (range(self::INFO_START_ROW, self::INFO_START_ROW + 3) as $row) {
            $sheet->getRowDimension($row)->setRowHeight(50);
        }

        // Court Information (Row 5)
        $sheet->setCellValue('A5', 'المحكمة: ');
        $sheet->mergeCells('B5:C5');
        $sheet->setCellValue('B5', ($this->box->tribunal->tribunal ?? ''));
        
        // Saving Base Number (Row 5)
        $sheet->setCellValue('E5', 'رقم قاعدة الحفظ: ');
        $sheet->mergeCells('F5:G5');
        $sheet->setCellValue('F5', $this->box->savingBase->number);

        // File Type (Row 6)
        $sheet->setCellValue('A6', 'نوع الملف: ');
        $sheet->mergeCells('B6:C6');
        $sheet->setCellValue('B6', $this->box->file_type);

        // Box Type (Row 6)
        $sheet->setCellValue('E6', 'النوع: ');
        $sheet->mergeCells('F6:G6');
        $sheet->setCellValue('F6', $this->box->type);

        // Box Number (Row 7)
        $sheet->setCellValue('A7', 'رقم العلبة: ');
        $sheet->mergeCells('B7:C7');
        $sheet->setCellValue('B7', $this->box->box_number);

        // Files Count (Row 7)
        $sheet->setCellValue('E7', 'عدد الملفات: ');
        $sheet->mergeCells('F7:G7');
        $sheet->setCellValue('F7', $this->box->files->count());

        // Judgment Year (Row 8)
        $sheet->setCellValue('A8', 'سنة الحكم: ');
        $sheet->mergeCells('B8:C8');
        $sheet->setCellValue('B8', $this->box->year_of_judgment ?? 'غير متوفر');

        // Apply styling to all info cells
        $sheet->getStyle('A5:C8')->applyFromArray($this->getInfoCellStyle());
        $sheet->getStyle('E5:G7')->applyFromArray($this->getInfoCellStyle());
    }

    protected function addDataHeaders(Worksheet $sheet)
    {
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
            $sheet->setCellValue($col . self::HEADER_ROW, $header);
        }

        $sheet->getRowDimension(self::HEADER_ROW)->setRowHeight(80);
        $sheet->getStyle('A' . self::HEADER_ROW . ':G' . self::HEADER_ROW)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => Color::COLOR_WHITE]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2F5496']
            ],
            'borders' => $this->getBorderStyle(),
            'alignment' => $this->getAlignmentStyle()
        ]);
    }

    protected function addDataRows(Worksheet $sheet)
    {
        $filesData = $this->collection()->toArray();
        $dataEndRow = self::DATA_START_ROW + count($filesData) - 1;

        foreach ($filesData as $rowIndex => $rowData) {
            foreach ($rowData as $col => $cellData) {
                $sheet->setCellValue($col . (self::DATA_START_ROW + $rowIndex), $cellData);
            }
        }

        if ($dataEndRow >= self::DATA_START_ROW) {
            $sheet->getStyle('A' . self::DATA_START_ROW . ':G' . $dataEndRow)
                ->applyFromArray([
                    'borders' => $this->getBorderStyle(),
                    'alignment' => $this->getAlignmentStyle()
                ]);

            // Alternate row coloring
            for ($row = self::DATA_START_ROW; $row <= $dataEndRow; $row++) {
                $fillColor = ($row % 2 == 0) ? 'FFFFFF' : 'E7E6E6';
                $sheet->getStyle('A' . $row . ':G' . $row)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($fillColor);
                $sheet->getRowDimension($row)->setRowHeight(25);
            }
        }
    }

    protected function getInfoCellStyle()
    {
        return [
            'font' => ['bold' => true, 'size' => 14],
            'borders' => $this->getBorderStyle(),
            'alignment' => $this->getAlignmentStyle()
        ];
    }

    protected function getBorderStyle()
    {
        return [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ];
    }

    protected function getAlignmentStyle()
    {
        return [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
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
        
        return [$drawing];
    }
}