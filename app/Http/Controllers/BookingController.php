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
        $zones = Zone::all();
        $drivers = Driver::where('status', 'available')->get();

        return view('booking.create', compact('vehicle', 'zones', 'drivers'));
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

        // 2. Hitung Durasi (Pembulatan ke atas, misal 26 jam dihitung 2 hari)
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $durationHours = $start->diffInHours($end);
        $durationDays = ceil($durationHours / 24) ?: 1; // Minimal 1 hari sewa

        // 3. Ambil Data Master dari Database
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $pickupZone = Zone::findOrFail($request->pickup_zone_id);
        $dropoffZone = Zone::findOrFail($request->dropoff_zone_id);

        $driverRate = 0;
        if ($request->driver_id) {
            $driver = Driver::findOrFail($request->driver_id);
            $driverRate = $driver->daily_rate;
        }

        // 4. Terapkan Rumus Kalkulasi Harga Dasar
        $basePrice = ($vehicle->price_per_day + $driverRate) * $durationDays;
        $totalPrice = $basePrice + $pickupZone->additional_cost + $dropoffZone->additional_cost;

        // 5. Cek & Terapkan Promo (Jika ada)
        $promoId = null;
        if ($request->promo_code) {
            $promo = Promo::where('code', $request->promo_code)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
                // Hitung diskon persentase
                $discount = ($promo->discount_percentage / 100) * $totalPrice;

                // Batasi diskon jika melebihi max_discount
                if ($discount > $promo->max_discount) {
                    $discount = $promo->max_discount;
                }

                $totalPrice -= $discount;
                $promoId = $promo->id;
            }
        }

        // 6. Generate Kode Booking Unik (Contoh: KR-AB12CD-171500000)
        $bookingCode = 'KR-' . strtoupper(Str::random(6)) . '-' . time();

        // 7. Simpan ke Database
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
            'total_price' => $totalPrice,
            'status' => 'pending' // Status awal pending, nunggu Midtrans
        ]);

        // 8. Redirect ke halaman riwayat pesanan
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

            // PECAH KALKULASI MOBIL DAN SUPIR
            $vehicleCost = $vehicle->price_per_day * $durationDays;
            $driverCost = $driverRate * $durationDays; // Hitung supir per hari

            $pickupCost = $pickupZone->additional_cost;
            $dropoffCost = $dropoffZone->additional_cost;

            // Total Keseluruhan
            $totalPrice = $vehicleCost + $driverCost + $pickupCost + $dropoffCost;

            // Logic Promo
            $promoValid = false;
            $promoMessage = '';
            $promoDiscount = 0;
            $promoPercentage = 0;

            if ($request->promo_code) {
                $promo = Promo::where('code', $request->promo_code)->where('valid_until', '>=', now())->first();
                if ($promo) {
                    $promoValid = true;
                    $promoPercentage = $promo->discount_percentage;

                    $discount = ($promoPercentage / 100) * $totalPrice;
                    $promoDiscount = $discount > $promo->max_discount ? $promo->max_discount : $discount;

                    $totalPrice -= $promoDiscount;
                    $promoMessage = "Yey! Promo {$promoPercentage}% berhasil diterapkan.";
                } else {
                    $promoMessage = 'Yah, kode promo tidak valid atau sudah kadaluarsa.';
                }
            }

            return response()->json([
                'status' => 'success',
                'duration_days' => $durationDays,
                'vehicle_cost' => 'Rp ' . number_format($vehicleCost, 0, ',', '.'), // Harga Mobil Saja
                'driver_cost' => 'Rp ' . number_format($driverCost, 0, ',', '.'),   // Harga Supir Saja
                'pickup_cost' => 'Rp ' . number_format($pickupCost, 0, ',', '.'),
                'dropoff_cost' => 'Rp ' . number_format($dropoffCost, 0, ',', '.'),
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
                'promo_valid' => $promoValid,
                'promo_percentage' => $promoPercentage,
                'promo_discount' => '- Rp ' . number_format($promoDiscount, 0, ',', '.'),
                'promo_message' => $promoMessage
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
