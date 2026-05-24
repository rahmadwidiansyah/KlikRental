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
        
        $drivers = Driver::where('status', 'available')
            ->withCount('bookings')
            ->withAvg('reviews', 'rating')
            ->get();

        // MENGAMBIL JADWAL MOBIL YANG SUDAH TERBOOKING + JEDA H+2 HARI
        // Dikirim ke frontend agar Flatpickr otomatis memblokir tanggal tersebut
        $bookedRanges = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', ['cancelled'])
            ->where('end_date', '>=', now()->subDays(2)) // Ambil booking yang baru atau masa depan
            ->get()
            ->map(function ($booking) {
                return [
                    'from' => Carbon::parse($booking->start_date)->format('Y-m-d H:i'),
                    // Tambah 2 hari untuk masa jeda perawatan mobil sebelum bisa disewa lagi
                    'to' => Carbon::parse($booking->end_date)->addDays(2)->format('Y-m-d H:i') 
                ];
            });

        return view('booking.create', compact('vehicle', 'zones', 'drivers', 'bookedRanges'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
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

        // RUMUS JEDA H+2: Tanggal sewa baru dikurangi 2 hari untuk dicocokkan dengan sewa sebelumnya
        $startMinus2Days = (clone $start)->subDays(2);

        // 2. FINAL BACKEND LOCK (Mencegah Double Booking + Cek Masa Jeda H+2)
        $isOverlap = Booking::where('vehicle_id', $request->vehicle_id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function($q) use ($startMinus2Days, $end) {
                $q->where('start_date', '<=', $end)
                  ->where('end_date', '>=', $startMinus2Days);
            })->exists();

        if ($isOverlap) {
            return back()->with('error', 'Transaksi Ditolak: Mobil sedang disewa atau dalam masa perawatan (jeda H+2) oleh pengguna lain pada tanggal tersebut. Silakan pilih tanggal atau armada lain.');
        }

        // 3. Kalkulasi Harga
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
        
        // 4. Promo
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

        // 5. Pajak & Total
        $priceAfterPromo = $subtotal - $discountAmount;
        $taxRate = 11; // PPN 11%
        $taxAmount = $priceAfterPromo * ($taxRate / 100);
        $totalPrice = $priceAfterPromo + $taxAmount;

        $bookingCode = 'KR-' . strtoupper(Str::random(6)) . '-' . time();

        // 6. Simpan ke Database
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

            // RUMUS JEDA H+2: Dikurangi 2 hari untuk mengecek bentrok dengan masa istirahat sewa sebelumnya
            $startMinus2Days = (clone $start)->subDays(2);

            // LIVE CHECK DOUBLE BOOKING VIA AJAX (Termasuk Jeda H+2)
            $isOverlap = Booking::where('vehicle_id', $request->vehicle_id)
                ->whereNotIn('status', ['cancelled'])
                ->where(function($q) use ($startMinus2Days, $end) {
                    $q->where('start_date', '<=', $end)
                      ->where('end_date', '>=', $startMinus2Days);
                })->exists();

            if ($isOverlap) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Mohon maaf, mobil sudah dipesan atau sedang dalam masa perawatan (jeda H+2) pada tanggal tersebut.'
                ]);
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
                'vehicle_cost' => 'Rp ' . number_format($vehicleCost, 0, ',', '.'), 
                'driver_cost' => 'Rp ' . number_format($driverCost, 0, ',', '.'),   
                'pickup_cost' => 'Rp ' . number_format($pickupCost, 0, ',', '.'),
                'dropoff_cost' => 'Rp ' . number_format($dropoffCost, 0, ',', '.'),
                
                'promo_valid' => $promoValid,
                'promo_percentage' => $promoPercentage,
                'promo_discount' => '- Rp ' . number_format($promoDiscount, 0, ',', '.'),
                'promo_message' => $promoMessage,
                
                'tax_cost' => 'Rp ' . number_format($taxAmount, 0, ',', '.'),
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Format tanggal salah.']);
        }
    }

    public function show($bookingCode)
    {
        $booking = Booking::with(['vehicle', 'driver', 'pickupZone', 'dropoffZone', 'promo'])
                          ->where('booking_code', $bookingCode)
                          ->where('user_id', Auth::id()) 
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