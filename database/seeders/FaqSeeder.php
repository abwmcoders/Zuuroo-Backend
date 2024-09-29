<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Faq::create([
            'question' => 'What is Laravel?',
            'answer' => 'Laravel is a web application framework with expressive, elegant syntax.'
        ]);

        Faq::create([
            'question' => 'How to install Laravel?',
            'answer' => 'You can install Laravel by running "composer create-project --prefer-dist laravel/laravel project-name".'
        ]);

        Faq::create([
            'question' => 'What is a seeder in Laravel?',
            'answer' => 'A seeder is used to populate database tables with sample or testing data.'
        ]);
    }
}
