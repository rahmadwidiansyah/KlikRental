<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    // Relasi ke banyak foto
    public function images() {
        return $this->hasMany(VehicleImage::class);
    }

    // Relasi tambahan untuk mengambil 1 foto utama saja (berguna untuk di halaman depan Katalog)
    public function primaryImage() {
        return $this->hasOne(VehicleImage::class)->where('is_primary', true);
    }
}
