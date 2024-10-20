<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_categories')->insert([
            [
                'operator_code' => 'VZ',
                'category_name' => 'Mobile Data',
                'category_code' => 'DATA',
                'status' => 1
            ],
            [
                'operator_code' => 'MTN',
                'category_name' => 'Airtime Recharge',
                'category_code' => 'AIRTIME',
                'status' => 1
            ],
            [
                'operator_code' => 'VF',
                'category_name' => 'Voice Plan',
                'category_code' => 'VOICE',
                'status' => 1
            ],
            [
                'operator_code' => 'RG',
                'category_name' => 'SMS Bundles',
                'category_code' => 'SMS',
                'status' => 1
            ],
            [
                'operator_code' => 'TEL',
                'category_name' => 'Internet Plan',
                'category_code' => 'INTERNET',
                'status' => 1
            ],
        ]);
    }
}