<?php

namespace Database\Seeders;

use App\Models\Box;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["file_number" => 5782, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5781, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5780, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5779, "year_of_opening" => 2006, "judgment_date" => "11/07/2006"],
            ["file_number" => 5778, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5777, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5775, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5774, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5773, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5772, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5771, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5770, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5769, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5759, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5767, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5766, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5758, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5757, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5756, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5755, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5754, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5753, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5752, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5751, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5763, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5765, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5747, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5748, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5749, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5750, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5735, "year_of_opening" => 2006, "judgment_date" => "03/11/2006"],
            ["file_number" => 5733, "year_of_opening" => 2006, "judgment_date" => "03/11/2006"],
            ["file_number" => 5746, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5744, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5760, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5762, "year_of_opening" => 2006, "judgment_date" => "07/11/2006"],
            ["file_number" => 5800, "year_of_opening" => 2006, "judgment_date" => "08/11/2006"],
        ];

        $box = Box::findOrFail(1); // change ID to the box you want

        $maxOrder = $box->files()->max('order') ?? 0;
        $orderCounter = $maxOrder + 1;

        foreach ($data as $fileData) {
            $box->files()->create([
                'file_number'     => $fileData['file_number'],
                'symbol'          => null,
                'year_of_opening' => $fileData['year_of_opening'],
                'judgment_number' => null,
                'judgment_date'   => \Carbon\Carbon::parse($fileData['judgment_date']),
                'order'           => $orderCounter++,
                'remark'          => null,
            ]);
        }
        $box->update([
            'total_files' => $box->files()->count(),
        ]);

    }
}
