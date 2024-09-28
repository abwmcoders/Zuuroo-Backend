<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'TheMade',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password' => bcrypt('12345A./'),
            'address' => 'Number 23, water cooperation drive eti-osa',
            'phone_number' => '09087673212',
            'email' => 'test@example.com',
            'referral_code' => '12345',
            'email_verified_at' => time(),
        ]);
    }
}
