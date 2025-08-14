<?php

namespace App\Imports;

use App\Models\Box;
use App\Models\File;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BoxImport implements ToModel, WithStartRow
{
    protected $tribunalId;
    protected $savingBaseId;
    protected $fileType;
    protected $boxType;
    protected $yearOfJudgment;
    protected $box;
    
    public function __construct($tribunalId, $savingBaseId, $fileType, $boxType, $yearOfJudgment = null)
    {
        $this->tribunalId = $tribunalId;
        $this->savingBaseId = $savingBaseId;
        $this->fileType = $fileType;
        $this->boxType = $boxType;
        $this->yearOfJudgment = $yearOfJudgment;
        $this->box = null; // Initialize as null
    }
    
    public function startRow(): int
    {
        return 11; // Start reading from row 11
    }

    public function model(array $row)
    {
        static $order = 1;
        
        // Create box on first row only
        if ($this->box === null) {
            $max = Box::where('tribunal_id', $this->tribunalId)
                    ->where('type', $this->boxType)
                    ->max('box_number');

            $next = is_numeric($max) ? $max + 1 : 1;

            $this->box = Box::create([
                'saving_base_id' => $this->savingBaseId,
                'box_number' => $next,
                'file_type' => $this->fileType,
                'type' => $this->boxType,
                'tribunal_id' => $this->tribunalId,
                'year_of_judgment' => $this->yearOfJudgment,
                'total_files' => 0, // Will be updated after counting files
                'user_id' => auth()->id(),
            ]);
        }
        
        // Skip rows with empty required fields
        if (empty($row[1]) || empty($row[2])) { // Column B (file_number) and C (year_of_opening)
            return null;
        }
        
        // Create file record
        return new File([
            'box_id' => $this->box->id,
            'file_number' => $row[1], // Column B
            'year_of_opening' => $row[2], // Column C
            'symbol' =>  null, 
            'judgment_number' =>  null, 
            'judgment_date' =>  null, 
            'remark' => null, 
            'order' => $order++,
        ]);
    }
    
    public function getBox()
    {
        return $this->box;
    }
}