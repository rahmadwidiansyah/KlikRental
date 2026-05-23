<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Zone;
use App\Models\Booking;

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

        // 4. Filter berdasarkan Ketersediaan Tanggal (Anti-Bentrok)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            // Cari ID mobil yang SEDANG DISEWA pada rentang tanggal tersebut
            $bookedVehicleIds = Booking::whereNotIn('status', ['cancelled']) // Abaikan yang dibatalkan
                ->where(function($q) use ($start_date, $end_date) {
                    // Logika Overlap: (Booking.Start <= Request.End) AND (Booking.End >= Request.Start)
                    $q->where('start_date', '<=', $end_date)
                      ->where('end_date', '>=', $start_date);
                })
                ->pluck('vehicle_id');

            // Kecualikan mobil-mobil yang sedang disewa tersebut dari hasil pencarian
            $query->whereNotIn('id', $bookedVehicleIds);
        }

        // Eksekusi query
        $vehicles = $query->get();
        
        // Kirim $vehicles dan $zones ke view dashboard
        return view('dashboard', compact('vehicles', 'zones'));
    }

    // Method untuk halaman Admin
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}