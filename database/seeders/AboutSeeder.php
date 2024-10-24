<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create([
            'title' => 'About Us',
            'description' => "Being away from your loved ones weakens communication. Zuuro, with the aim to strengthen communication at home and abroad, provides you with viable options for staying connected. With our customer's satisfaction as a priority, Zuuro offers mobile recharge/mobile recharge loan services that allow users to send mobile top-ups to friends and families anywhere in the world, helping them to stay connected all day, every day.
",
            'status' => 'active',
        ]);

        About::create([
            'title' => 'Best Strategy',
            'description' => 'Zuuro uses the best strategy to provide customer satisfactory services that runs at an optimal speed.
',
            'status' => 'active',
        ]);

        About::create([
            'title' => 'Creative Ideas',
            'description' => 'With the best technology, we bring out diverse opportunity to our customers satisfaction.
',
            'status' => 'active',
        ]);

        About::create([
            'title' => 'Brief About Zuuroo!',
            'description' => "Zuuro is a leading mobile recharge company in Nigeria that provides microcredit as loans to the people who have a low balance on their Globe or TM sim card worldwide.

Zuuro was founded to improve people’s lives by helping those with less, gain access to more.

Our aim has been to build & run the safest, simplest, most effective & convenient top-up service as loan, in partnership with the best operators and platforms. We provide more secure top-up loans to more countries, through more operators, helping people all around the world to send little bytes of happiness to their loved ones on loan in the blink of an eye.

We believe in giving mobile recharge loans to our customers when they have low balance on their SIM help them solve there emergency needs in calling or accessing the internet.",
            'status' => 'active',
        ]);
    }
}
