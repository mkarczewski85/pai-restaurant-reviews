<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Bus;

class BusinessesTableSeeder extends Seeder
{

    const STOCK_MAIN_PHOTOS = [
        "https://media.istockphoto.com/id/1081422898/photo/pan-fried-duck.jpg?s=612x612&w=0&k=20&c=kzlrX7KJivvufQx9mLd-gMiMHR6lC2cgX009k9XO6VA=",
        "https://cdn.vuetifyjs.com/images/cards/cooking.png",
        "https://images.unsplash.com/photo-1600891964599-f61ba0e24092?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cmVzdGF1cmFudCUyMGZvb2R8ZW58MHx8MHx8fDA%3D&w=1000&q=80",
        "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.pexels.com%2Fsearch%2Ffood%2F&psig=AOvVaw0yg2hZgc5kC5TG5f6XcoGl&ust=1685968054776000&source=images&cd=vfe&ved=0CBEQjRxqFwoTCKjqu8rOqf8CFQAAAAAdAAAAABAJ",
        "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.pexels.com%2Fsearch%2Frestaurant%2520food%2F&psig=AOvVaw0yg2hZgc5kC5TG5f6XcoGl&ust=1685968054776000&source=images&cd=vfe&ved=0CBEQjRxqFwoTCKjqu8rOqf8CFQAAAAAdAAAAABAR",
    ];
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

        for ($i = 0; $i < 15; $i++) {
            Business::create([
                'name' => $faker->company,
                'description' => $faker->paragraph(10),
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->country,
                'zip_code' => $faker->postcode,
                'homepage' => rand(0, 1) == 1 ? $faker->url : null,
                'facebook_profile' =>  rand(0, 1) == 1 ? 'www.facebook.com' : null,
                'instagram_profile' =>  rand(0, 1) == 1 ? 'www.instagram.com': null,
                'avg_rating' => floatval(rand(100, 500)) / 100,
                'total_reviews' => rand(1, 100),
                'price_level' => rand(1, 4),
                'photos' => self::STOCK_PHOTOS_URLS,
                'main_photo' => self::STOCK_MAIN_PHOTOS[rand(0,4)],
                'business_category_id' => rand(1, 2),
            ]);
        }
    }
}
