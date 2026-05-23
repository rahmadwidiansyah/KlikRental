<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Dijalankan di Local & Production (Aman karena datanya hardcode)
        $this->call([
            UserSeeder::class,
        ]);

        // 2. HANYA dijalankan di Local (Karena pakai fake() / Faker)
        if (app()->environment('local')) {
            $this->call([
                ZoneSeeder::class,
                VehicleSeeder::class,
                DriverSeeder::class,
                PromoSeeder::class,
            ]);
        }
    }
}