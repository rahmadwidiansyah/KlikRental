<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            ['zone_name' => 'Simpang Lima (Pusat Kota)', 'additional_cost' => 0],
            ['zone_name' => 'Bandara Jenderal Ahmad Yani', 'additional_cost' => 50000],
            ['zone_name' => 'Stasiun Semarang Tawang', 'additional_cost' => 35000],
            ['zone_name' => 'Tembalang / Banyumanik', 'additional_cost' => 45000],
            ['zone_name' => 'Pelabuhan Tanjung Emas', 'additional_cost' => 60000],
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }
    }
}
