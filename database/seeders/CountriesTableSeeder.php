<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            [
                'country_name' => 'United States',
                'country_code' => 'US',
                'is_loan' => true,
                'phone_code' => '+1',
                'status' => 1
            ],
            [
                'country_name' => 'Canada',
                'country_code' => 'CA',
                'is_loan' => false,
                'phone_code' => '+1',
                'status' => 1
            ],
            [
                'country_name' => 'United Kingdom',
                'country_code' => 'UK',
                'is_loan' => true,
                'phone_code' => '+44',
                'status' => 1
            ],
            [
                'country_name' => 'Australia',
                'country_code' => 'AU',
                'is_loan' => false,
                'phone_code' => '+61',
                'status' => 1
            ],
            [
                'country_name' => 'Nigeria',
                'country_code' => 'NG',
                'is_loan' => true,
                'phone_code' => '+234',
                'status' => 1
            ],
        ]);
    }
}
