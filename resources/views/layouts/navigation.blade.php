<nav x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 10)"
    :class="scrolled ? 'bg-surface/95 shadow-md py-1' : 'bg-surface/80 py-2'"
    class="fixed top-0 w-full z-50 backdrop-blur-xl border-b border-outline-variant/30 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14"> <!-- Height navbar h-14 -->

            <!-- Kiri: Logo & Navigasi Utama -->
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
                    <a href="{{ route('driver.index') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('driver.index') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary' }} transition-colors py-1">
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

            <!-- Kanan: Menu Autentikasi Desktop -->
            <div class="hidden lg:flex items-center">
                @auth
                <!-- Tampilan Desktop untuk yang SUDAH LOGIN -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-primary/20 rounded-lg text-[13px] font-semibold font-inter text-primary bg-primary/5 hover:bg-primary/10 transition ease-in-out duration-150">
                            <div>{{ explode(' ', Auth::user()?->name ?? 'Guest')[0] }}</div>
                            <div class="ms-1 flex items-center">
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
                @else
                <!-- Tampilan Desktop untuk yang BELUM LOGIN (GUEST) -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-[13px] font-semibold font-inter text-on-surface-variant hover:text-primary transition-colors py-1.5">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-[13px] font-semibold font-inter text-primary border border-primary px-3 py-1.5 rounded-lg hover:bg-primary hover:text-white transition-all duration-200">
                        Register
                    </a>
                </div>
                @endauth
            </div>

            <!-- Tombol Menu Mobile Burger -->
            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="text-primary hover:bg-primary/5 p-1.5 rounded-md transition-colors">
                    <span class="material-symbols-outlined text-2xl" x-text="open ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="lg:hidden bg-surface border-t border-outline-variant/30 shadow-lg absolute w-full left-0">

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-inter text-[14px] font-semibold">Katalog Armada</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('booking.index')" :active="request()->routeIs('booking.index')" class="font-inter text-[14px] font-semibold">Riwayat Pesanan</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('driver.index')" :active="request()->routeIs('driver.index')" class="font-inter text-[14px] font-semibold">Driver Kami</x-responsive-nav-link>
            <x-responsive-nav-link href="#" class="font-inter text-[14px] font-semibold">Tentang Perusahaan</x-responsive-nav-link>
            <x-responsive-nav-link href="#" class="font-inter text-[14px] font-semibold">Layanan CS</x-responsive-nav-link>
        </div>

        @auth
        <!-- Tampilan Mobile untuk yang SUDAH LOGIN -->
        <div class="pt-3 pb-3 border-t border-outline-variant/30 bg-surface-container">
            <div class="px-4">
                <div class="font-bold text-sm text-on-surface font-montserrat">{{ Auth::user()?->name ?? 'User' }}</div>
                <div class="font-medium text-xs text-on-surface-variant font-inter">{{ Auth::user()?->email ?? 'Email' }}</div>
            </div>
            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-inter text-[14px]">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="font-inter text-[14px] text-error">Log Out</x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <!-- Tampilan Mobile untuk yang BELUM LOGIN (GUEST) -->
        <div class="pt-4 pb-4 border-t border-outline-variant/30 px-4 flex gap-3">
            <a href="{{ route('login') }}" class="flex-1 text-center font-inter text-[14px] font-semibold text-on-surface-variant hover:text-primary transition py-2 border border-outline-variant/50 rounded-lg">
                Login
            </a>
            <a href="{{ route('register') }}" class="flex-1 text-center font-inter text-[14px] font-semibold text-white bg-primary hover:bg-primary/90 transition py-2 rounded-lg">
                Register
            </a>
        </div>
        @endauth
    </div>
</nav>