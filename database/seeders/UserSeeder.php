<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin KlikRental',
            'email' => 'admin@klikrental.com',
            'password' => Hash::make('password123'),
            'phone_number' => '081234567890',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'phone_number' => '089876543210',
            'role' => 'customer',
        ]);
    }
}
