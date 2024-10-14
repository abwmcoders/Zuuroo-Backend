<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecurringChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recurring_charges')->insert([
            [
                'user_id' => 2,
                'user_email' => 'berry@example.com',
                'authorization_code' => 'AUTH123456',
                'account_name' => 'Berry Made',
                'account_number' => '1234567890',
                'bank_name' => 'XYZ Bank',
                'country_code' => 'NG',
                'card_type' => 'Visa',
                'last4' => '1234',
                'exp_month' => '12',
                'exp_year' => '2026',
                'bin' => '123456',
                'channel' => 'card',
                'signature' => 'abcd1234',
                'reusable' => true,
                'status' => 'active',
            ],
        ]);
    }
}
