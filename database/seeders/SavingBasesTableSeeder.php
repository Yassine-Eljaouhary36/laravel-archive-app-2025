<?php

namespace Database\Seeders;

use App\Models\SavingBase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SavingBasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $bases = [
                ['number' => 'قاعدة 1', 'description' => 'المحكمة الابتدائية'],
                ['number' => 'قاعدة 2', 'description' => 'محكمة الاستئناف'],
                // etc...
            ];
            
            foreach ($bases as $base) {
                SavingBase::create($base);
            }
    }
}
