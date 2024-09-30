<?php

namespace Database\Seeders;

use App\Models\SimServer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SimServer::create([
            'operator_code' => 1,
            'sim_server' => 'Airtime_vtpass',
            'client_id' => 'client_id_1',
            'client_secret' => 'client_secret_1',
            'access_token' => 'access_token_1',
            'public_key' => 'public_key_1',
            'secret_key' => 'secret_key_1',
        ]);

        SimServer::create([
            'operator_code' => 2,
            'sim_server' => 'Airtime_example',
            'client_id' => 'client_id_2',
            'client_secret' => 'client_secret_2',
            'access_token' => 'access_token_2',
            'public_key' => 'public_key_2',
            'secret_key' => 'secret_key_2',
        ]);

    }
}
