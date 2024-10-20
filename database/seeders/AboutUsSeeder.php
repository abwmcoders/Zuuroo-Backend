<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    public function run()
    {
        AboutUs::create([
            'company_name' => 'Zuuro VTU Services',
            'description' => 'Zuuro VTU provides seamless international data and airtime recharge services across Nigeria and beyond.',
            'headquarters' => 'Lagos, Nigeria',
            'contact_email' => 'info@zuurovtu.com',
            'contact_phone' => '+2348001234567',
            'website_url' => 'https://zuurovtu.com',
            'services_offered' => [
                'International Airtime Top-up',
                'Data Bundle Purchases',
                'Electricity Bill Payment',
                'Cable TV Subscription',
            ],
            'status' => 'active',
        ]);
    }
}
