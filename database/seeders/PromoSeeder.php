<?php

namespace Database\Seeders;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        Promo::create([
            'code' => 'KLIKRENTAL2026',
            'discount_percentage' => 10,
            'max_discount' => 50000,
            'valid_until' => '2026-12-31'
        ]);
    }
}
