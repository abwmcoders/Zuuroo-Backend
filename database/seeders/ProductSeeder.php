<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_code'   => 'ELEC001',
            'country_code'    => 'NG',
            'operator_code'   => 'MTN',
            'product_code'    => 'ELEC_TOPUP',
            'product_name'    => 'Electricity Topup',
            'product_price'   => '5000',
            'cost_price'      => '4800',
            'loan_price'      => '4900',
            'send_value'      => '5000',
            'send_currency'   => 'NGN',
            'receive_value'   => '5000',
            'receive_currency' => 'NGN',
            'commission_rate' => '5',
            'uat_number'      => '1234567890',
            'validity'        => '30 days',
            'status'          => 1,
        ]);

        Product::create([
            'category_code'   => 'AIR001',
            'country_code'    => 'NG',
            'operator_code'   => 'AIRTEL',
            'product_code'    => 'AIRTIME_TOPUP',
            'product_name'    => 'Airtime Topup',
            'product_price'   => '2000',
            'cost_price'      => '1950',
            'loan_price'      => '1900',
            'send_value'      => '2000',
            'send_currency'   => 'NGN',
            'receive_value'   => '2000',
            'receive_currency' => 'NGN',
            'commission_rate' => '2.5',
            'uat_number'      => '0987654321',
            'validity'        => 'No Expiry',
            'status'          => 1,
        ]);
    }
}
