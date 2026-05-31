<nav x-data="{ 
        scrolled: false,
        isDark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            }
        }
    }"
    @scroll.window="scrolled = (window.pageYOffset > 10)"
    :class="scrolled ? 'bg-surface/95 dark:bg-surface/95 shadow-md dark:shadow-black/50 py-1' : 'bg-surface/80 dark:bg-surface/90 py-2'"
    class="fixed top-0 w-full z-50 backdrop-blur-xl border-b border-outline-variant/30 dark:border-outline-variant/50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">

            <!-- KIRI: Logo & Menu Utama -->
            <div class="flex items-center gap-8">
                <a href="{{ route('welcome') }}" class="shrink-0">
                    <span class="font-montserrat text-xl font-bold text-primary">KlikRental</span>
                </a>

                <div class="hidden lg:flex items-center space-x-5">
                    <a href="{{ route('dashboard') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('dashboard') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary' }} transition-colors py-1">
                        Katalog Armada
                    </a>
                    
                    @auth
                    <a href="{{ route('booking.index') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('booking.index') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary' }} transition-colors py-1">
                        Riwayat Pesanan
                    </a>
                    @endauth
                    
                    <a href="{{ route('driver.index') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('driver.index') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary' }} transition-colors py-1">
                        Driver Kami
                    </a>
                    
                    <a href="{{ route('about') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('about') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary' }} transition-colors py-1">
                        Tentang Perusahaan
                    </a>
                    <a href="{{ route('cs') }}" class="nav-link-hover font-inter font-semibold text-[13px] {{ request()->routeIs('cs') ? 'text-primary active' : 'text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary' }} transition-colors py-1">
                        Layanan CS
                    </a>
                </div>
            </div>

            <!-- KANAN: Tombol Tema & Profil (Berlaku untuk Desktop & Mobile) -->
            <div class="flex items-center gap-3 lg:gap-4">
                <!-- Tombol Toggle Tema -->
                <button @click="toggleTheme()" class="text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-white transition-colors p-1.5 rounded-full hover:bg-surface-container dark:hover:bg-outline-variant/30 focus:outline-none flex items-center justify-center border border-transparent hover:border-outline-variant/30 dark:hover:border-outline-variant/50">
                    <span class="material-symbols-outlined text-[20px] sm:text-[22px]" x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
                </button>

                @auth
                <!-- DROPDOWN PROFIL MODERN (Universal) -->
                <div x-data="{ profileOpen: false }" class="relative ml-1">
                    <!-- Trigger Button -->
                    <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" 
                            class="inline-flex items-center pl-1 sm:pl-2 pr-1 sm:pr-3 py-1.5 border border-transparent sm:border-primary/20 rounded-full text-[13px] font-semibold font-inter text-primary bg-transparent sm:bg-primary/5 hover:bg-primary/10 transition-all duration-200 gap-1 sm:gap-2 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/40"
                            :class="profileOpen ? 'bg-primary/10 border-primary/40 ring-2 ring-primary/20' : ''">
                        
                        <img src="{{ Auth::user()->display_picture }}" alt="Profile" class="h-8 w-8 sm:h-7 sm:w-7 rounded-full object-cover border border-primary/30 shadow-sm">
                        
                        <!-- Nama User disembunyikan di Mobile -->
                        <div class="hidden sm:block tracking-tight">{{ explode(' ', Auth::user()?->name ?? 'Guest')[0] }}</div>
                        
                        <!-- Panah Dropdown disembunyikan di Mobile -->
                        <div class="hidden sm:flex -ml-1 items-center transition-transform duration-300 ease-out" :class="profileOpen ? 'rotate-180' : ''">
                            <span class="material-symbols-outlined text-[18px]">expand_more</span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-3"
                         class="absolute right-0 mt-3 w-64 bg-surface border border-outline-variant/30 dark:border-outline-variant/50 rounded-2xl shadow-xl premium-shadow overflow-hidden z-50 origin-top-right"
                         style="display: none;">
                         
                         <!-- Dropdown Header (Info User) -->
                         <div class="px-4 py-4 border-b border-outline-variant/30 dark:border-outline-variant/50 bg-surface-container-lowest flex items-center gap-3">
                             <img src="{{ Auth::user()->display_picture }}" alt="Profile" class="h-11 w-11 rounded-full object-cover border border-outline-variant/50 shadow-sm">
                             <div class="overflow-hidden">
                                 <p class="text-[14px] font-bold text-on-surface font-montserrat truncate">{{ Auth::user()->name }}</p>
                                 <p class="text-[12px] font-medium text-on-surface-variant dark:text-gray-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                             </div>
                         </div>

                         <!-- Dropdown Body (Links) -->
                         <div class="p-2 space-y-1 bg-surface">
                             <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold font-inter text-on-surface-variant dark:text-gray-300 hover:bg-primary/10 hover:text-primary dark:hover:text-primary transition-all">
                                 <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                                 Pengaturan Akun
                             </a>
                             
                             <div class="h-px bg-outline-variant/30 dark:bg-outline-variant/50 my-1.5 mx-2"></div>
                             
                             <form method="POST" action="{{ route('logout') }}">
                                 @csrf
                                 <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold font-inter text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all text-left">
                                     <span class="material-symbols-outlined text-[20px]">logout</span>
                                     Keluar
                                 </button>
                             </form>
                         </div>
                    </div>
                </div>
                @else
                <!-- Desktop Auth -->
                <div class="hidden lg:flex items-center gap-4 ml-2 border-l border-outline-variant/30 dark:border-outline-variant/50 pl-4">
                    <a href="{{ route('login') }}" class="text-[13px] font-semibold font-inter text-on-surface-variant hover:text-primary dark:text-gray-200 dark:hover:text-primary transition-colors py-1.5">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-[13px] font-semibold font-inter text-primary border border-primary px-3 py-1.5 rounded-lg hover:bg-primary hover:text-white transition-all duration-200">
                        Register
                    </a>
                </div>
                <!-- Mobile Auth Icon -->
                <div class="flex lg:hidden items-center ml-1">
                    <a href="{{ route('login') }}" class="text-primary hover:bg-primary/10 p-1.5 rounded-full transition-colors flex items-center justify-center border border-primary/30">
                        <span class="material-symbols-outlined text-[20px]">login</span>
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- BOTTOM NAVIGATION BAR (KHUSUS MOBILE) -->
<div class="lg:hidden fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant/30 dark:border-outline-variant/50 shadow-[0_-4px_24px_rgba(0,0,0,0.06)] dark:shadow-[0_-4px_24px_rgba(0,0,0,0.3)] z-50 pb-safe transition-colors duration-300">
    <div class="flex justify-around items-center px-1 py-2">
        
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 w-16 p-1 transition-colors {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-primary' }}">
            <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('dashboard') ? 'icon-fill' : '' }}">directions_car</span>
            <span class="text-[10px] font-semibold font-inter">Katalog</span>
        </a>

        @auth
        <a href="{{ route('booking.index') }}" class="flex flex-col items-center gap-1 w-16 p-1 transition-colors {{ request()->routeIs('booking.index') ? 'text-primary' : 'text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-primary' }}">
            <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('booking.index') ? 'icon-fill' : '' }}">history</span>
            <span class="text-[10px] font-semibold font-inter">Riwayat</span>
        </a>
        @endauth

        <a href="{{ route('driver.index') }}" class="flex flex-col items-center gap-1 w-16 p-1 transition-colors {{ request()->routeIs('driver.index') ? 'text-primary' : 'text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-primary' }}">
            <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('driver.index') ? 'icon-fill' : '' }}">badge</span>
            <span class="text-[10px] font-semibold font-inter">Driver</span>
        </a>

        <a href="{{ route('about') }}" class="flex flex-col items-center gap-1 w-16 p-1 transition-colors {{ request()->routeIs('about') ? 'text-primary' : 'text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-primary' }}">
            <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('about') ? 'icon-fill' : '' }}">info</span>
            <span class="text-[10px] font-semibold font-inter">Tentang</span>
        </a>

        <a href="{{ route('cs') }}" class="flex flex-col items-center gap-1 w-16 p-1 transition-colors {{ request()->routeIs('cs') ? 'text-primary' : 'text-on-surface-variant hover:text-primary dark:text-gray-300 dark:hover:text-primary' }}">
            <span class="material-symbols-outlined text-[22px] {{ request()->routeIs('cs') ? 'icon-fill' : '' }}">support_agent</span>
            <span class="text-[10px] font-semibold font-inter">Bantuan</span>
        </a>

    </div>
</div>