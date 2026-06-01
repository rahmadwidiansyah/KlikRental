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

        // 2. Buat query dasar: Ambil semua status mobil agar badge 'Disewa' & 'Perawatan' bisa tampil di katalog frontend
        $query = Vehicle::query();

        // 3. Filter berdasarkan Kategori (Type)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // 4. Filter berdasarkan Kelas (Class) 
        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('class', $request->class);
        }

        // 5. LAPIS 1: Filter berdasarkan Ketersediaan Tanggal 
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);

            $startMinus2Days = (clone $start_date)->subDays(2);

            $bookedVehicleIds = Booking::whereNotIn('status', ['cancelled'])
                ->where(function ($q) use ($startMinus2Days, $end_date) {
                    $q->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $startMinus2Days);
                })
                ->pluck('vehicle_id');

            $query->whereNotIn('id', $bookedVehicleIds);
        }

        // 6. LAPIS 2: Jika user HANYA MELIHAT KATALOG (Tanpa Filter Tanggal), beri tanda disewa HARI INI
        if (!$request->filled('start_date')) {
            $todayMinus2Days = now()->subDays(2)->startOfDay();

            $todayBookedIds = Booking::whereNotIn('status', ['cancelled', 'returned'])
                ->where('start_date', '<=', now()->endOfDay())
                ->where('end_date', '>=', $todayMinus2Days)
                ->pluck('vehicle_id')
                ->toArray();
            
            $allVehicles = $query->with('primaryImage')->get();
            foreach ($allVehicles as $car) {
                $car->is_booked_today = in_array($car->id, $todayBookedIds);
            }

            $maintenanceVehicleIds = Vehicle::where('status', 'maintenance')->pluck('id')->toArray();
            foreach ($allVehicles as $car) {
                $car->is_maintenance = in_array($car->id, $maintenanceVehicleIds);
            }
        } else {
            $allVehicles = $query->with('primaryImage')->get();
        }

        // 7. Kelompokkan kendaraan berdasarkan kolom 'class'
        $groupedVehicles = $allVehicles->groupBy('class');

        // 8. Ambil data review untuk slider marquee
        $reviews = Review::with(['user', 'booking.vehicle'])
            ->latest()
            ->take(10)
            ->get();

        // 9. Data Statistik untuk Section Keunggulan Layanan
        $totalVehicles = Vehicle::count();
        $totalBookings = Booking::whereNotIn('status', ['cancelled', 'pending'])->count(); 
        $totalCustomers = Booking::distinct()->count('user_id'); 

        return view('dashboard', compact('groupedVehicles', 'zones', 'reviews', 'totalVehicles', 'totalBookings', 'totalCustomers'));
    }

    public function indexVehicle(Request $request)
    {
        $zones = Zone::where('is_active', true)->get();
        $query = Vehicle::with('primaryImage');

        if ($request->filled('class') && $request->class !== 'all') {
            $query->where('class', $request->class);
        }

        $vehicles = $query->paginate(12);
        return view('vehicle.index', compact('vehicles', 'zones'));
    }

    public function show($id)
    {
        $vehicle = Vehicle::with(['images', 'primaryImage', 'reviews.user'])->findOrFail($id);
        return view('vehicle.show', compact('vehicle'));
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    // --- 🚨 PERBAIKAN DI SINI 🚨 ---
    // Sekarang pengunjung yang belum login (Guest) akan mendapatkan data yang SAMA PERSIS dengan user login
    public function welcome(Request $request)
    {
        return $this->index($request);
    }
}