<x-app-layout>
    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); } 
        }
        .animate-marquee {
            display: flex;
            width: max-content;
            animation: marquee 35s linear infinite; 
        }
        .marquee-container:hover .animate-marquee {
            animation-play-state: paused; 
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <section class="relative min-h-[550px] flex items-center justify-center pt-10 pb-10 hero-bg bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC9bJYzj5fVYzxIjogSg18_3EooGnfWR6TPK5hFZrUb_131OSbfsKqpuH2_XgFKFoNFPKSkCpsALuRblJ-I9LHUTjNpUh5ih9JRA6d4ArWtVhNNrcBnarqhjiV_agGpHwD1-CSkoeVhwbpZiobo6iJ2Qz_AOYxEQ1SyvdNs0stirLJhEL9qMBPJjIGNDunUGnUX2T-EGxdRpa_4J1n7XOKdfT4pXCCQWeLKD3rZ_JuvZxY3126EaoZyvSj38Y7W1rlO_i7tyOjIvpa7');">
        <div class="absolute inset-0 gradient-overlay bg-black/60"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center mt-6">

            @auth
            <h1 class="font-montserrat text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 drop-shadow-lg tracking-tight leading-tight">
                Selamat Datang, <span class="text-primary">{{ explode(' ', Auth::user()->name ?? 'Pengguna')[0] }}</span>!
            </h1>
            @else
            <h1 class="font-montserrat text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 drop-shadow-lg tracking-tight leading-tight">
                Selamat Datang di <span class="text-primary">KlikRental</span>!
            </h1>
            <p class="font-inter text-[15px] md:text-[17px] text-white/90 mb-8 max-w-2xl drop-shadow">
                Nikmati pengalaman sewa mobil premium dengan proses verifikasi instan, ketersediaan real-time, dan dukungan 24/7.
            </p>
            @endauth

            <form action="{{ route('dashboard') }}" method="GET" class="glass-panel w-full max-w-4xl rounded-2xl p-4 md:p-5 shadow-lg border border-outline-variant/30 flex flex-col md:flex-row gap-3 items-end text-left bg-white/10 backdrop-blur-md">

                <div class="w-full">
                    <label for="zone_id" class="font-inter font-semibold text-[13px] text-white/90 mb-1.5 block">Lokasi Penjemputan</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10" aria-hidden="true">location_on</span>
                        <select name="zone_id" id="zone_id" required aria-label="Pilih Lokasi Penjemputan" class="w-full bg-none bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all appearance-none cursor-pointer relative z-20" style="background-image: none !important;">
                            <option value="">Pilih Lokasi...</option>
                            @foreach($zones ?? [] as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none z-30" aria-hidden="true">expand_more</span>
                    </div>
                </div>

                <div class="w-full">
                    <label for="start_date" class="font-inter font-semibold text-[13px] text-white/90 mb-1.5 block">Tgl Ambil</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]" aria-hidden="true">calendar_today</span>
                        <input type="date" name="start_date" id="start_date" aria-label="Pilih Tanggal Pengambilan" value="{{ request('start_date') }}" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all" />
                    </div>
                </div>

                <div class="w-full">
                    <label for="end_date" class="font-inter font-semibold text-[13px] text-white/90 mb-1.5 block">Tgl Kembali</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]" aria-hidden="true">event_busy</span>
                        <input type="date" name="end_date" id="end_date" aria-label="Pilih Tanggal Pengembalian" value="{{ request('end_date') }}" required min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all" />
                    </div>
                </div>

                <div class="w-full">
                    <label for="type" class="font-inter font-semibold text-[13px] text-white/90 mb-1.5 block">Kategori</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10" aria-hidden="true">directions_car</span>
                        <select name="type" id="type" aria-label="Pilih Kategori Mobil" class="w-full bg-surface bg-none border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all appearance-none cursor-pointer relative z-20" style="background-image: none !important;">
                            <option value="all">Semua</option>
                            <option value="SUV" {{ request('type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                            <option value="MPV" {{ request('type') == 'MPV' ? 'selected' : '' }}>MPV</option>
                            <option value="Sedan" {{ request('type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="Hatchback" {{ request('type') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="Minibus" {{ request('type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none z-30" aria-hidden="true">expand_more</span>
                    </div>
                </div>

                <button type="submit" class="w-full md:w-auto bg-primary text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all flex items-center justify-center gap-1.5 whitespace-nowrap active:scale-95 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">search</span>
                    Cari
                </button>

                @if(request()->has('start_date') || (request()->has('type') && request('type') != 'all'))
                <a href="{{ route('dashboard') }}" class="w-full md:w-auto bg-surface-container text-on-surface-variant font-inter font-bold text-[14px] py-2.5 px-4 rounded-xl hover:bg-surface-variant border border-outline-variant/50 transition-all flex items-center justify-center gap-1.5 whitespace-nowrap active:scale-95 shadow-sm" title="Hapus Filter">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">close</span>
                </a>
                @endif
            </form>

            <div class="flex flex-wrap justify-center gap-3 mt-8 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]" aria-hidden="true">check_circle</span>
                    Realtime Availability
                </div>
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]" aria-hidden="true">payments</span>
                    Pembayaran Digital
                </div>
                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 text-white font-inter text-[12px] font-semibold">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]" aria-hidden="true">verified_user</span>
                    Verifikasi Aman
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-background" id="armada">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h2 class="font-montserrat text-[24px] font-bold text-on-surface leading-tight">Armada Premium Kami</h2>
                    <p class="font-inter text-[14px] text-on-surface-variant">
                        @if(request()->has('start_date'))
                        Menampilkan mobil yang <span class="font-bold text-forest-green">Tersedia</span> pada {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @else
                        Pilihan efisien untuk perjalanan Anda.
                        @endif
                    </p>
                </div>
            </div>

            @if(!isset($vehicles) || $vehicles->isEmpty())
            <div class="bg-error-container text-on-error-container p-5 rounded-xl font-inter flex flex-col items-center justify-center text-center gap-3 border border-red-200 premium-shadow">
                <span class="material-symbols-outlined text-[40px] text-error" aria-hidden="true">car_crash</span>
                <div>
                    <h3 class="font-bold text-[16px] mb-1">Armada Tidak Ditemukan</h3>
                    <p class="text-[14px]">Maaf, saat ini semua armada mobil pada kriteria/tanggal tersebut sedang disewa.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="mt-2 text-primary font-semibold hover:underline">Lihat Semua Armada</a>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($vehicles as $car)
                <a href="{{ route('vehicle.show', $car->id) }}" class="vehicle-card block bg-surface border border-outline-variant/60 rounded-xl overflow-hidden transition-all duration-300 premium-shadow group relative flex flex-col hover:border-primary/50 hover:shadow-lg cursor-pointer" title="Lihat Detail {{ $car->name ?? 'Mobil' }}">

                    <div class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
                        <span class="bg-primary/10 text-primary border border-primary/20 px-2 py-0.5 rounded-full font-inter font-semibold text-[10px] flex items-center gap-1 backdrop-blur-md w-max">
                            <span class="material-symbols-outlined text-[14px] icon-fill" aria-hidden="true">directions_car</span> {{ $car->type ?? 'Umum' }}
                        </span>
                    </div>

                    @if(isset($car->is_booked_today) && $car->is_booked_today)
                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Sedang Disewa
                        </span>
                    </div>
                    @else
                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] shadow-sm flex items-center gap-1 backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                        </span>
                    </div>
                    @endif

                    <div class="h-44 overflow-hidden bg-surface-container flex items-center justify-center p-4 relative">
                        <img src="{{ isset($car->primaryImage) && $car->primaryImage ? Storage::url($car->primaryImage->image_url) : 'https://placehold.co/400x250?text=Mobil' }}"
                            alt="Foto {{ $car->name ?? 'Kendaraan' }}" loading="lazy"
                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500 drop-shadow-md">
                    </div>

                    <div class="p-4 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-montserrat text-[16px] font-bold text-on-surface mb-0.5 line-clamp-1">{{ $car->name ?? 'Nama Mobil' }}</h3>
                                <p class="font-inter text-on-surface-variant text-[12px]">{{ $car->transmission ?? '-' }}</p>
                            </div>
                            <div class="text-right shrink-0 ml-2">
                                <span class="font-inter text-[10px] text-on-surface-variant block leading-tight">Mulai dari</span>
                                <span class="font-montserrat text-[15px] font-bold text-primary">Rp {{ number_format($car->price_per_day ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between mb-4 pb-4 border-b border-outline-variant/30 mt-auto px-1">
                            <div class="flex items-center gap-1" title="Kapasitas Penumpang">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]" aria-hidden="true">group</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->seats ?? 0 }}</span>
                            </div>
                            <div class="flex items-center gap-1" title="Kapasitas Bagasi (Koper)">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]" aria-hidden="true">luggage</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->luggage_capacity ?? 0 }}</span>
                            </div>
                            <div class="flex items-center gap-1" title="Jenis Bahan Bakar">
                                <span class="material-symbols-outlined text-on-surface-variant text-[16px]" aria-hidden="true">local_gas_station</span>
                                <span class="font-inter text-[11px] text-on-surface-variant">{{ $car->fuel_type ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="w-full bg-primary/5 text-primary font-inter font-bold text-[13px] py-2.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-all duration-300 flex items-center justify-center gap-1.5 shadow-sm border border-primary/20">
                            Lihat Detail
                            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">visibility</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <div x-data="{ 
        showDetail: false, revName: '', revDate: '', revVehicle: '', 
        revVRating: 5, revCRating: 5, revComment: '' 
    }">

        <section class="py-12 bg-surface overflow-hidden border-t border-outline-variant/20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <h2 class="font-montserrat text-[24px] font-bold text-on-surface">Apa Kata Mereka?</h2>
                <p class="font-inter text-[14px] text-on-surface-variant">Pengalaman nyata dari pelanggan setia KlikRental.</p>
            </div>

            @php
                $displayReviews = (isset($reviews) && $reviews->count() > 0) ? $reviews : collect([
                    (object)['user' => (object)['name' => 'Budi Santoso'], 'vehicle_rating' => 5, 'company_rating' => 5, 'comment' => 'Pelayanan sangat memuaskan! Mobil bersih dan mesinnya halus. Sangat membantu keliling area Kota Semarang.', 'vehicle' => (object)['name' => 'Toyota Innova Zenix'], 'created_at' => now()->subDays(2)],
                    (object)['user' => (object)['name' => 'Siti Aisyah'], 'vehicle_rating' => 4, 'company_rating' => 5, 'comment' => 'Proses sewa gampang banget pakai face recognition. Mobilnya enak dipakai dan irit.', 'vehicle' => (object)['name' => 'Honda Brio RS'], 'created_at' => now()->subDays(5)],
                    (object)['user' => (object)['name' => 'Andi Wijaya'], 'vehicle_rating' => 5, 'company_rating' => 4, 'comment' => 'Mantap, harganya transparan ga ada biaya tersembunyi pas balikin mobil. Recomended!', 'vehicle' => (object)['name' => 'Mitsubishi Pajero'], 'created_at' => now()->subDays(10)],
                ]);
            @endphp

            <div class="absolute left-0 top-0 bottom-0 w-12 md:w-32 bg-gradient-to-r from-surface to-transparent z-10 pointer-events-none mt-20"></div>
            <div class="absolute right-0 top-0 bottom-0 w-12 md:w-32 bg-gradient-to-l from-surface to-transparent z-10 pointer-events-none mt-20"></div>

            <div class="marquee-container w-full overflow-hidden flex relative group pb-4">
                <div class="animate-marquee gap-5 px-4 items-stretch">
                    @for($i = 0; $i < 2; $i++) @foreach($displayReviews as $rev)
                            <div @click="
                                    showDetail = true;
                                    revName = '{{ addslashes($rev->user->name ?? 'Anonim') }}';
                                    revDate = '{{ \Carbon\Carbon::parse($rev->created_at)->diffForHumans() }}';
                                    revVehicle = '{{ addslashes($rev->vehicle->name ?? '-') }}';
                                    revVRating = {{ $rev->vehicle_rating ?? 5 }};
                                    revCRating = {{ $rev->company_rating ?? 5 }};
                                    revComment = '{{ addslashes(str_replace(["\r", "\n"], ' ', $rev->comment ?? '')) }}';
                                 " 
                                 class="w-[320px] md:w-[380px] bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-5 premium-shadow cursor-pointer hover:border-primary/50 hover:shadow-lg transition-all flex-shrink-0 flex flex-col">
                                
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[14px] uppercase shrink-0">
                                            {{ substr($rev->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[14px] text-on-surface font-montserrat line-clamp-1">{{ $rev->user->name ?? 'Anonim' }}</p>
                                            <p class="text-[11px] text-on-surface-variant font-inter flex items-center gap-1">
                                                Sewa <span class="font-semibold text-primary">{{ $rev->vehicle->name ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex text-[#B87503] gap-0.5 bg-[#FFF8E7] px-1.5 py-0.5 rounded">
                                        <span class="material-symbols-outlined text-[14px] icon-fill">star</span>
                                        <span class="text-[12px] font-bold">{{ number_format($rev->vehicle_rating ?? 5, 1) }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-[13px] text-on-surface-variant/90 leading-relaxed font-inter italic line-clamp-3 mt-2">
                                    "{{ $rev->comment }}"
                                </p>
                                
                                <div class="mt-auto pt-3">
                                    <span class="text-[11px] text-primary font-medium hover:underline flex items-center gap-1">
                                        Baca selengkapnya <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </section>

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
                                <span class="material-symbols-outlined text-forest-green text-[20px]" aria-hidden="true">verified</span>
                            </div>
                            <h3 class="font-montserrat text-[20px] font-bold text-on-surface mb-2">Verifikasi Aman & Instan</h3>
                            <p class="font-inter text-[14px] text-on-surface-variant max-w-md">Proses onboarding menggunakan e-KYC dan teknologi face recognition untuk keamanan maksimal dan persetujuan yang cepat.</p>

                            <div class="mt-auto flex gap-3 pt-4">
                                <div class="bg-surface px-3 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1.5 shadow-sm">
                                    <span class="material-symbols-outlined text-forest-green text-[14px]" aria-hidden="true">check_circle</span>
                                    <span class="font-inter font-medium text-[11px]">KTP Terintegrasi</span>
                                </div>
                                <div class="bg-surface px-3 py-1.5 rounded-lg border border-outline-variant flex items-center gap-1.5 shadow-sm">
                                    <span class="material-symbols-outlined text-forest-green text-[14px]" aria-hidden="true">face</span>
                                    <span class="font-inter font-medium text-[11px]">Liveness Check</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 premium-shadow group hover:-translate-y-1 transition-transform flex flex-col justify-center">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mb-3 text-primary">
                            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">schedule</span>
                        </div>
                        <h3 class="font-montserrat text-[15px] font-bold text-on-surface mb-1">Booking Real-time</h3>
                        <p class="font-inter text-[13px] text-on-surface-variant line-clamp-3">Sistem inventaris terhubung langsung. Apa yang Anda lihat adalah apa yang tersedia.</p>
                    </div>

                    <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/40 premium-shadow group hover:-translate-y-1 transition-transform flex flex-col justify-center">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center mb-3 text-primary">
                            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">qr_code_scanner</span>
                        </div>
                        <h3 class="font-montserrat text-[15px] font-bold text-on-surface mb-1">Pembayaran Digital</h3>
                        <p class="font-inter text-[13px] text-on-surface-variant line-clamp-3">Mendukung berbagai metode pembayaran QRIS & Transfer dengan konfirmasi instan.</p>
                    </div>
                </div>
            </div>
        </section>

        <div x-show="showDetail" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
            
            <div x-show="showDetail" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDetail = false"></div>
            
            <div x-show="showDetail" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative bg-surface w-full max-w-md rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-outline-variant/30">
                
                <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container/50">
                    <h3 class="font-montserrat font-bold text-lg text-on-surface">Detail Ulasan</h3>
                    <button @click="showDetail = false" type="button" class="text-on-surface-variant hover:text-error transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-4 border-b border-outline-variant/20 pb-4">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[18px] uppercase shrink-0" x-text="revName.charAt(0)">
                        </div>
                        <div>
                            <h4 class="font-montserrat font-bold text-[16px] text-on-surface" x-text="revName"></h4>
                            <p class="font-inter text-[12px] text-on-surface-variant" x-text="revDate"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/30">
                        <div>
                            <span class="font-inter text-[11px] text-on-surface-variant block mb-1">Kondisi Kendaraan</span>
                            <div class="flex text-[#B87503] gap-0.5">
                                <template x-for="i in 5">
                                    <span class="material-symbols-outlined text-[16px]" :class="i <= revVRating ? 'icon-fill' : 'text-outline-variant/30'">star</span>
                                </template>
                            </div>
                            <p class="font-inter font-semibold text-[13px] text-primary mt-1 line-clamp-1" x-text="revVehicle"></p>
                        </div>
                        <div>
                            <span class="font-inter text-[11px] text-on-surface-variant block mb-1">Pelayanan KlikRental</span>
                            <div class="flex text-[#B87503] gap-0.5">
                                <template x-for="i in 5">
                                    <span class="material-symbols-outlined text-[16px]" :class="i <= revCRating ? 'icon-fill' : 'text-outline-variant/30'">star</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="font-inter font-semibold text-[13px] text-on-surface-variant mb-2">Komentar:</h5>
                        <p class="font-inter text-[14px] text-on-surface leading-relaxed italic bg-primary/5 p-4 rounded-xl border-l-4 border-primary" x-text="revComment ? '“' + revComment + '”' : 'Tidak ada komentar tertulis.'"></p>
                    </div>
                </div>
                
                <div class="p-5 border-t border-outline-variant/30 bg-surface-container/30">
                    <button @click="showDetail = false" class="w-full bg-surface-variant text-on-surface-variant font-bold text-[14px] py-2.5 rounded-xl hover:bg-outline-variant/30 transition-all border border-outline-variant/50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div> </x-app-layout>