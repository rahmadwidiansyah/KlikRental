<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'name' => 'Toyota Avanza 2023',
                'type' => 'MPV',
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'seats' => 7,
                'luggage_capacity' => 2,
                'price_per_day' => 350000,
                'status' => 'available',
            ],
            [
                'name' => 'Honda Brio RS',
                'type' => 'Hatchback',
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'seats' => 5,
                'luggage_capacity' => 1,
                'price_per_day' => 300000,
                'status' => 'available',
            ],
            [
                'name' => 'Toyota Innova Reborn',
                'type' => 'MPV',
                'transmission' => 'Manual',
                'fuel_type' => 'Diesel',
                'seats' => 7,
                'luggage_capacity' => 3,
                'price_per_day' => 500000,
                'status' => 'available',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
