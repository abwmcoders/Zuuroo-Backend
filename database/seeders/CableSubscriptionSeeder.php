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
            ['provider_name' => 'DSTV', 'provider_code' => '2', 'country_code' => 'NG', 'status' => 'active'],
            ['provider_name' => 'GOTV', 'provider_code' => '1', 'country_code' => 'NG', 'status' => 'active'],
            ['provider_name' => 'STARTIME', 'provider_code' => '3', 'country_code' => 'NG', 'status' => 'active'],
            //! ['provider_name' => 'Showmax', 'provider_code' => 'SHOW004', 'country_code' => 'NG', 'status' => 'active'],
        ];

        foreach ($cableProviders as $provider) {
            CableSubscription::create($provider);
        }
    }
}
