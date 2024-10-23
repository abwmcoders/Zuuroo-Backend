<?php

namespace Database\Seeders;

use App\Models\MaxLimit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaxLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaxLimit::create([
            'topup' => 'data',
            'limit_value' => 200,
            'admin' => 1,
        ]);

        MaxLimit::create([
            'topup' => 'airtime',
            'limit_value' => 200,
            'admin' => 1,
        ]);

        MaxLimit::create([
            'topup' => 'cable',
            'limit_value' => 2000,
            'admin' => 1,
        ]);

        MaxLimit::create([
            'topup' => 'bill',
            'limit_value' => 2000,
            'admin' => 1,
        ]);
    }
}
