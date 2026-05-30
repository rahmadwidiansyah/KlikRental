<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="font-montserrat text-2xl font-bold text-on-surface">Buat Password Baru</h2>
        <p class="font-inter text-sm text-on-surface-variant mt-2">
            Silakan masukkan password baru untuk akun Anda. Pastikan password mudah diingat namun aman.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="email" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password Baru')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="password" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                Simpan Password Baru
            </button>
        </div>
    </form>
</x-guest-layout>