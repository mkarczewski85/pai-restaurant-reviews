<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;

class BusinessesTableSeeder extends Seeder
{
    const STOCK_PHOTOS_URLS = [
        "https://images.pexels.com/photos/67468/pexels-photo-67468.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
        "https://images.pexels.com/photos/2074130/pexels-photo-2074130.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1",
        "https://images.pexels.com/photos/1484516/pexels-photo-1484516.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
    ];
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
                'description' => $faker->paragraph(10),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->country,
                'zip_code' => $faker->postcode,
                'avg_rating' => floatval(rand(100, 500)) / 100,
                'total_reviews' => rand(1, 100),
                'price_level' => rand(1, 4),
                'photos' => json_encode(self::STOCK_PHOTOS_URLS),
                'business_category_id' => rand(1, 2),
            ]);
        }
    }
}
