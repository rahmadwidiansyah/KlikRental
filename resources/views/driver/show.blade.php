<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: Foto Profil & Info Cepat -->
            <div class="md:col-span-1 flex flex-col gap-6">
                <!-- Card Profil Utama -->
                <div class="bg-surface rounded-2xl border border-outline-variant/30 premium-shadow overflow-hidden flex flex-col">
                    
                    <div class="pt-10 pb-6 flex justify-center bg-gradient-to-b from-surface-container/50 to-surface relative">
                        <!-- Badge Status -->
                        <div class="absolute top-4 right-4 z-10">
                            @if($driver->status === 'available')
                                <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-1 rounded-full font-inter font-bold text-[11px] flex items-center gap-1 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                                </span>
                            @elseif($driver->status === 'on_duty')
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full font-inter font-bold text-[11px] flex items-center gap-1 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Sedang Bertugas
                                </span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-full font-inter font-bold text-[11px] flex items-center gap-1 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Foto -->
                        <div class="relative w-40 h-40 rounded-full p-1.5 bg-white border border-outline-variant/50 shadow-md z-10">
                            <img src="{{ $driver->image_url ? asset('storage/' . $driver->image_url) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=e4dfff&color=140067' }}" 
                                 alt="Foto {{ $driver->name }}" 
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                    </div>

                    <div class="p-6 text-center pt-0 border-b border-outline-variant/30">
                        <h1 class="font-montserrat text-2xl font-bold text-on-surface mb-2">{{ $driver->name }}</h1>
                        <div class="flex items-center justify-center gap-1 text-on-surface-variant font-inter text-[14px]">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            Mitra Pengemudi Resmi
                        </div>
                    </div>

                    <div class="p-6 bg-surface-container-lowest text-center">
                        <span class="text-sm text-on-surface-variant font-inter block mb-1">Tarif Jasa Pengemudi</span>
                        <div class="text-2xl font-montserrat font-bold text-primary">
                            Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}<span class="text-sm text-on-surface-variant font-medium">/hari</span>
                        </div>
                    </div>
                </div>

                <!-- Card Statistik Mini -->
                <div class="bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow grid grid-cols-2 gap-4">
                    <div class="text-center border-r border-outline-variant/30">
                        <span class="material-symbols-outlined text-primary text-[28px] mb-1">star</span>
                        <h4 class="font-montserrat font-bold text-xl text-on-surface">{{ number_format($driver->reviews_avg_driver_rating ?? 5.0, 1) }}</h4>
                        <p class="font-inter text-[12px] text-on-surface-variant">Rating</p>
                    </div>
                    <div class="text-center">
                        <span class="material-symbols-outlined text-primary text-[28px] mb-1">work_history</span>
                        <h4 class="font-montserrat font-bold text-xl text-on-surface">{{ $driver->bookings_count ?? 0 }}</h4>
                        <p class="font-inter text-[12px] text-on-surface-variant">Perjalanan</p>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Ulasan Pelanggan -->
            <div class="md:col-span-2 flex flex-col gap-6">
                
                <div class="bg-surface rounded-2xl p-6 md:p-8 border border-outline-variant/30 premium-shadow">
                    <div class="flex justify-between items-center mb-6 border-b border-outline-variant/20 pb-4">
                        <h3 class="font-montserrat font-bold text-xl flex items-center gap-2 text-on-surface">
                            <span class="material-symbols-outlined text-primary text-[24px]">forum</span>
                            Ulasan untuk {{ $driver->name }}
                        </h3>
                    </div>

                    @if(isset($driver->reviews) && $driver->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($driver->reviews as $review)
                            <div class="border-b border-outline-variant/10 pb-5 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start mb-2.5">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar Inisial Nama -->
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[14px] uppercase shrink-0">
                                            {{ substr($review->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[14px] text-on-surface font-montserrat">{{ $review->user->name ?? 'Anonim' }}</p>
                                            <p class="text-[11px] text-on-surface-variant font-inter">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <!-- Render Bintang Khusus Supir -->
                                    <div class="flex text-[#B87503] gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[16px] {{ $i <= $review->driver_rating ? 'icon-fill' : 'text-outline-variant/30' }}">star</span>
                                        @endfor
                                    </div>
                                </div>
                                <!-- Komentar -->
                                @if($review->comment)
                                    <p class="text-[13px] text-on-surface-variant/90 leading-relaxed font-inter italic ml-13 border-l-2 border-primary/20 pl-3 mt-2">
                                        "{{ $review->comment }}"
                                    </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- State Kosong Jika Belum Ada Review -->
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-3 text-on-surface-variant/50">
                                <span class="material-symbols-outlined text-3xl">speaker_notes_off</span>
                            </div>
                            <p class="font-montserrat font-semibold text-on-surface text-[15px]">Belum Ada Ulasan</p>
                            <p class="font-inter text-sm text-on-surface-variant mt-1">Belum ada pelanggan yang meninggalkan ulasan khusus untuk pengemudi ini.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>