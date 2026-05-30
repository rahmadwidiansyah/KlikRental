<x-app-layout>
    <!-- HERO SECTION (Dioptimalkan ukurannya persis seperti Dashboard) -->
    <section class="relative bg-primary pt-8 pb-10 overflow-hidden">
        <!-- Abstract Background Ornaments -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            
            <!-- Judul (Ukuran font persis dashboard) -->
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                Mitra Pengemudi <span class="text-white/90">Terbaik</span>
            </h1>
            
            <!-- Deskripsi -->
            <p class="font-inter text-[14px] md:text-[15px] text-white/80 max-w-2xl mx-auto leading-relaxed mb-4">
                Perjalanan Anda adalah prioritas kami. Semua pengemudi KlikRental telah melewati seleksi ketat, tersertifikasi, dan berdedikasi untuk memberikan pelayanan yang aman, nyaman, dan ramah.
            </p>

            <!-- Badge (Dipindah ke bawah judul & deskripsi, style persis dashboard) -->
            <div class="flex flex-wrap justify-center gap-3 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">badge</span>
                    Tenaga Profesional Tersertifikasi
                </div>
            </div>

        </div>
    </section>

    <!-- LIST DRIVER SECTION (Overlap tipis -mt-6) -->
    <section class="relative -mt-6 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($drivers as $driver)
            <a href="{{ route('driver.show', $driver->id) }}" class="bg-surface border border-outline-variant/40 rounded-2xl p-5 text-center premium-shadow group hover:border-primary/50 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 relative flex flex-col block cursor-pointer">
                
                <!-- BADGE STATUS -->
                <div class="absolute top-4 right-4 z-10">
                    @if($driver->status === 'available')
                    <span class="bg-forest-light/90 text-forest-green border border-forest-green/20 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1.5 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                    </span>
                    @elseif($driver->status === 'on_duty')
                    <span class="bg-blue-50/90 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1.5 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Sedang Bertugas
                    </span>
                    @else
                    <span class="bg-red-50/90 text-red-600 border border-red-200 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1.5 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Tidak Aktif
                    </span>
                    @endif
                </div>

                <!-- FOTO PROFIL -->
                <div class="w-20 h-20 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4 mt-2 border-4 border-surface shadow-md relative group-hover:border-primary/20 transition-colors">
                    <img src="{{ $driver->image_url ? asset('storage/' . $driver->image_url) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=e4dfff&color=140067' }}"
                         alt="Foto {{ $driver->name }}"
                         class="w-full h-full object-cover rounded-full">
                    
                    <!-- Verified Badge Centang -->
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-sm border border-outline-variant/30">
                        <span class="material-symbols-outlined text-primary text-[14px] icon-fill">verified</span>
                    </div>
                </div>

                <!-- KONTEN INFO DRIVER -->
                <div class="flex-grow flex flex-col text-center">
                    <h3 class="font-montserrat text-[16px] font-bold text-on-surface mb-1 line-clamp-1 group-hover:text-primary transition-colors">{{ $driver->name }}</h3>

                    <!-- RATING & BOOKING COUNT -->
                    <div class="flex items-center justify-center gap-2.5 mt-1 mb-4">
                        <div class="flex items-center gap-1 bg-[#FFF8E7] text-[#B87503] px-1.5 py-0.5 rounded text-[10px] font-bold border border-[#B87503]/10">
                            <span class="material-symbols-outlined icon-fill text-[12px]">star</span>
                            {{ number_format($driver->reviews_avg_driver_rating ?? 5.0, 1) }}
                        </div>
                        <span class="text-outline-variant/40 text-[10px]">|</span>
                        <div class="flex items-center gap-1 text-on-surface-variant text-[11px] font-medium">
                            <span class="material-symbols-outlined text-[14px] text-primary/70">work_history</span>
                            {{ $driver->bookings_count ?? 0 }}x Melayani
                        </div>
                    </div>

                    <!-- HARGA & TOMBOL CTA -->
                    <div class="mt-auto pt-3 border-t border-outline-variant/30">
                        <div class="flex justify-between items-end mb-3">
                            <span class="font-inter text-[11px] text-on-surface-variant text-left block">Tarif Jasa<br>Pengemudi</span>
                            <div class="text-right">
                                <span class="font-montserrat text-[15px] font-bold text-primary">Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-medium text-on-surface-variant/70 block -mt-1">/ hari</span>
                            </div>
                        </div>
                        
                        <!-- Tombol CTA -->
                        <div class="w-full bg-primary/5 text-primary font-inter font-bold text-[12px] py-2 rounded-xl group-hover:bg-primary group-hover:text-white transition-all duration-300 flex items-center justify-center gap-1.5">
                            Lihat Profil <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </a>            
            @empty
            <div class="col-span-full bg-surface-container-lowest text-on-surface-variant p-10 rounded-2xl flex flex-col items-center justify-center text-center gap-4 border border-outline-variant/50 premium-shadow">
                <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center text-outline-variant">
                    <span class="material-symbols-outlined text-[32px]">person_off</span>
                </div>
                <div>
                    <h3 class="font-montserrat font-bold text-[16px] mb-1 text-on-surface">Belum Ada Data Supir</h3>
                    <p class="font-inter text-[13px] max-w-md mx-auto">Sistem belum memiliki data mitra pengemudi untuk ditampilkan saat ini. Silakan periksa kembali nanti.</p>
                </div>
            </div>
            @endforelse
        </div>
    </section>
</x-app-layout>