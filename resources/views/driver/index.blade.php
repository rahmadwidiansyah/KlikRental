<x-app-layout>
    <section class="relative bg-surface-container-lowest border-b border-outline-variant/30 py-12 md:py-16 overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-primary/5 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-secondary/5 blur-3xl"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-montserrat text-3xl md:text-4xl font-bold text-on-surface mb-4">Mitra Pengemudi <span class="text-primary">Terbaik</span></h1>
            <p class="font-inter text-[15px] text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Perjalanan Anda adalah prioritas kami. Semua pengemudi KlikRental telah melewati seleksi ketat, tersertifikasi, dan berdedikasi untuk memberikan pelayanan yang aman, nyaman, dan ramah.
            </p>
        </div>
    </section>

    <section class="py-12 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($drivers as $driver)
                <a href="{{ route('driver.show', $driver->id) }}" class="bg-surface border border-outline-variant/60 rounded-2xl overflow-hidden transition-all duration-300 premium-shadow group hover:border-primary/40 hover:-translate-y-1 relative flex flex-col block">
                    <div class="absolute top-3 right-3 z-10">
                        @if($driver->status === 'available')
                        <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                        </span>
                        @elseif($driver->status === 'on_duty')
                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Sedang Bertugas
                        </span>
                        @else
                        <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Tidak Aktif
                        </span>
                        @endif
                    </div>

                    <div class="pt-8 pb-4 flex justify-center bg-gradient-to-b from-surface-container/50 to-surface relative">
                        <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

                        <div class="relative w-28 h-28 rounded-full p-1 bg-white border border-outline-variant/50 shadow-md group-hover:border-primary/50 transition-colors z-10">
                            <img src="{{ $driver->image_url ? asset('storage/' . $driver->image_url) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=e4dfff&color=140067' }}"
                                alt="Foto {{ $driver->name }}"
                                class="w-full h-full object-cover rounded-full">
                        </div>
                    </div>

                    <div class="p-5 flex-grow flex flex-col text-center">
                        <h3 class="font-montserrat text-[18px] font-bold text-on-surface mb-1 line-clamp-1">{{ $driver->name }}</h3>

                        <div class="flex items-center justify-center gap-3 mt-2 mb-4">
                            <div class="flex items-center gap-1 bg-[#FFF8E7] text-[#B87503] px-2 py-0.5 rounded text-[11px] font-bold">
                                <span class="material-symbols-outlined icon-fill text-[14px]">star</span>
                                {{ number_format($driver->reviews_avg_driver_rating ?? 5.0, 1) }}
                            </div>
                            <span class="text-outline-variant/60 text-[10px]">|</span>
                            <div class="flex items-center gap-1 text-on-surface-variant text-[12px] font-medium">
                                <span class="material-symbols-outlined text-[16px]">work_history</span>
                                {{ $driver->bookings_count ?? 0 }}x Melayani
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-outline-variant/30">
                            <span class="font-inter text-[11px] text-on-surface-variant block mb-0.5">Tarif Jasa Pengemudi</span>
                            <span class="font-montserrat text-[16px] font-bold text-primary">Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}<span class="text-[12px] font-medium text-on-surface-variant/70">/hari</span></span>
                        </div>
                    </div>
                </a>            
            @empty
            <div class="col-span-full bg-surface-container-lowest text-on-surface-variant p-8 rounded-2xl flex flex-col items-center justify-center text-center gap-3 border border-outline-variant/50">
                <span class="material-symbols-outlined text-[48px] text-outline-variant">person_off</span>
                <div>
                    <h3 class="font-montserrat font-bold text-[18px] mb-1">Belum Ada Data Supir</h3>
                    <p class="font-inter text-[14px]">Sistem belum memiliki data mitra pengemudi untuk ditampilkan.</p>
                </div>
            </div>
            @endforelse
        </div>
        </div>
    </section>
</x-app-layout>