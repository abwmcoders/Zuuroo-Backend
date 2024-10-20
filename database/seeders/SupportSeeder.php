<?php

namespace Database\Seeders;

use App\Models\Support;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supports = [
            [
                'page_type' => 'dashboard',
                'page_name' => 'Home',
                'page_link' => '/home',
                'page_icon' => 'home',
            ],
            [
                'page_type' => 'settings',
                'page_name' => 'Profile Settings',
                'page_link' => '/profile-settings',
                'page_icon' => 'settings',
            ],
            [
                'page_type' => 'vtu',
                'page_name' => 'Airtime Purchase',
                'page_link' => '/vtu/airtime',
                'page_icon' => 'phone_android',
            ],
            [
                'page_type' => 'vtu',
                'page_name' => 'Data Purchase',
                'page_link' => '/vtu/data',
                'page_icon' => 'wifi',
            ],
            [
                'page_type' => 'support',
                'page_name' => 'Customer Support',
                'page_link' => '/support',
                'page_icon' => 'support_agent',
            ],
            [
                'page_type' => 'billing',
                'page_name' => 'Transaction History',
                'page_link' => '/billing/history',
                'page_icon' => 'receipt_long',
            ],
            [
                'page_type' => 'auth',
                'page_name' => 'Login',
                'page_link' => '/login',
                'page_icon' => 'login',
            ],
            [
                'page_type' => 'auth',
                'page_name' => 'Register',
                'page_link' => '/register',
                'page_icon' => 'person_add',
            ],
        ];

        foreach ($supports as $support) {
            Support::create($support);
        }
    }
}
