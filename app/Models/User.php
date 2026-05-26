<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
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
    'google_avatar',   // <-- Tambahan
    'profile_picture'  // <-- Tambahan
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
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

    // LOHIKA AVATAR PINTAR: Prioritas 1 = Foto Profil, Prioritas 2 = Google, Prioritas 3 = Default
    public function getDisplayPictureAttribute()
    {
        if (!empty($this->profile_picture)) {
            return asset('storage/' . $this->profile_picture);
        }
        
        if (!empty($this->google_avatar)) {
            return $this->google_avatar;
        }

        // Kalau kosong semua, tampilkan inisial nama
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }
}