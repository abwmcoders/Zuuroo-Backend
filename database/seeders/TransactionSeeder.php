<?php

namespace Database\Seeders;

use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 15; $i++) {
            $data[] = [
                'user_id' => rand(1, 10),
                'plan' => 'Plan ' . rand(1, 5),
                'purchase' => 'Purchase ' . Str::random(10),
                'country_code' => 'NG',
                'operator_code' => 'OP' . rand(100, 999),
                'product_code' => 'PR' . rand(1000, 9999),
                'transfer_ref' => Str::uuid(),
                'phone_number' => '080' . rand(10000000, 99999999),
                'distribe_ref' => Str::random(10),
                'selling_price' => rand(1000, 5000),
                'cost_price' => rand(500, 999),
                'receive_value' => rand(100, 1000),
                'send_value' => rand(200, 1500),
                'receive_currency' => 'NGN',
                'commission_applied' => rand(1, 10) . '%',
                'startedUtc' => Carbon::now()->subMinutes(rand(1, 60)),
                'completedUtc' => Carbon::now()->addMinutes(rand(1, 30)),
                'processing_state' => ['pending', 'completed', 'failed'][rand(0, 2)],
            ];
        }

        History::insert($data);
    }
}
