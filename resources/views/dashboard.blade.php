<x-app-layout>
    <!-- Hero Section Kepadatan Tinggi (Compact) -->
    <section class="relative min-h-[550px] flex items-center justify-center pt-10 pb-10 hero-bg" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC9bJYzj5fVYzxIjogSg18_3EooGnfWR6TPK5hFZrUb_131OSbfsKqpuH2_XgFKFoNFPKSkCpsALuRblJ-I9LHUTjNpUh5ih9JRA6d4ArWtVhNNrcBnarqhjiV_agGpHwD1-CSkoeVhwbpZiobo6iJ2Qz_AOYxEQ1SyvdNs0stirLJhEL9qMBPJjIGNDunUGnUX2T-EGxdRpa_4J1n7XOKdfT4pXCCQWeLKD3rZ_JuvZxY3126EaoZyvSj38Y7W1rlO_i7tyOjIvpa7');">
        <div class="absolute inset-0 gradient-overlay"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center mt-6">

            <h1 class="font-montserrat text-3xl md:text-[46px] font-bold text-white mb-4 drop-shadow-lg leading-tight">
                Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}!
            </h1>
            <p class="font-inter text-[15px] md:text-[17px] text-white/90 mb-8 max-w-2xl drop-shadow">
                Nikmati pengalaman sewa mobil premium dengan proses verifikasi instan, ketersediaan real-time, dan dukungan 24/7.
            </p>

            <!-- Search Bar Minimalis & Bersih -->
            <form action="{{ route('dashboard') }}" method="GET" class="glass-panel w-full max-w-4xl rounded-2xl p-4 md:p-5 shadow-lg border border-outline-variant/30 flex flex-col md:flex-row gap-3 items-end text-left">

                <!-- 1. Input Lokasi (Tanpa Biaya Tambahan) -->
                <div class="w-full">
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Lokasi Penjemputan</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">location_on</span>
                        <select name="zone_id" required class="w-full bg-none bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Lokasi...</option>
                            @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                            @endforeach
                        </select>
                        <!-- Panah dibuat flat (tidak ada bayangan) -->
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                    </div>
                </div>

                <!-- 2. Input Tanggal Ambil -->
                <div class="w-full">
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl Ambil</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">calendar_today</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all" />
                    </div>
                </div>

                <!-- 3. Input Tanggal Kembali -->
                <div class="w-full">
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl Kembali</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">event_busy</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all" />
                    </div>
                </div>

                <!-- 4. Input Kategori -->
                <div class="w-full">
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Kategori</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">directions_car</span>
                        <select name="type" class="w-full bg-surface bg-none border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all appearance-none cursor-pointer">
                            <option value="all">Semua</option>
                            <option value="SUV" {{ request('type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                            <option value="MPV" {{ request('type') == 'MPV' ? 'selected' : '' }}>MPV</option>
                            <option value="Sedan" {{ request('type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="Hatchback" {{ request('type') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="Minibus" {{ request('type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                        </select>
                        <!-- Panah flat -->
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full md:w-auto bg-primary text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all flex items-center justify-center gap-1.5 whitespace-nowrap active:scale-95 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                    Cari
                </button>
            </form>

            <!-- Trust Badges (Aksen Hijau Forest yang cerah untuk visibilitas di background gelap) -->
            <div class="flex flex-wrap justify-center gap-3 mt-8 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">check_circle</span>
                    Realtime Availability
                </div>
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">payments</span>
                    Pembayaran Digital
                </div>
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">verified_user</span>
                    Verifikasi Aman
                </div>
            </div>
        </div>
    </section>

    <!-- Grid Card Efisien & Compact -->
    <section class="py-10 bg-background" id="armada">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="font-montserrat text-[24px] font-bold text-on-surface leading-tight">Armada Premium Kami</h2>
                    <p class="font-inter text-[14px] text-on-surface-variant">Pilihan efisien untuk perjalanan Anda.</p>
                </div>
            </div>

            @if($vehicles->isEmpty())
            <div class="bg-error-container text-on-error-container p-4 rounded-xl font-inter flex items-center gap-3 border border-red-200">
                <span class="material-symbols-outlined text-xl">info</span>
                <p class="text-[14px]">Maaf, saat ini semua armada mobil sedang disewa.</p>
            </div>
            @else
            <!-- Menggunakan grid hingga 4 kolom (xl) agar tidak memakan ruang vertical -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($vehicles as $car)
                <div class="vehicle-card bg-surface border border-outline-variant/60 rounded-xl overflow-hidden transition-all duration-300 premium-shadow group relative flex flex-col">

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
                        <span class="bg-primary/10 text-primary border border-primary/20 px-2 py-0.5 rounded-full font-inter font-semibold text-[10px] flex items-center gap-1 backdrop-blur-md w-max">
                            <span class="material-symbols-outlined text-[14px] icon-fill">directions_car</span> {{ $car->type }}
                        </span>
                    </div>

                    <!-- Badge Hijau Forest untuk Ketersediaan -->
                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                        </span>
                    </div>

                    <!-- Gambar Diperkecil (h-44) -->
                    <div class="h-44 overflow-hidden bg-surface-container flex items-center justify-center p-4 relative">
                        <img src="{{ $car->primaryImage->image_url ?? 'https://placehold.co/400x250?text=Mobil' }}"
                            alt="{{ $car->name }}"
                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500 drop-shadow-md">
                    </div>

                    <!-- Konten Diperpadat (p-4) -->
                    <div class="p-4 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-montserrat text-[16px] font-bold text-on-surface mb-0.5 line-clamp-1">{{ $car->name }}</h3>
                                <p class="font-inter text-on-surface-variant text-[12px]">{{ $car->transmission }}</p>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <span class="font-inter text-[10px] text-on-surface-variant block leading-tight">Mulai dari</span>
                                <span class="font-montserrat text-[15px] font-bold text-primary">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between mb-4 pb-4 border-b border-outline-variant/30 mt-auto px-1">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]">group</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->seats }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]">luggage</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->luggage_capacity }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]">local_gas_station</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->fuel_type }}</span>
                            </div>
                        </div>

                        <a href="{{ route('booking.create', $car->id) }}" class="block w-full">
                            <!-- Aksen gradasi Ungu ke Biru -->
                            <button class="w-full bg-primary/10 text-primary font-inter font-bold text-[13px] py-2.5 rounded-lg group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary group-hover:text-white transition-all duration-300 flex items-center justify-center gap-1.5">
                                Sewa Sekarang
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Bento Grid Features (Tinggi dan padding di perkecil) -->
    <section class="py-10 bg-surface border-t border-outline-variant/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="font-montserrat text-[24px] md:text-[28px] font-bold text-on-surface mb-2">Fitur Rental Cerdas</h2>
                <p class="font-inter text-[14px] md:text-[15px] text-on-surface-variant max-w-2xl mx-auto">Kami mengintegrasikan teknologi terkini untuk memastikan pengalaman rental yang mulus, aman, dan tanpa kerumitan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 auto-rows-[200px]">
                <div class="md:col-span-2 md:row-span-2 bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/40 premium-shadow relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                            <!-- Ikon utama menggunakan Aksen Hijau Forest -->
                            <span class="material-symbols-outlined text-forest-green text-[20px]">verified</span>
                        </div>
                        <h3 class="font-montserrat text-[20px] font-bold text-on-surface mb-2">Verifikasi Aman & Instan</h3>
                        <p class="font-inter text-[14px] text-on-surface-variant max-w-md">Proses onboarding menggunakan e-KYC dan teknologi face recognition untuk keamanan maksimal dan persetujuan yang cepat.</p>

                        <div class="mt-auto flex gap-3 pt-4">
                            <div class="bg-surface px-3 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-forest-green text-[14px]">check_circle</span>
                                <span class="font-inter font-medium text-[11px]">KTP Terintegrasi</span>
                            </div>
                            <div class="bg-surface px-3 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-forest-green text-[14px]">face</span>
                                <span class="font-inter font-medium text-[11px]">Liveness Check</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 premium-shadow group hover:-translate-y-1 transition-transform flex flex-col justify-center">
                    <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mb-3 text-primary">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                    </div>
                    <h3 class="font-montserrat text-[15px] font-bold text-on-surface mb-1">Booking Real-time</h3>
                    <p class="font-inter text-[13px] text-on-surface-variant line-clamp-3">Sistem inventaris terhubung langsung. Apa yang Anda lihat adalah apa yang tersedia.</p>
                </div>

                <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 premium-shadow group hover:-translate-y-1 transition-transform flex flex-col justify-center">
                    <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mb-3 text-primary">
                        <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span>
                    </div>
                    <h3 class="font-montserrat text-[15px] font-bold text-on-surface mb-1">Pembayaran Digital</h3>
                    <p class="font-inter text-[13px] text-on-surface-variant line-clamp-3">Mendukung berbagai metode pembayaran QRIS & Transfer dengan konfirmasi instan.</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>