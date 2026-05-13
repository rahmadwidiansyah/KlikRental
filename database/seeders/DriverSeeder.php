<?php

namespace Database\Seeders;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::create(['name' => 'Budi Santoso', 'phone' => '08111222333', 'daily_rate' => 150000, 'status' => 'available']);
        Driver::create(['name' => 'Agus Pratama', 'phone' => '08222333444', 'daily_rate' => 150000, 'status' => 'available']);
    }
}
