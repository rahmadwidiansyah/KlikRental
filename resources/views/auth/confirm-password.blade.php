<x-guest-layout>
    <div class="mb-4 text-sm text-on-surface-variant font-inter">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" class="font-inter font-semibold text-[13px] text-on-surface" />
            <x-text-input id="password" class="block mt-1 w-full bg-surface text-on-surface border-outline-variant/60 rounded-xl focus:border-primary focus:ring-primary/20"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-primary text-[#FFFFFF] font-montserrat font-bold text-[14px] px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-all active:scale-95 shadow-lg shadow-primary/20 border border-transparent">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>