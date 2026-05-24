<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'daily_rate',
        'image_url', 
        'status'     
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    // Relasi untuk mengambil rating melalui booking
    public function reviews() {
        return $this->hasManyThrough(Review::class, Booking::class);
    }
}