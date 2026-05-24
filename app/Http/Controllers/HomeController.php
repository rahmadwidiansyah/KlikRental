<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Booking;
use Carbon\Carbon; // Pastikan Carbon di-import

class HomeController extends Controller
{
    // Method untuk halaman Customer (Katalog Mobil)
    public function index(Request $request)
    {
        // 1. Ambil data zona/lokasi yang aktif dari database
        $zones = Zone::where('is_active', true)->get();

        // 2. Buat query dasar: hanya mobil yang secara fisik berstatus 'available'
        $query = Vehicle::where('status', 'available');

        // 3. Filter berdasarkan Kategori (Type)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // 4. LAPIS 1: Filter berdasarkan Ketersediaan Tanggal (Anti-Bentrok + Jeda H+2)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);
            
            // Rumus Jeda H+2: Tanggal sewa request dikurangi 2 hari 
            // agar mobil yang baru selesai tidak langsung bisa disewa
            $startMinus2Days = (clone $start_date)->subDays(2);

            // Cari ID mobil yang SEDANG DISEWA ATAU DALAM MASA JEDA pada rentang tanggal tersebut
            $bookedVehicleIds = Booking::whereNotIn('status', ['cancelled']) // Abaikan yang dibatalkan
                ->where(function ($q) use ($startMinus2Days, $end_date) {
                    $q->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $startMinus2Days);
                })
                ->pluck('vehicle_id');

            // Kecualikan mobil-mobil yang sedang disewa tersebut dari hasil pencarian
            $query->whereNotIn('id', $bookedVehicleIds);
        }

        // Eksekusi query
        $vehicles = $query->get();

        // 5. LAPIS 2: Jika user HANYA MELIHAT KATALOG (Tanpa Filter Tanggal), beri tanda jika disewa HARI INI atau MASIH DALAM JEDA
        if (!$request->filled('start_date')) {
            // Mundurkan 2 hari untuk mengecek mobil yang masih masa istirahat/jeda
            $todayMinus2Days = now()->subDays(2)->startOfDay();
            
            $todayBookedIds = Booking::whereNotIn('status', ['cancelled'])
                ->where('start_date', '<=', now()->endOfDay())
                ->where('end_date', '>=', $todayMinus2Days)
                ->pluck('vehicle_id')
                ->toArray();

            // Suntikkan flag 'is_booked_today' ke setiap object mobil
            foreach ($vehicles as $car) {
                $car->is_booked_today = in_array($car->id, $todayBookedIds);
            }
        }

        // Kirim $vehicles dan $zones ke view dashboard
        return view('dashboard', compact('vehicles', 'zones'));
    }
    // Method untuk Halaman Detail Kendaraan
    public function show($id)
    {
        // Ambil data mobil beserta semua relasi gambarnya
        $vehicle = Vehicle::with(['images', 'primaryImage'])->findOrFail($id);
        
        return view('vehicle.show', compact('vehicle'));
    }
    // Method untuk halaman Admin
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}