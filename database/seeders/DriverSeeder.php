<?php

namespace Database\Seeders;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::create(['name' => 'Budi Santoso', 'phone_number' => '08111222333', 'image_url' => null, 'daily_rate' => 150000, 'status' => 'available']);
        Driver::create(['name' => 'Agus Pratama', 'phone_number' => '08222333444', 'image_url' => null, 'daily_rate' => 150000, 'status' => 'available']);
    }
}