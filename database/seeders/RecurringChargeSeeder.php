<?php

namespace Database\Seeders;

use App\Models\RecurringCharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecurringChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RecurringCharge::create([
            'user_id' => 1,
            'user_email' => 'user1@example.com',
            'authorization_code' => 'AUTH_123456789',
            'account_name' => 'John Doe',
            'account_number' => '1234567890',
            'bank_name' => 'Sample Bank',
            'country_code' => 'US',
            'card_type' => 'Visa',
            'last4' => '1234',
            'exp_month' => '12',
            'exp_year' => '2025',
            'bin' => '123456',
            'channel' => 'online',
            'signature' => 'SIGNATURE_12345',
            'reusable' => 'yes',
            'status' => 'active',
        ]);

        RecurringCharge::create([
            'user_id' => 2,
            'user_email' => 'user2@example.com',
            'authorization_code' => 'AUTH_987654321',
            'account_name' => 'Jane Smith',
            'account_number' => '9876543210',
            'bank_name' => 'Another Bank',
            'country_code' => 'UK',
            'card_type' => 'Mastercard',
            'last4' => '5678',
            'exp_month' => '06',
            'exp_year' => '2024',
            'bin' => '654321',
            'channel' => 'mobile',
            'signature' => 'SIGNATURE_67890',
            'reusable' => 'no',
            'status' => 'inactive',
        ]);
    }
}
