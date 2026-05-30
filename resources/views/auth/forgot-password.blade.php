<x-guest-layout>
    <div class="mb-4 text-sm text-on-surface-variant font-inter">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="email" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>