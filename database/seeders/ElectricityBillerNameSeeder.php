<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElectricityBillerNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discos = [
            ['biller_name' => 'Ikeja Electric', 'biller_code' => '1', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Eko Electric', 'biller_code' => '2', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Abuja Electric', 'biller_code' => '3', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Kano Electric', 'biller_code' => '4', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Enugu Electric', 'biller_code' => '5', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Port Harcourt Electric', 'biller_code' => '6', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Ibadan Electric', 'biller_code' => '7', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Kaduna Electric', 'biller_code' => '8', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Jos Electric', 'biller_code' => '9', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Benin Electric', 'biller_code' => '10', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Yola Electric', 'biller_code' => '11', 'country_code' => 'NG', 'status' => 1],
        ];

        DB::table('electricity_biller_names')->insert($discos);
    }
}
