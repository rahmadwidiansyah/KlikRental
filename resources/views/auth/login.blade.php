<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-on-surface font-montserrat">Masuk ke KlikRental</h2>
        <p class="text-sm text-on-surface-variant mt-1 font-inter">Lanjutkan petualangan berkendara Anda.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 text-red-600 border border-red-200 text-sm p-3 rounded-xl text-center font-inter dark:bg-red-900/20 dark:border-red-800/50 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-surface border border-outline-variant/60 rounded-xl px-4 py-3 text-[14px] font-bold text-on-surface hover:bg-surface-variant transition-all active:scale-95 shadow-sm font-montserrat">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Lanjutkan dengan Google
        </a>
    </div>

    <div class="relative flex items-center justify-center mb-6">
        <div class="border-t border-outline-variant/40 w-full absolute"></div>
        <span class="bg-surface px-3 text-[11px] font-bold text-on-surface-variant relative z-10 uppercase tracking-wider font-inter">Atau dengan Email</span>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-[13px] font-inter text-on-surface" />
            <x-text-input id="email" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-[13px] font-inter text-on-surface" />
            <x-text-input id="password" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4 font-inter">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-outline-variant/60 text-primary bg-surface shadow-sm focus:ring-primary/50" name="remember">
                <span class="ms-2 text-[13px] text-on-surface-variant">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[13px] text-primary font-semibold hover:underline" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-primary text-[#FFFFFF] font-bold text-[14px] py-2.5 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent flex items-center justify-center font-inter">
                Masuk Sekarang
            </button>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-[13px] text-on-surface-variant font-inter">Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar di sini</a></p>
        </div>
    </form>
</x-guest-layout>