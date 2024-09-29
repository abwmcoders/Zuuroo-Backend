<?php

namespace Database\Seeders;

use App\Models\loan_history;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        loan_history::create([
            'user_id' => 1,
            'plan' => 'Standard Plan',
            'purchase' => 'Mobile Phone Purchase',
            'country_code' => 'US',
            'operator_code' => '1234',
            'product_code' => 'P123456',
            'transfer_ref' => 'TXN123456789',
            'phone_number' => '1234567890',
            'distribe_ref' => 'D123456',
            'selling_price' => 500,
            'receive_value' => '450',
            'send_value' => '500',
            'receive_currency' => 'USD',
            'commission_applied' => '10',
            'startedUtc' => '2024-09-29 12:00:00',
            'completedUtc' => '2024-09-30 12:00:00',
            'processing_state' => 'successful',
            'repayment' => '2024-10-30',
            'due_date' => '2024-10-15'
        ]);
    }
}
