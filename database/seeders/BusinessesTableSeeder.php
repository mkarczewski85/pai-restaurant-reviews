<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Business::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Business::create([
                'name' => $faker->company,
                'description' => $faker->sentence,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->country,
                'zip_code' => $faker->postcode,
                'avg_rating' => floatval(rand(100, 500)) / 100,
                'total_reviews' => rand(1, 100),
                'price_level' => rand(1, 4),
                'business_category_id' => 1,
            ]);
        }
    }
}
