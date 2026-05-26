<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Logika upload foto KTP
        if ($request->hasFile('ktp_image')) {
            if ($user->ktp_image_url) {
                Storage::disk('public')->delete($user->ktp_image_url);
            }
            $data['ktp_image_url'] = $request->file('ktp_image')->store('identitas/ktp', 'public');
        }

        // Logika upload foto SIM
        if ($request->hasFile('sim_image')) {
            if ($user->sim_image_url) {
                Storage::disk('public')->delete($user->sim_image_url);
            }
            $data['sim_image_url'] = $request->file('sim_image')->store('identitas/sim', 'public');
        }

        // Logika upload Foto Profil (Avatar)
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada, dan pastikan itu bukan link dari Google
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Simpan file, lalu masukkan path-nya ke array key 'avatar' (sesuai database)
            $data['avatar'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}