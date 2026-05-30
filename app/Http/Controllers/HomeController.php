<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Booking;
use App\Models\Review;
use Carbon\Carbon;

class HomeController extends Controller
{
    // Method untuk halaman Dashboard Customer utama (menggunakan pengelompokan kelas)
    public function index(Request $request)
    {
        // 1. Ambil data zona/lokasi yang aktif dari database
        $zones = Zone::where('is_active', true)->get();

        // 2. Buat query dasar: (PERBAIKAN) Ambil semua status mobil agar badge 'Disewa' & 'Perawatan' bisa tampil di katalog frontend
        $query = Vehicle::query();

        // 3. Filter berdasarkan Kategori (Type)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // 4. Filter berdasarkan Kelas (Class) - Tambahan Baru
        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('class', $request->class);
        }

        // 5. LAPIS 1: Filter berdasarkan Ketersediaan Tanggal (Hanya jika start_date & end_date diisi)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);

            // Rumus Jeda H+2: Tanggal sewa request dikurangi 2 hari
            $startMinus2Days = (clone $start_date)->subDays(2);

            // Cari ID mobil yang SEDANG DISEWA ATAU DALAM MASA JEDA pada rentang tanggal tersebut
            $bookedVehicleIds = Booking::whereNotIn('status', ['cancelled'])
                ->where(function ($q) use ($startMinus2Days, $end_date) {
                    $q->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $startMinus2Days);
                })
                ->pluck('vehicle_id');

            // Kecualikan mobil-mobil yang sedang disewa tersebut dari hasil pencarian
            $query->whereNotIn('id', $bookedVehicleIds);
        }

        // Eksekusi query dengan relasi gambar utamanya
        $allVehicles = $query->with('primaryImage')->get();

        // 6. LAPIS 2: Jika user HANYA MELIHAT KATALOG (Tanpa Filter Tanggal), beri tanda disewa HARI INI
        if (!$request->filled('start_date')) {
            $todayMinus2Days = now()->subDays(2)->startOfDay();

            $todayBookedIds = Booking::whereNotIn('status', ['cancelled'])
                ->where('start_date', '<=', now()->endOfDay())
                ->where('end_date', '>=', $todayMinus2Days)
                ->pluck('vehicle_id')
                ->toArray();

            // Suntikkan flag 'is_booked_today' ke setiap object mobil
            foreach ($allVehicles as $car) {
                $car->is_booked_today = in_array($car->id, $todayBookedIds);
            }
        }

        // 7. Kelompokkan kendaraan berdasarkan kolom 'class'
        $groupedVehicles = $allVehicles->groupBy('class');

        // 8. Ambil data review untuk slider marquee
        $reviews = Review::with(['user', 'booking.vehicle'])
            ->latest()
            ->take(10)
            ->get();

        // 9. TAMBAHAN BARU: Data Statistik untuk Section Keunggulan Layanan
        $totalVehicles = Vehicle::count();
        $totalBookings = Booking::whereNotIn('status', ['cancelled', 'pending'])->count(); // Hanya hitung pesanan yang sukses/jalan
        $totalCustomers = Booking::distinct('user_id')->count(); // Hitung jumlah pelanggan unik

        // Kirim semua variabel ke view dashboard
        return view('dashboard', compact('groupedVehicles', 'zones', 'reviews', 'totalVehicles', 'totalBookings', 'totalCustomers'));
    }

    // Method Baru untuk Halaman "Lihat Semua" (Katalog List Biasa dengan Paginasi)
    public function indexVehicle(Request $request)
    {
        $zones = Zone::where('is_active', true)->get();
        
        // (PERBAIKAN) Hapus filter status available agar selaras dengan dashboard
        $query = Vehicle::with('primaryImage');

        // Jika tombol "Lihat Semua" ditekan dari salah satu kelas, langsung filter kelasnya
        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('class', $request->class);
        }

        // Buat paginasi (misal 12 mobil per halaman) agar tidak berat jika datanya banyak
        $vehicles = $query->paginate(12);

        return view('vehicle.index', compact('vehicles', 'zones'));
    }

    // Method untuk Halaman Detail Kendaraan
    public function show($id)
    {
        // Ambil data mobil beserta semua relasi gambarnya dan review
        $vehicle = Vehicle::with(['images', 'primaryImage', 'reviews.user'])->findOrFail($id);

        return view('vehicle.show', compact('vehicle'));
    }

    // Method untuk halaman Admin
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    // Method bawaan yang sudah ada sebelumnya
    public function welcome()
    {
        // Ambil data kendaraan
        $vehicles = Vehicle::all();

        // Ambil data zones agar dropdown lokasi bisa terisi
        $zones = Zone::where('is_active', true)->get();
        
        return view('dashboard', compact('vehicles', 'zones'));
    }
}