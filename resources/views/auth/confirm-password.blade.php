<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="font-montserrat text-2xl font-bold text-on-surface">Keamanan Berlapis</h2>
        <p class="font-inter text-sm text-on-surface-variant mt-2">
            Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-inter font-semibold text-[13px]" />

            <x-text-input id="password" class="block mt-1 w-full bg-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                Konfirmasi
            </button>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('dashboard') }}" class="text-[13px] font-inter text-on-surface-variant hover:text-primary transition-colors">
                Kembali
            </a>
        </div>
    </form>
</x-guest-layout>