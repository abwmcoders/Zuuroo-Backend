<?php

namespace Database\Seeders;

use App\Models\History;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        History::create([
            'user_id' => 1,
            'plan' => 'Monthly Subscription',
            'purchase' => 'Subscription Purchase',
            'country_code' => 'US',
            'operator_code' => 'AT&T',
            'product_code' => 'ATT001',
            'transfer_ref' => 'TRANS123456',
            'phone_number' => '1234567890',
            'distribe_ref' => 'DISTR001',
            'selling_price' => 5000,
            'cost_price' => 4500,
            'receive_value' => '4500',
            'send_value' => '5000',
            'receive_currency' => 'USD',
            'commission_applied' => '10%',
            'startedUtc' => '2023-09-28 10:00:00',
            'completedUtc' => '2023-09-28 11:00:00',
            'processing_state' => 'successful',
        ]);
    }
}
