<?php

namespace Database\Seeders;

use App\Models\CableSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CableSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cableProviders = [
            ['provider_name' => 'DSTV', 'provider_code' => 'DSTV001', 'country_code' => 'NG', 'status' => 'active'],
            ['provider_name' => 'GOTV', 'provider_code' => 'GOTV002', 'country_code' => 'NG', 'status' => 'active'],
            ['provider_name' => 'Startimes', 'provider_code' => 'STT003', 'country_code' => 'NG', 'status' => 'inactive'],
            ['provider_name' => 'Showmax', 'provider_code' => 'SHOW004', 'country_code' => 'NG', 'status' => 'active'],
        ];

        foreach ($cableProviders as $provider) {
            CableSubscription::create($provider);
        }
    }
}
