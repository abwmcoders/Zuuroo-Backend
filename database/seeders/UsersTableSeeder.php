<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'mobile' => '1234567890',
                'telephone' => 1234,
                'isVerified' => 1,
                'dob' => '1990-01-01',
                'username' => 'johndoe',
                'gender' => 'male',
                'address' => '123 Main St',
                'zipcode' => '12345',
                'country' => 'USA',
                'create_pin' => '1234',
                'status' => 1,
                'remember_token' => 'sampletoken123',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
