<?php

namespace Database\Seeders;

use App\Models\CablePlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CablePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'plan' => 'Basic',
                'price' => '₦1500',
                'channels' => ['News', 'Sports', 'Movies'],
                'provider_code' => 'DSTV001',
            ],
            [
                'plan' => 'Premium',
                'price' => '₦3000',
                'channels' => ['News', 'Sports', 'Movies', 'Documentaries'],
                'provider_code' => 'DSTV001',
            ],
            [
                'plan' => 'Family',
                'price' => '₦2000',
                'channels' => ['Kids', 'Movies', 'Lifestyle'],
                'provider_code' => 'DSTV001',
            ],
            [
                'plan' => 'Lite',
                'price' => '₦1000',
                'channels' => ['News', 'Lifestyle'],
                'provider_code' => 'GOTV002',
            ],
            [
                'plan' => 'Max',
                'price' => '₦2500',
                'channels' => ['News', 'Sports', 'Music', 'Movies'],
                'provider_code' => 'GOTV002',
            ],
            [
                'plan' => 'Compact',
                'price' => '₦1800',
                'channels' => ['Sports', 'Documentaries'],
                'provider_code' => 'GOTV002',
            ],
            [
                'plan' => 'Start Lite',
                'price' => '₦1200',
                'channels' => ['News', 'Movies', 'Music'],
                'provider_code' => 'STT003',
            ],
            [
                'plan' => 'Showmax Basic',
                'price' => '₦3500',
                'channels' => ['Movies', 'Lifestyle', 'Kids'],
                'provider_code' => 'SHOW004',
            ],
        ];

        foreach ($plans as $plan) {
            CablePlan::create($plan);
        }
    }
}
