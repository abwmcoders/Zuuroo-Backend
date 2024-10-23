<?php

namespace Database\Seeders;

use App\Models\LoanLimit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoanLimit::create([
            'labelName' => '5',
            'percentage' => 20,
            'status' => true,
        ]);

        LoanLimit::create([
            'labelName' => '7',
            'percentage' => 25,
            'status' => true,
        ]);

        LoanLimit::create([
            'labelName' => '3',
            'percentage' => 15,
            'status' => true,
        ]);
    }
}
