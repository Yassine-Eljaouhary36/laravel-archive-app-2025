<?php

namespace Database\Seeders;

use App\Models\SavingBase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavingBasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $path = database_path('data/saving_bases.csv');
        if (!file_exists($path)) {
            echo "CSV file not found: $path\n";
            return;
        }

        $file = fopen($path, 'r');
        fgetcsv($file); // skip header

        while (($data = fgetcsv($file)) !== false) {
            DB::table('saving_bases')->insert([
                'number' => $data[0],
                'description' => $data[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
