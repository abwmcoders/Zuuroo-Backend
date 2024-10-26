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
                'plan' => 'GOtv Max',
                'price' => '7200',
                'provider_code' => '2',
            ],
            [
                'plan' => 'DStv Yanga',
                'price' => '5100',
                'provider_code' => '6',
            ],
            [
                'plan' => 'DStv Compact	',
                'price' => '15700',
                'provider_code' => '7',
            ],
            [
                'plan' => 'DStv Compact Plus',
                'price' => '25000',
                'provider_code' => '8',
            ],
            [
                'plan' => 'DStv Premium',
                'price' => '37000',
                'provider_code' => '9',
            ],
            [
                'plan' => 'Classic - 5000 Naira - 1 Mont',
                'price' => '5000',
                'provider_code' => '11',
            ],
            [
                'plan' => 'Basic - 3300 Naira - 1 Month',
                'price' => '3300',
                'provider_code' => '12',
            ],
            [
                'plan' => 'Smart - 4200 Naira - 1 Month',
                'price' => '13',
                'provider_code' => '4200',
            ],
            [
                'plan' => 'Nova - 1700 Naira - 1 Month',
                'price' => '14',
                'provider_code' => '1700',
            ],
            [
                'plan' => 'Super - 8000 Naira - 1 Month',
                'price' => '15',
                'provider_code' => '8000',
            ],
            [
                'plan' => 'GOtv Jinja',
                'price' => '16',
                'provider_code' => '3300',
            ],
            [
                'plan' => 'GOtv Jolli',
                'price' => '17',
                'provider_code' => '4850',
            ],
            [
                'plan' => 'DStv Confam',
                'price' => '19',
                'provider_code' => '9300',
            ],
            [
                'plan' => 'DStv Padi',
                'price' => '20',
                'provider_code' => '2950',
            ],
            [
                'plan' => 'DStv Asia',
                'price' => '23',
                'provider_code' => '12400',
            ],
            [
                'plan' => 'DStv Premium French',
                'price' => '24',
                'provider_code' => '57500',
            ],
            [
                'plan' => 'DStv Premium Asia',
                'price' => '25',
                'provider_code' => '42000',
            ],
            [
                'plan' => 'DStv Confam + ExtraView',
                'price' => '26',
                'provider_code' => '14300',
            ],
            [
                'plan' => 'DStv Yanga + ExtraView',
                'price' => '27',
                'provider_code' => '10100',
            ],
            [
                'plan' => 'DStv Padi + ExtraView',
                'price' => '28',
                'provider_code' => '8600',
            ],
            [
                'plan' => 'DStv Compact + Extra View',
                'price' => '29',
                'provider_code' => '20700',
            ],
            [
                'plan' => 'DStv Premium + Extra View',
                'price' => '30',
                'provider_code' => '42000',
            ],
            [
                'plan' => 'DStv Compact Plus - Extra View',
                'price' => '31',
                'provider_code' => '37400',
            ],
            [
                'plan' => 'ExtraView Access',
                'price' => '33',
                'provider_code' => '5000',
            ],
            [
                'plan' => 'GOtv Smallie - Monthly',
                'price' => '34',
                'provider_code' => '1575',
            ],
            [
                'plan' => 'GOtv Smallie - Quarterly',
                'price' => '35',
                'provider_code' => '4175',
            ],
            [
                'plan' => 'GOtv Smallie - Yearly',
                'price' => '36',
                'provider_code' => '12300',
            ],
            [
                'plan' => 'Nova - 500 Naira - 1 Week',
                'price' => '37',
                'provider_code' => '500',
            ],
            [
                'plan' => 'Basic - 1100 Naira - 1 Week',
                'price' => '38',
                'provider_code' => '1100',
            ],
            [
                'plan' => 'Smart - 1500 Naira - 1 Week',
                'price' => '39',
                'provider_code' => '1500',
            ],
            [
                'plan' => 'Classic - 1700 Naira - 1 Week',
                'price' => '40',
                'provider_code' => '1700',
            ],
            [
                'plan' => 'Super - 2,700 Naira - 1 Week',
                'price' => '41',
                'provider_code' => '2700',
            ],
            [
                'plan' => 'GOTv SUPA',
                'price' => '47',
                'provider_code' => '9600',
            ],
            [
                'plan' => 'Super - 8000 Naira - 1 Month',
                'price' => '48',
                'provider_code' => '8000',
            ],
            [
                'plan' => 'GOTv SUPA PLUS',
                'price' => '49',
                'provider_code' => '15700',
            ],
        ];

        foreach ($plans as $plan) {
            CablePlan::create($plan);
        }
    }
}
