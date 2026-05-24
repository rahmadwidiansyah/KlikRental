<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MidtransController;

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

// --- ROUTE CUSTOMER ---
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/booking/{vehicle}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking/calculate-price', [BookingController::class, 'calculatePrice'])->name('booking.calculatePrice');
    Route::get('/booking/{booking_code}/detail', [BookingController::class, 'show'])->name('booking.show');
    });

Route::get('/kendaraan/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('vehicle.show');

Route::post('/midtrans/callback', [MidtransController::class, 'handleNotification']);
// --- ROUTE PROFILE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ROUTE AUTENTIKASI (Login, Register, Logout) ---
require __DIR__.'/auth.php';