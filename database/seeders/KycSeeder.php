<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KycSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kycs')->insert([
            [
                'user_id' => 1,
                'countryC_code' => 'US',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'transaction_ref' => 'TXN123456',
                'id_number' => 'A123456789',
                'id_type' => 'passport',
                'date_of_birth' => '1985-06-15',
                'verify_status' => 'pending',
            ],
            [
                'user_id' => 2,
                'countryC_code' => 'NG',
                'first_name' => 'berry',
                'last_name' => 'made',
                'transaction_ref' => 'TXN987654',
                'id_number' => 'B987654321',
                'id_type' => 'driver_license',
                'date_of_birth' => '1990-04-22',
                'verify_status' => 'verified',
            ],
        ]);
    }
}
