<?php

namespace Database\Seeders;

use App\Models\TermCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TermCondition::create([
            'id' => 1,
            'write_up' => 'These are the terms and conditions of using our service.',
            'admin' => 'Admin Name'
        ]);

        TermCondition::create([
            'id' => 2,
            'write_up' => 'By using this platform, you agree to all the conditions mentioned.',
            'admin' => 'Another Admin'
        ]);
    }
}
