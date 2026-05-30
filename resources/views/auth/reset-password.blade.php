<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="email" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="password" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>