<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            for ($j = 0; $j < 20; $j++) {
                Review::create([
                    'review_text' => $faker->paragraph(5),
                    'rating' => rand(1, 5),
                    'useful_count' => rand(1, 100),
                    'business_id' => $i,
                    'user_id' => 1,
                ]);
            }
        }
    }
}
