<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User (Customer)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kendaraan
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    // Relasi ke Supir (Opsional)
    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    // Relasi ke Zona (Jemput & Kembali)
    public function pickupZone() {
        return $this->belongsTo(Zone::class, 'pickup_zone_id');
    }

    public function dropoffZone() {
        return $this->belongsTo(Zone::class, 'dropoff_zone_id');
    }

    // Relasi ke Promo
    public function promo() {
        return $this->belongsTo(Promo::class);
    }

    // Relasi ke Payment
    public function payment() {
        return $this->hasOne(Payment::class);
    }

    // Relasi ke Review
    public function review() {
        return $this->hasOne(Review::class);
    }
}
