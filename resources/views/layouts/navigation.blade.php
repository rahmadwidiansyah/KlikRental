<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 10)"
     :class="scrolled ? 'bg-surface/95 shadow-md py-1' : 'bg-surface/80 py-2'"
     class="fixed top-0 w-full z-50 backdrop-blur-xl border-b border-outline-variant/30 transition-all duration-300">
     
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14"> <!-- Height navbar di perkecil ke h-14 -->
            
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="shrink-0">
                    <span class="font-montserrat text-xl font-bold text-primary">KlikRental</span>
                </a>

                <div class="hidden lg:flex items-center space-x-5">
                    <a href="{{ route('dashboard') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('dashboard') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary' }} transition-colors py-1">
                        Katalog Armada
                    </a>
                    <a href="{{ route('booking.index') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('booking.index') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary' }} transition-colors py-1">
                        Riwayat Pesanan
                    </a>
                    <a href="#" class="nav-link-hover font-inter font-semibold text-[13px] text-on-surface-variant hover:text-primary transition-colors py-1">
                        Driver Kami
                    </a>
                    <a href="#" class="nav-link-hover font-inter font-semibold text-[13px] text-on-surface-variant hover:text-primary transition-colors py-1">
                        Tentang Perusahaan
                    </a>
                    <a href="#" class="nav-link-hover font-inter font-semibold text-[13px] text-on-surface-variant hover:text-primary transition-colors py-1">
                        Layanan CS
                    </a>
                </div>
            </div>

            <div class="hidden lg:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-primary/20 rounded-lg text-[13px] font-semibold font-inter text-primary bg-primary/5 hover:bg-primary/10 transition ease-in-out duration-150">
                            <div>{{ explode(' ', Auth::user()->name)[0] }}</div>
                            <div class="ms-1">
                                <span class="material-symbols-outlined text-[18px]">expand_more</span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-inter text-[13px]">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="font-inter text-[13px] text-error">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="text-primary hover:bg-primary/5 p-1.5 rounded-md transition-colors">
                    <span class="material-symbols-outlined text-2xl" x-text="open ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div x-show="open" class="lg:hidden bg-surface border-t border-outline-variant/30 shadow-lg absolute w-full left-0">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-inter text-[14px] font-semibold">Katalog Armada</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking.index')" :active="request()->routeIs('booking.index')" class="font-inter text-[14px] font-semibold">Riwayat Pesanan</x-responsive-nav-link>
            <x-responsive-nav-link href="#" class="font-inter text-[14px] font-semibold">Driver Kami</x-responsive-nav-link>
            <x-responsive-nav-link href="#" class="font-inter text-[14px] font-semibold">Tentang Perusahaan</x-responsive-nav-link>
            <x-responsive-nav-link href="#" class="font-inter text-[14px] font-semibold">Layanan CS</x-responsive-nav-link>
        </div>
        <div class="pt-3 pb-3 border-t border-outline-variant/30 bg-surface-container">
            <div class="px-4">
                <div class="font-bold text-sm text-on-surface font-montserrat">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-on-surface-variant font-inter">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-inter text-[14px]">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="font-inter text-[14px] text-error">Log Out</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>