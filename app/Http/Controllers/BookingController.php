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

        // 4. (BARU) Ambil Daftar Promo yang masih berlaku hari ini untuk Kupon UI
        $activePromos = Promo::where('valid_until', '>=', now())->get();

        return view('booking.create', compact('vehicle', 'zones', 'drivers', 'bookedRanges', 'activePromos'));
    }

    public function store(Request $request)
    {
        // PERUBAHAN: Tambahkan validasi phone_number (karena di-submit via form hidden)
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_zone_id' => 'required|exists:zones,id',
            'dropoff_zone_id' => 'required|exists:zones,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'promo_code' => 'nullable|string',
            'phone_number' => 'required|string|min:10|max:15'
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

        // PERUBAHAN: Jika phone_number user di DB kosong, update & simpan nomor WA ini secara permanen
        $user = Auth::user();
        if (empty($user->phone_number)) {
            $user->update(['phone_number' => $request->phone_number]);
        }

        try {
            $pricing = $this->calculateBookingPricing(
                $request->vehicle_id,
                $request->start_date,
                $request->end_date,
                $request->pickup_zone_id,
                $request->dropoff_zone_id,
                $request->driver_id,
                $request->promo_code
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $bookingCode = 'KR-' . strtoupper(Str::random(6)) . '-' . time();

        Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $user->id,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'pickup_zone_id' => $request->pickup_zone_id,
            'dropoff_zone_id' => $request->dropoff_zone_id,
            'promo_id' => $pricing['promo_id'],
            'start_date' => $pricing['start_date'],
            'end_date' => $pricing['end_date'],
            'subtotal' => $pricing['subtotal'],
            'tax_rate' => $pricing['tax_rate'],
            'tax_amount' => $pricing['tax_amount'],
            'total_price' => $pricing['total_price'],
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
            $pricing = $this->calculateBookingPricing(
                $request->vehicle_id,
                $request->start_date,
                $request->end_date,
                $request->pickup_zone_id,
                $request->dropoff_zone_id,
                $request->driver_id,
                $request->promo_code,
                true // check overlap
            );

            return response()->json([
                'status' => 'success',
                'duration_days' => $pricing['duration_days'],
                'vehicle_cost' => 'Rp ' . number_format($pricing['vehicle_cost'], 0, ',', '.'),
                'driver_cost' => 'Rp ' . number_format($pricing['driver_cost'], 0, ',', '.'),
                'pickup_cost' => 'Rp ' . number_format($pricing['pickup_cost'], 0, ',', '.'),
                'dropoff_cost' => 'Rp ' . number_format($pricing['dropoff_cost'], 0, ',', '.'),
                'promo_valid' => $pricing['promo_valid'],
                'promo_percentage' => $pricing['promo_percentage'],
                'promo_discount' => '- Rp ' . number_format($pricing['promo_discount'], 0, ',', '.'),
                'promo_message' => $pricing['promo_message'],
                'tax_cost' => 'Rp ' . number_format($pricing['tax_amount'], 0, ',', '.'),
                'total_price' => 'Rp ' . number_format($pricing['total_price'], 0, ',', '.')
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function calculateBookingPricing($vehicleId, $startDate, $endDate, $pickupZoneId, $dropoffZoneId, $driverId = null, $promoCode = null, $checkOverlap = false)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->greaterThanOrEqualTo($end)) {
            throw new \Exception('Tanggal kembali tidak valid.');
        }

        if ($checkOverlap) {
            $startMinus2Days = (clone $start)->subDays(2);
            $isOverlap = Booking::where('vehicle_id', $vehicleId)
                ->whereNotIn('status', ['cancelled'])
                ->where(function ($q) use ($startMinus2Days, $end) {
                    $q->where('start_date', '<=', $end)
                        ->where('end_date', '>=', $startMinus2Days);
                })->exists();

            if ($isOverlap) {
                throw new \Exception('Mohon maaf, mobil sudah dipesan.');
            }
        }

        $durationHours = $start->diffInHours($end);
        $durationDays = ceil($durationHours / 24) ?: 1;

        $vehicle = Vehicle::findOrFail($vehicleId);
        $pickupZone = Zone::findOrFail($pickupZoneId);
        $dropoffZone = Zone::findOrFail($dropoffZoneId);
        
        $driverRate = 0;
        if ($driverId) {
            $driver = Driver::find($driverId);
            if ($driver) {
                $driverRate = $driver->daily_rate;
            }
        }

        $vehicleCost = $vehicle->price_per_day * $durationDays;
        $driverCost = $driverRate * $durationDays;
        $pickupCost = $pickupZone->additional_cost;
        $dropoffCost = $dropoffZone->additional_cost;

        $subtotal = $vehicleCost + $driverCost + $pickupCost + $dropoffCost;

        $promoId = null;
        $promoDiscount = 0;
        $promoPercentage = 0;
        $promoValid = false;
        $promoMessage = '';

        if ($promoCode) {
            $promo = Promo::where('code', $promoCode)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
                $promoValid = true;
                $promoId = $promo->id;
                $promoPercentage = $promo->discount_percentage;
                $promoDiscount = ($promoPercentage / 100) * $subtotal;
                if ($promoDiscount > $promo->max_discount) {
                    $promoDiscount = $promo->max_discount;
                }
            } else {
                $promoMessage = 'Kode promo tidak valid atau sudah kadaluarsa.';
            }
        }

        $priceAfterPromo = $subtotal - $promoDiscount;
        $taxRate = 11;
        $taxAmount = $priceAfterPromo * ($taxRate / 100);
        $totalPrice = $priceAfterPromo + $taxAmount;

        return [
            'start_date' => $start,
            'end_date' => $end,
            'duration_days' => $durationDays,
            'vehicle' => $vehicle,
            'vehicle_cost' => $vehicleCost,
            'driver_cost' => $driverCost,
            'pickup_cost' => $pickupCost,
            'dropoff_cost' => $dropoffCost,
            'subtotal' => $subtotal,
            'promo_id' => $promoId,
            'promo_valid' => $promoValid,
            'promo_percentage' => $promoPercentage,
            'promo_discount' => $promoDiscount,
            'promo_message' => $promoMessage,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
        ];
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
                    'phone' => $booking->user->phone_number ?? '081234567890', 
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

    public function storeReview(Request $request, $bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

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