<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::withCount('bookings')
            ->withAvg('reviews', 'driver_rating')
            ->orderByRaw("FIELD(status, 'available', 'on_duty', 'inactive')")
            ->get();

        return view('driver.index', compact('drivers'));
    }

    // TAMBAHKAN FUNGSI INI
    public function show($id)
    {
        // Ambil data driver beserta hitungan order, rata-rata rating, dan list ulasan
        $driver = Driver::withCount('bookings')
            ->withAvg('reviews', 'driver_rating')
            ->with(['reviews' => function($query) {
                // Hanya ambil ulasan yang ngasih rating ke supir, urutkan dari terbaru
                $query->whereNotNull('driver_rating')->with('user')->latest();
            }])
            ->findOrFail($id);

        return view('driver.show', compact('driver'));
    }
}