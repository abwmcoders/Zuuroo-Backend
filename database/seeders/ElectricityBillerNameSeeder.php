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
            ['biller_name' => 'Ikeja Electric', 'biller_code' => 'IKEJA', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Eko Electricity Distribution Company', 'biller_code' => 'EKO', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Abuja Electricity Distribution Company', 'biller_code' => 'ABUJA', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Kano Electricity Distribution Company', 'biller_code' => 'KANO', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Port Harcourt Electricity Distribution Company', 'biller_code' => 'PH', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Ibadan Electricity Distribution Company', 'biller_code' => 'IBADAN', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Jos Electricity Distribution Company', 'biller_code' => 'JOS', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Kaduna Electricity Distribution Company', 'biller_code' => 'KADUNA', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Benin Electricity Distribution Company', 'biller_code' => 'BENIN', 'country_code' => 'NG', 'status' => 1],
            ['biller_name' => 'Enugu Electricity Distribution Company', 'biller_code' => 'ENUGU', 'country_code' => 'NG', 'status' => 1],
        ];

        DB::table('electricity_biller_names')->insert($discos);
    }
}
