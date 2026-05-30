<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'booking_code',    
        'user_id',
        'vehicle_id',
        'driver_id',
        'pickup_zone_id',   
        'dropoff_zone_id',  
        'promo_id',
        'start_date',
        'end_date',
        'subtotal',        
        'tax_rate',        
        'tax_amount',       
        'total_price',
        'payment_status',
        'status'
    ];
    
    protected $guarded = ['id'];

  
    protected static function booted()
    {
        static::updated(function ($booking) {
            // Cek apakah admin baru saja mengubah nilai di kolom 'status'
            if ($booking->isDirty('status')) {
                $newStatus = $booking->status;

                // 1. Jika Booking diubah jadi IN_USE (Kunci diserahkan)
                if ($newStatus === 'in_use') {
                    if ($booking->vehicle_id) {
                        // ✨ UBAH KE 'rented' SESUAI MIGRATION KENDARAAN KAMU ✨
                        $booking->vehicle()->update(['status' => 'rented']); 
                    }
                    if ($booking->driver_id) {
                        $booking->driver()->update(['status' => 'on_duty']);
                    }
                } 
                // 2. Jika Booking diubah jadi COMPLETED atau CANCELLED (Sewa selesai/batal)
                elseif (in_array($newStatus, ['completed', 'cancelled'])) {
                    if ($booking->vehicle_id) {
                        $booking->vehicle()->update(['status' => 'available']);
                    }
                    if ($booking->driver_id) {
                        $booking->driver()->update(['status' => 'available']);
                    }
                }
            }
        });
    }

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