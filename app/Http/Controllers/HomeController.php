<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class HomeController extends Controller
{
    // Method untuk halaman Customer (Katalog Mobil)
    public function index()
    {
        // Ambil semua mobil yang statusnya 'available'
        $vehicles = Vehicle::where('status', 'available')->get();
        
        return view('dashboard', compact('vehicles'));
    }

    // Method untuk halaman Admin
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
}