<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_code' => 'TEL',
                'country_code' => 'US',
                'operator_code' => 'VZ',
                'product_code' => 'PROD001',
                'product_name' => 'Verizon Monthly Plan',
                'product_price' => 50.00,
                'cost_price' => 45.00,
                'loan_price' => 5.00,
                'send_value' => 50,
                'send_currency' => 'USD',
                'receive_value' => 50,
                'receive_currency' => 'USD',
                'commission_rate' => 0.1,
                'uat_number' => '1234567890',
                'validity' => '30 days',
                'status' => 1
            ],
            [
                'category_code' => 'TEL',
                'country_code' => 'NG',
                'operator_code' => 'MTN',
                'product_code' => 'PROD002',
                'product_name' => 'MTN Data Bundle',
                'product_price' => 20.00,
                'cost_price' => 18.00,
                'loan_price' => 2.00,
                'send_value' => 20,
                'send_currency' => 'USD',
                'receive_value' => 7400,
                'receive_currency' => 'NGN',
                'commission_rate' => 0.15,
                'uat_number' => '2348030000000',
                'validity' => '7 days',
                'status' => 1
            ],
            [
                'category_code' => 'TEL',
                'country_code' => 'UK',
                'operator_code' => 'VF',
                'product_code' => 'PROD003',
                'product_name' => 'Vodafone Prepaid Plan',
                'product_price' => 30.00,
                'cost_price' => 27.00,
                'loan_price' => 3.00,
                'send_value' => 30,
                'send_currency' => 'GBP',
                'receive_value' => 30,
                'receive_currency' => 'GBP',
                'commission_rate' => 0.05,
                'uat_number' => '441234567890',
                'validity' => '30 days',
                'status' => 1
            ],
        ]);
    }
}
