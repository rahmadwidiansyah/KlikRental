<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika sudah ada, update id & avatar Google-nya
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // <-- SUDAH DIUBAH JADI 'avatar'
                ]);
            } else {
                // Jika belum, register otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // <-- SUDAH DIUBAH JADI 'avatar'
                    'password' => bcrypt(Str::random(16)), // Password acak karena login via Google
                    'role' => 'customer', 
                ]);
            }

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // TIPS: Jika nanti masih gagal, ubah baris return di bawah ini sementara menjadi:
            // dd($e->getMessage()); 
            // Untuk melihat pesan error aslinya.
            return redirect()->route('login')->with('error', 'Waduh, gagal login pakai Google. Coba lagi ya!');
        }
    }
}