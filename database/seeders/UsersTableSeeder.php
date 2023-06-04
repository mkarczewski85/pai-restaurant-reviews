<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Maciej',
            'email' => 'test1@test.com',
            'password' => Hash::make('Password@123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Piotr',
            'email' => 'test2@test.com',
            'password' => Hash::make('Password@123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Mateusz',
            'email' => 'test3@test.com',
            'password' => Hash::make('Password@123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('Password@123'),
            'role' => 'admin',
        ]);
    }
}
