<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar; // <-- 1. Tambahkan import HasAvatar
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'phone_number',
    'nik',
    'address',
    'ktp_image_url',
    'sim_image_url',
    'role',
    'google_id',
    'avatar' 
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar // <-- 2. Implementasikan HasAvatar
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    // LOGIKA AVATAR PINTAR (Bawaanmu)
    public function getDisplayPictureAttribute()
    {
        // Cek apakah ada avatar (entah dari upload manual atau dari Google)
        if (!empty($this->avatar)) {
            // Jika link avatar dari Google (mengandung http), langsung return
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // Jika upload manual (biasanya path file storage), tambahkan asset()
            return asset('storage/' . $this->avatar);
        }

        // Kalau kosong semua, tampilkan inisial nama
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    // <-- 3. TAMBAHAN METHOD UNTUK FILAMENT -->
    public function getFilamentAvatarUrl(): ?string
    {
        // Panggil logika cerdas yang sudah kamu buat di atas
        return $this->display_picture;
    }
}