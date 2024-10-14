<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operators')->insert([
            [
                'country_code' => 'US',
                'operator_name' => 'Verizon',
                'operator_code' => 'VZ',
                'validation_regex' => '^\+1\d{10}$', // Sample regex for US phone numbers
                'logo_url' => 'https://example.com/verizon_logo.png',
                'status' => 1
            ],
            [
                'country_code' => 'NG',
                'operator_name' => 'MTN',
                'operator_code' => 'MTN',
                'validation_regex' => '^\+234\d{10}$', // Sample regex for Nigeria phone numbers
                'logo_url' => 'https://example.com/mtn_logo.png',
                'status' => 1
            ],
            [
                'country_code' => 'UK',
                'operator_name' => 'Vodafone',
                'operator_code' => 'VF',
                'validation_regex' => '^\+44\d{10}$', // Sample regex for UK phone numbers
                'logo_url' => 'https://example.com/vodafone_logo.png',
                'status' => 1
            ],
            [
                'country_code' => 'CA',
                'operator_name' => 'Rogers',
                'operator_code' => 'RG',
                'validation_regex' => '^\+1\d{10}$', // Sample regex for Canada phone numbers
                'logo_url' => 'https://example.com/rogers_logo.png',
                'status' => 1
            ],
            [
                'country_code' => 'AU',
                'operator_name' => 'Telstra',
                'operator_code' => 'TEL',
                'validation_regex' => '^\+61\d{9}$', // Sample regex for Australia phone numbers
                'logo_url' => 'https://example.com/telstra_logo.png',
                'status' => 1
            ],
        ]);
    }
}
