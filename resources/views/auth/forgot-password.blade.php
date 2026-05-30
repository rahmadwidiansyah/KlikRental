<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="font-montserrat text-2xl font-bold text-on-surface">Lupa Password?</h2>
        <p class="font-inter text-sm text-on-surface-variant mt-2 leading-relaxed">
            Tidak masalah. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="email" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                Kirim Tautan Reset Password
            </button>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-[13px] font-inter text-on-surface-variant">Ingat password Anda? <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a></p>
        </div>
    </form>
</x-guest-layout>