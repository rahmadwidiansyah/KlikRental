<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Vehicle $vehicle)
    {
  
        $zones = Zone::where('is_active', true)->get();
        $drivers = Driver::where('status', 'available')->get();

        return view('booking.create', compact('vehicle', 'zones', 'drivers'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_zone_id' => 'required|exists:zones,id',
            'dropoff_zone_id' => 'required|exists:zones,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'promo_code' => 'nullable|string'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $durationHours = $start->diffInHours($end);
        $durationDays = ceil($durationHours / 24) ?: 1; 
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $pickupZone = Zone::findOrFail($request->pickup_zone_id);
        $dropoffZone = Zone::findOrFail($request->dropoff_zone_id);

        $driverRate = 0;
        if ($request->driver_id) {
            $driver = Driver::findOrFail($request->driver_id);
            $driverRate = $driver->daily_rate;
        }

        $basePrice = ($vehicle->price_per_day + $driverRate) * $durationDays;
        $subtotal = $basePrice + $pickupZone->additional_cost + $dropoffZone->additional_cost;

        
        $promoId = null;
        $discountAmount = 0;

        if ($request->promo_code) {
            $promo = Promo::where('code', $request->promo_code)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
               
                $discountAmount = ($promo->discount_percentage / 100) * $subtotal;

              
                if ($discountAmount > $promo->max_discount) {
                    $discountAmount = $promo->max_discount;
                }

                $promoId = $promo->id;
            }
        }

        
        $priceAfterPromo = $subtotal - $discountAmount;
        $taxRate = 11; // PPN 11%
        $taxAmount = $priceAfterPromo * ($taxRate / 100);
        $totalPrice = $priceAfterPromo + $taxAmount;

        
        $bookingCode = 'KR-' . strtoupper(Str::random(6)) . '-' . time();

       
        Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'driver_id' => $request->driver_id,
            'pickup_zone_id' => $pickupZone->id,
            'dropoff_zone_id' => $dropoffZone->id,
            'promo_id' => $promoId,
            'start_date' => $start,
            'end_date' => $end,
            
           
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            
            'status' => 'pending' 
        ]);

        
        return redirect()->route('booking.index')->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function calculatePrice(Request $request)
    {
        if (!$request->start_date || !$request->end_date || !$request->pickup_zone_id || !$request->dropoff_zone_id) {
            return response()->json(['status' => 'incomplete']);
        }

        try {
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            if ($start->greaterThanOrEqualTo($end)) {
                return response()->json(['status' => 'error', 'message' => 'Tanggal kembali tidak valid.']);
            }

            $durationHours = $start->diffInHours($end);
            $durationDays = ceil($durationHours / 24) ?: 1;

            $vehicle = Vehicle::find($request->vehicle_id);
            $pickupZone = Zone::find($request->pickup_zone_id);
            $dropoffZone = Zone::find($request->dropoff_zone_id);
            $driverRate = $request->driver_id ? Driver::find($request->driver_id)->daily_rate : 0;

            
            $vehicleCost = $vehicle->price_per_day * $durationDays;
            $driverCost = $driverRate * $durationDays; 

            $pickupCost = $pickupZone->additional_cost;
            $dropoffCost = $dropoffZone->additional_cost;

            
            $subtotal = $vehicleCost + $driverCost + $pickupCost + $dropoffCost;

            
            $promoValid = false;
            $promoMessage = '';
            $promoDiscount = 0;
            $promoPercentage = 0;

            if ($request->promo_code) {
                $promo = Promo::where('code', $request->promo_code)->where('valid_until', '>=', now())->first();
                if ($promo) {
                    $promoValid = true;
                    $promoPercentage = $promo->discount_percentage;

                    $discount = ($promoPercentage / 100) * $subtotal;
                    $promoDiscount = $discount > $promo->max_discount ? $promo->max_discount : $discount;

                    $promoMessage = "Yey! Promo {$promoPercentage}% berhasil diterapkan.";
                } else {
                    $promoMessage = 'Yah, kode promo tidak valid atau sudah kadaluarsa.';
                }
            }

      
            $priceAfterPromo = $subtotal - $promoDiscount;
            $taxAmount = $priceAfterPromo * 0.11;
            
    
            $totalPrice = $priceAfterPromo + $taxAmount;

            return response()->json([
                'status' => 'success',
                'duration_days' => $durationDays,
                'vehicle_cost' => 'Rp ' . number_format($vehicleCost, 0, ',', '.'), // Harga Mobil Saja
                'driver_cost' => 'Rp ' . number_format($driverCost, 0, ',', '.'),   // Harga Supir Saja
                'pickup_cost' => 'Rp ' . number_format($pickupCost, 0, ',', '.'),
                'dropoff_cost' => 'Rp ' . number_format($dropoffCost, 0, ',', '.'),
                
                // DATA PROMO
                'promo_valid' => $promoValid,
                'promo_percentage' => $promoPercentage,
                'promo_discount' => '- Rp ' . number_format($promoDiscount, 0, ',', '.'),
                'promo_message' => $promoMessage,
                
                // DATA PAJAK (BARU)
                'tax_cost' => 'Rp ' . number_format($taxAmount, 0, ',', '.'),
                
                // TOTAL AKHIR
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format tanggal salah.']);
        }
    }

    public function show($bookingCode)
    {
        // Ambil data booking beserta relasinya (mobil, supir, zona, promo)
        $booking = Booking::with(['vehicle', 'driver', 'pickupZone', 'dropoffZone', 'promo'])
                          ->where('booking_code', $bookingCode)
                          ->where('user_id', Auth::id()) // Pastikan hanya user ybs yang bisa lihat
                          ->firstOrFail();

        return view('booking.show', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::with(['vehicle'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }
}