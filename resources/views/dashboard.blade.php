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

    <section class="relative bg-primary pt-8 pb-6">
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">

            @auth
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4 tracking-tight leading-tight">
                Selamat Datang, <span class="text-white/90">{{ explode(' ', Auth::user()->name ?? 'Pengguna')[0] }}</span>!
            </h1>
            @else
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4 tracking-tight leading-tight">
                Selamat Datang di <span class="text-white/90">KlikRental</span>!
            </h1>
            @endauth

            <div class="flex flex-wrap justify-center gap-3 mb-6 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">check_circle</span> Realtime Availability
                </div>
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">payments</span> Pembayaran Digital
                </div>
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20">
                    <span class="material-symbols-outlined text-[#8cd95c] icon-fill text-[16px]">notifications_active</span> Pengingat Otomatis
                </div>
            </div>
        </div>
    </section>

    <section class="relative -mt-6 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('dashboard') }}" method="GET" class="bg-white rounded-2xl p-4 md:p-5 shadow-lg border border-outline-variant/30 flex flex-col md:flex-row gap-3 items-end w-full">
            
            <div class="w-full">
                <label for="zone_id" class="font-inter font-semibold text-[13px] text-on-surface mb-1.5 block">Penjemputan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">location_on</span>
                    <select name="zone_id" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px]">
                        <option value="">Semua Lokasi</option>
                        @foreach($zones ?? [] as $zone)
                        <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->zone_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="w-full">
                <label for="start_date" class="font-inter font-semibold text-[13px] text-on-surface mb-1.5 block">Tgl Ambil</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">calendar_today</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px]" />
                </div>
            </div>
            <div class="w-full">
                <label for="end_date" class="font-inter font-semibold text-[13px] text-on-surface mb-1.5 block">Tgl Kembali</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">event_busy</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" min="{{ date('Y-m-d') }}" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-3 text-[14px]" />
                </div>
            </div>

            <div class="w-full">
                <label for="class" class="font-inter font-semibold text-[13px] text-on-surface mb-1.5 block">Kelas</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">workspace_premium</span>
                    <select name="class" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-8 text-[14px]">
                        <option value="all">Semua Kelas</option>
                        <option value="VIP" {{ request('class') == 'VIP' ? 'selected' : '' }}>VIP</option>
                        <option value="Premium" {{ request('class') == 'Premium' ? 'selected' : '' }}>Premium</option>
                        <option value="Standard" {{ request('class') == 'Standard' ? 'selected' : '' }}>Standard</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full md:w-auto bg-primary text-white font-inter font-bold text-[14px] py-2.5 px-6 rounded-xl hover:bg-primary/90 transition-all flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[20px]">search</span> Cari
            </button>
            
            @if(request()->anyFilled(['start_date', 'zone_id', 'class', 'type']))
                <a href="{{ route('dashboard') }}" class="w-full md:w-auto bg-surface-container text-on-surface-variant font-inter font-bold text-[14px] py-2.5 px-4 rounded-xl border transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </a>
            @endif
        </form>
    </section>

    <section class="py-10 bg-background" id="armada">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(isset($groupedVehicles) && $groupedVehicles->isEmpty())
                <div class="bg-error-container text-on-error-container p-5 rounded-xl font-inter flex flex-col items-center text-center gap-3 border">
                    <span class="material-symbols-outlined text-[40px] text-error">car_crash</span>
                    <div>
                        <h3 class="font-bold text-[16px] mb-1">Armada Tidak Ditemukan</h3>
                        <p class="text-[14px]">Maaf, armada dengan kriteria tersebut sedang tidak tersedia.</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="mt-2 text-primary font-semibold hover:underline">Hapus Filter</a>
                </div>
            @else
                @foreach(['VIP', 'Premium', 'Standard'] as $className)
                    @if(isset($groupedVehicles[$className]) && $groupedVehicles[$className]->count() > 0)
                        <div class="mb-10">
                            <div class="flex justify-between items-end mb-4 pb-2 border-b border-outline-variant/30">
                                <div>
                                    <h2 class="font-montserrat text-[22px] font-bold text-on-surface flex items-center gap-2">
                                        @if($className == 'VIP') <span class="material-symbols-outlined text-yellow-500">diamond</span>
                                        @elseif($className == 'Premium') <span class="material-symbols-outlined text-blue-500">star</span>
                                        @else <span class="material-symbols-outlined text-green-500">directions_car</span>
                                        @endif
                                        Kelas {{ $className }}
                                    </h2>
                                </div>
                                <a href="{{ route('vehicle.index', ['class' => $className]) }}" class="text-primary font-inter text-[13px] font-bold hover:underline flex items-center gap-1">
                                    Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                @foreach($groupedVehicles[$className]->take(4) as $car) <a href="{{ route('vehicle.show', $car->id) }}" class="vehicle-card block bg-surface border border-outline-variant/60 rounded-xl overflow-hidden transition-all duration-300 premium-shadow group relative flex flex-col hover:border-primary/50 hover:shadow-lg cursor-pointer">
                                        
                                        @if(isset($car->is_booked_today) && $car->is_booked_today)
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> Disewa
                                            </span>
                                        </div>
                                        @elseif($car->status === 'maintenance')
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-red-100 text-red-600 border border-red-200 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Perawatan
                                            </span>
                                        </div>
                                        @elseif($car->status === 'rented')
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-blue-100 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Disewa
                                            </span>
                                        </div>
                                        @else
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-0.5 rounded-full font-inter font-bold text-[10px] flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                                            </span>
                                        </div>
                                        @endif

                                        <div class="h-44 overflow-hidden bg-surface-container flex items-center justify-center p-4 relative">
                                            <img src="{{ isset($car->primaryImage) && $car->primaryImage ? Storage::url($car->primaryImage->image_url) : 'https://placehold.co/400x250?text=Mobil' }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                        </div>

                                        <div class="p-4 flex-grow flex flex-col">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h3 class="font-montserrat text-[16px] font-bold text-on-surface mb-0.5 line-clamp-1">{{ $car->name }}</h3>
                                                    <p class="font-inter text-on-surface-variant text-[12px]">{{ $car->transmission }}</p>
                                                </div>
                                                <div class="text-right shrink-0 ml-2">
                                                    <span class="font-inter text-[10px] text-on-surface-variant block">Mulai dari</span>
                                                    <span class="font-montserrat text-[15px] font-bold text-primary">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="w-full mt-auto bg-primary/5 text-primary font-inter font-bold text-[13px] py-2.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-all duration-300 flex items-center justify-center gap-1.5">
                                                Lihat Detail
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </section>

    <section class="py-14 bg-surface-container-lowest border-y border-outline-variant/20 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                
                <div>
                    <h2 class="font-montserrat text-[26px] md:text-[32px] font-bold text-on-surface mb-4">Kenapa Memilih <span class="text-primary">KlikRental</span>?</h2>
                    <p class="font-inter text-[15px] text-on-surface-variant mb-8 leading-relaxed">
                        Kami berkomitmen memberikan pengalaman sewa mobil yang aman, nyaman, dan transparan. Nikmati berbagai kemudahan yang dirancang khusus untuk kelancaran perjalanan Anda.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 text-primary mt-1 border border-primary/20 shadow-sm">
                                <span class="material-symbols-outlined text-[24px]">verified_user</span>
                            </div>
                            <div>
                                <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Armada Terawat & Tersertifikasi</h4>
                                <p class="font-inter text-[13px] text-on-surface-variant mt-1.5 leading-relaxed">Setiap kendaraan melewati inspeksi rutin bengkel resmi untuk menjamin performa, keselamatan, dan kebersihan maksimal.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 text-primary mt-1 border border-primary/20 shadow-sm">
                                <span class="material-symbols-outlined text-[24px]">support_agent</span>
                            </div>
                            <div>
                                <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Layanan Pelanggan 24/7</h4>
                                <p class="font-inter text-[13px] text-on-surface-variant mt-1.5 leading-relaxed">Tim operasional kami selalu siap sedia membantu Anda kapan saja, mulai dari proses pemesanan hingga perjalanan Anda selesai.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 text-primary mt-1 border border-primary/20 shadow-sm">
                                <span class="material-symbols-outlined text-[24px]">price_check</span>
                            </div>
                            <div>
                                <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Harga Transparan Tanpa Jebakan</h4>
                                <p class="font-inter text-[13px] text-on-surface-variant mt-1.5 leading-relaxed">Tidak ada biaya tersembunyi. Anda hanya membayar harga pasti yang tertera di layar ponsel Anda saat melakukan pemesanan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 md:gap-5">
                    <div class="bg-surface border border-outline-variant/40 p-6 md:p-8 rounded-2xl premium-shadow text-center flex flex-col justify-center transition-transform hover:-translate-y-1 duration-300">
                        <div class="w-14 h-14 mx-auto bg-surface-container rounded-full flex items-center justify-center mb-3 text-primary">
                            <span class="material-symbols-outlined text-[28px]">directions_car</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-3xl md:text-4xl text-on-surface">{{ $totalVehicles ?? 0 }}+</h3>
                        <p class="font-inter text-[13px] text-on-surface-variant mt-1.5 font-medium">Pilihan Armada</p>
                    </div>
                    <div class="bg-surface border border-outline-variant/40 p-6 md:p-8 rounded-2xl premium-shadow text-center flex flex-col justify-center transition-transform hover:-translate-y-1 duration-300">
                        <div class="w-14 h-14 mx-auto bg-surface-container rounded-full flex items-center justify-center mb-3 text-primary">
                            <span class="material-symbols-outlined text-[28px]">task_alt</span>
                        </div>
                        <h3 class="font-montserrat font-bold text-3xl md:text-4xl text-on-surface">{{ $totalBookings ?? 0 }}+</h3>
                        <p class="font-inter text-[13px] text-on-surface-variant mt-1.5 font-medium">Perjalanan Sukses</p>
                    </div>
                    
                    <div class="col-span-2 bg-primary p-6 md:p-8 rounded-2xl premium-shadow text-center flex flex-col justify-center text-white relative overflow-hidden transition-transform hover:-translate-y-1 duration-300">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mt-10 -mr-10"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl -mb-10 -ml-10"></div>
                        
                        <div class="relative z-10">
                            <div class="w-14 h-14 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-3 backdrop-blur-sm border border-white/30">
                                <span class="material-symbols-outlined text-[28px]">groups</span>
                            </div>
                            <h3 class="font-montserrat font-bold text-3xl md:text-4xl">{{ $totalCustomers ?? 0 }}+</h3>
                            <p class="font-inter text-[14px] text-white/90 mt-1.5 font-medium">Pelanggan Telah Mempercayai Kami</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div x-data="{ 
        showDetail: false, revName: '', revDate: '', revVehicle: '', 
        revVRating: 5, revCRating: 5, revComment: '', revAvatar: '' 
    }">

        <section class="py-12 bg-surface overflow-hidden relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <h2 class="font-montserrat text-[24px] font-bold text-on-surface">Apa Kata Mereka?</h2>
                <p class="font-inter text-[14px] text-on-surface-variant">Pengalaman nyata dari pelanggan setia KlikRental.</p>
            </div>

            @php
                // Tambahan fallback dummy avatar jika review kosong
                $displayReviews = (isset($reviews) && $reviews->count() > 0) ? $reviews : collect([
                    (object)['user' => (object)['name' => 'Budi Santoso', 'avatar' => null], 'vehicle_rating' => 5, 'company_rating' => 5, 'comment' => 'Pelayanan sangat memuaskan! Mobil bersih dan mesinnya halus.', 'vehicle' => (object)['name' => 'Toyota Innova Zenix'], 'created_at' => now()->subDays(2)],
                    (object)['user' => (object)['name' => 'Siti Aisyah', 'avatar' => null], 'vehicle_rating' => 4, 'company_rating' => 5, 'comment' => 'Proses sewa gampang banget pakai face recognition. Mobilnya enak dipakai dan irit.', 'vehicle' => (object)['name' => 'Honda Brio RS'], 'created_at' => now()->subDays(5)],
                    (object)['user' => (object)['name' => 'Andi Wijaya', 'avatar' => null], 'vehicle_rating' => 5, 'company_rating' => 4, 'comment' => 'Mantap, harganya transparan ga ada biaya tersembunyi pas balikin mobil.', 'vehicle' => (object)['name' => 'Mitsubishi Pajero'], 'created_at' => now()->subDays(10)],
                ]);
            @endphp

            <div class="absolute left-0 top-0 bottom-0 w-12 md:w-32 bg-gradient-to-r from-surface to-transparent z-10 pointer-events-none mt-20"></div>
            <div class="absolute right-0 top-0 bottom-0 w-12 md:w-32 bg-gradient-to-l from-surface to-transparent z-10 pointer-events-none mt-20"></div>

            <div class="marquee-container w-full overflow-hidden flex relative group pb-4">
                <div class="animate-marquee gap-5 px-4 items-stretch">
                    @for($i = 0; $i < 2; $i++) 
                        @foreach($displayReviews as $rev)
                            @php
                                // Cek kolom avatar (sesuaikan dengan nama kolom di database users kamu)
                                $userAvatar = $rev->user->avatar ?? $rev->user->profile_photo_url ?? $rev->user->profile_photo_path ?? null;
                            @endphp
                            
                            <div @click="
                                    showDetail = true;
                                    revName = '{{ addslashes($rev->user->name ?? 'Anonim') }}';
                                    revDate = '{{ \Carbon\Carbon::parse($rev->created_at)->diffForHumans() }}';
                                    revVehicle = '{{ addslashes($rev->vehicle->name ?? '-') }}';
                                    revVRating = {{ $rev->vehicle_rating ?? 5 }};
                                    revCRating = {{ $rev->company_rating ?? 5 }};
                                    revComment = '{{ addslashes(str_replace(["\r", "\n"], ' ', $rev->comment ?? '')) }}';
                                    revAvatar = '{{ $userAvatar }}';
                                 " 
                                 class="w-[320px] md:w-[380px] bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-5 premium-shadow cursor-pointer hover:border-primary/50 hover:shadow-lg transition-all flex-shrink-0 flex flex-col">
                                
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        @if($userAvatar)
                                            <img src="{{ filter_var($userAvatar, FILTER_VALIDATE_URL) ? $userAvatar : Storage::url($userAvatar) }}" alt="{{ $rev->user->name ?? 'User' }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-outline-variant/30 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[14px] uppercase shrink-0 border border-primary/20">
                                                {{ substr($rev->user->name ?? 'A', 0, 1) }}
                                            </div>
                                        @endif

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
                        <template x-if="revAvatar">
                            <img :src="revAvatar" alt="Avatar" class="w-12 h-12 rounded-full object-cover shrink-0 border border-outline-variant/30 shadow-sm">
                        </template>
                        <template x-if="!revAvatar">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[18px] uppercase shrink-0 border border-primary/20" x-text="revName.charAt(0)">
                            </div>
                        </template>

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
    </div>
</x-app-layout>