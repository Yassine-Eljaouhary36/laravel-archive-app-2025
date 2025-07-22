<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TribunauxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('data/tribunaux.csv');
        $data = array_map('str_getcsv', file($file));
        foreach ($data as $row) {
            DB::table('tribunaux')->insert([
                'tribunal' => $row[2],
                'circonscription_judiciaire' => $row[1],
                'active' => false,
            ]);
        }
    }
}
