<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Promo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        // 1. Pengaman Status Kendaraan
        if ($vehicle->status !== 'available') {
            return redirect()->route('vehicle.show', $vehicle->id)
                             ->with('error', 'Maaf, kendaraan ini sedang tidak tersedia untuk disewa saat ini.');
        }

        // 2. Ambil data zona dan supir yang tersedia (TIDAK DOBEL)
        $zones = Zone::where('is_active', true)->get();

        $drivers = Driver::where('status', 'available')
            ->withCount('bookings')
            ->withAvg('reviews', 'driver_rating')
            ->get();

        // 3. Ambil data booking untuk di-disable di kalender Flatpickr
        $bookedRanges = Booking::where('vehicle_id', $vehicle->id)
            ->whereNotIn('status', ['cancelled'])
            ->where('end_date', '>=', now()->subDays(2))
            ->get()
            ->map(function ($booking) {
                return [
                    'from' => Carbon::parse($booking->start_date)->format('Y-m-d H:i'),
                    'to' => Carbon::parse($booking->end_date)->addDays(2)->format('Y-m-d H:i')
                ];
            });

        return view('booking.create', compact('vehicle', 'zones', 'drivers', 'bookedRanges'));
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
        $startMinus2Days = (clone $start)->subDays(2);
        
        // --- 🚨 CEK BENTROK MOBIL (Dikeluarkan dari IF Supir) 🚨 ---
        $isOverlap = Booking::where('vehicle_id', $request->vehicle_id)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) use ($startMinus2Days, $end) {
                $q->where('start_date', '<=', $end)
                    ->where('end_date', '>=', $startMinus2Days);
            })->exists();

        if ($isOverlap) {
            return back()->with('error', 'Transaksi Ditolak: Mobil sedang disewa atau dalam masa perawatan pada tanggal tersebut.');
        }

        // --- CEK BENTROK SUPIR (Hanya jika pakai supir) ---
        if ($request->driver_id) {
            $isDriverOverlap = Booking::where('driver_id', $request->driver_id)
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $start);
                })->exists();

            if ($isDriverOverlap) {
                return back()->with('error', 'Transaksi Ditolak: Supir yang dipilih sudah ada jadwal pada tanggal tersebut.');
            }
        }

        // --- PENGAMAN STATUS REAL-TIME ---
        $vehicleCheck = Vehicle::findOrFail($request->vehicle_id);
        if ($vehicleCheck->status !== 'available') {
            return back()->with('error', 'Mohon maaf, kendaraan ini baru saja disewa oleh pelanggan lain atau sedang dalam perawatan.');
        }

        if ($request->driver_id) {
            $driverCheck = Driver::findOrFail($request->driver_id);
            if ($driverCheck->status !== 'available') {
                return back()->with('error', 'Mohon maaf, supir yang Anda pilih baru saja mendapat tugas lain.');
            }
        }

       
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
        $taxRate = 11;
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

        return redirect()->route('booking.show', $bookingCode)->with('success', 'Pemesanan berhasil dibuat!');
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

            $startMinus2Days = (clone $start)->subDays(2);

            $isOverlap = Booking::where('vehicle_id', $request->vehicle_id)
                ->whereNotIn('status', ['cancelled'])
                ->where(function ($q) use ($startMinus2Days, $end) {
                    $q->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $startMinus2Days);
                })->exists();

            if ($isOverlap) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mohon maaf, mobil sudah dipesan.'
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

                    $promoMessage = "Promo diterapkan.";
                } else {
                    $promoMessage = 'Kode promo tidak valid.';
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
        $booking = Booking::with(['vehicle', 'driver', 'pickupZone', 'dropoffZone', 'promo', 'user'])
            ->where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $snapToken = null;

        if ($booking->status === 'pending') {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);

            $params = [
                'transaction_details' => [
                    'order_id' => $booking->booking_code,
                    'gross_amount' => (int) ceil($booking->total_price),
                ],
                'customer_details' => [
                    'first_name' => $booking->user->name,
                    'email' => $booking->user->email,
                    'phone' => $booking->user->phone ?? '081234567890',
                ],
                'item_details' => [
                    [
                        'id' => $booking->vehicle_id,
                        'price' => (int) ceil($booking->total_price),
                        'quantity' => 1,
                        'name' => 'Sewa ' . $booking->vehicle->name,
                    ]
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
            }
        }

        return view('booking.show', compact('booking', 'snapToken'));
    }

    public function index()
    {
        $bookings = Booking::with(['vehicle'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }

    // Tambahkan use App\Models\Review; di bagian atas controller

    public function storeReview(Request $request, $bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi: Hanya pesanan completed yang belum di-review yang bisa dinilai
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Pesanan harus diselesaikan sebelum memberi ulasan.');
        }

        if ($booking->review()->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $request->validate([
            'vehicle_rating' => 'required|integer|min:1|max:5',
            'company_rating' => 'required|integer|min:1|max:5',
            'driver_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'vehicle_rating' => $request->vehicle_rating,
            'company_rating' => $request->company_rating,
            'driver_rating' => $request->driver_rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil disimpan.');
    }
}
