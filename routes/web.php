<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\Zone;
use App\Models\TeamMember;
// =================================================================
// --- ROUTE PUBLIK (Bisa diakses oleh Guest tanpa perlu login) ---
// =================================================================

// Halaman utama dan katalog kendaraan
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Detail dan list katalog tambahan
Route::get('/vehicles', [HomeController::class, 'indexVehicle'])->name('vehicle.index');
Route::get('/kendaraan/{id}', [HomeController::class, 'show'])->name('vehicle.show');

// Informasi supir, CS, dan About
Route::get('/driver-kami', [DriverController::class, 'index'])->name('driver.index');
Route::get('/driver-kami/{id}', [DriverController::class, 'show'])->name('driver.show');

// <-- PERUBAHAN: Modifikasi rute CS untuk mengirim data kantor cabang -->
Route::get('/cs', function () { 
    $officeZones = Zone::where('is_office', true)->where('is_active', true)->get();
    return view('cs', compact('officeZones')); 
})->name('cs');

Route::get('/about', function () {$teamMembers = TeamMember::all(); return view('about', compact('teamMembers')); 
})->name('about');Route::get('/kebijakan-privasi', function () { return view('privacy'); })->name('privacy');
Route::get('/syarat-ketentuan', function () {return view('terms'); })->name('terms');

// Webhook Midtrans & Google OAuth
Route::post('/midtrans/callback', [MidtransController::class, 'handleNotification']);
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


// =================================================================
// --- ROUTE CUSTOMER (Wajib Login & Punya Role Customer) ---
// =================================================================
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Transaksi Booking
    Route::get('/booking/{vehicle}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking/calculate-price', [BookingController::class, 'calculatePrice'])->name('booking.calculatePrice');
    Route::get('/booking/{booking_code}/detail', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking_code}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    
    // Fitur Review (Wajib login untuk memberi ulasan)
    Route::post('/booking/{booking_code}/review', [BookingController::class, 'storeReview'])->name('booking.review');
});


// =================================================================
// --- ROUTE PROFILE (Wajib Login) ---
// =================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================================================================
// --- ROUTE AUTENTIKASI (Bawaan Breeze: Login, Register, Logout) ---
// =================================================================
require __DIR__ . '/auth.php';