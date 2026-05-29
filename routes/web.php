<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\GoogleController;

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

Route::get('/vehicles', [App\Http\Controllers\HomeController::class, 'indexVehicle'])->name('vehicle.index');
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/driver-kami', [App\Http\Controllers\DriverController::class, 'index'])->name('driver.index');
Route::get('/driver-kami/{id}', [App\Http\Controllers\DriverController::class, 'show'])->name('driver.show');
Route::get('/kendaraan/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('vehicle.show');
Route::post('/booking/{booking_code}/review', [App\Http\Controllers\BookingController::class, 'storeReview'])->name('booking.review');
Route::post('/midtrans/callback', [MidtransController::class, 'handleNotification']);
// --- ROUTE PROFILE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ROUTE AUTENTIKASI (Login, Register, Logout) ---
require __DIR__.'/auth.php';