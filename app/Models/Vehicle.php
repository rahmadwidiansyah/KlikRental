<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'type', 
        'transmission',     
        'fuel_type',        
        'seats',            
        'luggage_capacity',
        'price_per_day', 
        'status'
    ];

    // Relasi ke banyak foto
    public function images() {
        return $this->hasMany(VehicleImage::class);
    }

   
    public function primaryImage() {
        return $this->hasOne(VehicleImage::class)->where('is_primary', true);
    }
    // Relasi Ulasan (melalui tabel bookings)
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Booking::class);
    }
}