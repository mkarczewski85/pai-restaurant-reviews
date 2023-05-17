<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        BusinessCategory::truncate();

        BusinessCategory::create([
            'name' => 'Restauracja'
        ]);

        BusinessCategory::create([
            'name' => 'Kawiarnia'
        ]);
    }
}
