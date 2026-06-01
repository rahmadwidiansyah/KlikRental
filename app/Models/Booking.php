<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Centralized Pricing Logic
     * Digunakan oleh Controller (Customer) dan nantinya oleh Filament (Admin)
     */
    public static function calculatePricing($data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        
        $durationHours = $start->diffInHours($end);
        $durationDays = ceil($durationHours / 24) ?: 1;

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $pickupZone = Zone::findOrFail($data['pickup_zone_id']);
        $dropoffZone = Zone::findOrFail($data['dropoff_zone_id']);
        
        $driverRate = 0;
        if (!empty($data['driver_id'])) {
            $driver = Driver::find($data['driver_id']);
            $driverRate = $driver ? $driver->daily_rate : 0;
        }

        $vehicleCost = $vehicle->price_per_day * $durationDays;
        $driverCost = $driverRate * $durationDays;
        $pickupCost = $pickupZone->additional_cost;
        $dropoffCost = $dropoffZone->additional_cost;

        $subtotal = $vehicleCost + $driverCost + $pickupCost + $dropoffCost;

        $promoDiscount = 0;
        if (!empty($data['promo_code'])) {
            $promo = Promo::where('code', $data['promo_code'])
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
                $promoDiscount = ($promo->discount_percentage / 100) * $subtotal;
                if ($promoDiscount > $promo->max_discount) {
                    $promoDiscount = $promo->max_discount;
                }
            }
        }

        $priceAfterPromo = $subtotal - $promoDiscount;
        $taxRate = 11;
        $taxAmount = $priceAfterPromo * ($taxRate / 100);
        $totalPrice = $priceAfterPromo + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            'duration_days' => $durationDays,
            'promo_discount' => $promoDiscount
        ];
    }

    /**
     * Accessor untuk mengecek apakah ini order di kantor (Roadmap)
     */
    public function getIsOfficeOrderAttribute(): bool
    {
        return $this->pickupZone?->is_office ?? false;
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