<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <nav class="flex mb-6 text-sm text-on-surface-variant font-inter" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors" title="Kembali ke Katalog">Katalog Mobil</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="font-semibold text-on-surface" aria-current="page">{{ $vehicle->name }}</span>
        </nav>

        <div class="flex flex-col md:grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            
            <div class="contents md:flex flex-col md:col-span-2 gap-4">
                
                <div class="order-1 md:order-none w-full h-[400px] bg-surface rounded-2xl border border-outline-variant/30 flex items-center justify-center overflow-hidden premium-shadow p-4 group">
                    <img id="main-image" src="{{ $vehicle->primaryImage ? Storage::url($vehicle->primaryImage->image_url) : 'https://placehold.co/800x500?text=Mobil' }}" 
                         alt="Gambar Utama {{ $vehicle->name }}" class="w-full h-full object-contain transition-opacity duration-300 group-hover:scale-105">
                </div>
                
                @if($vehicle->images && $vehicle->images->count() > 0)
                <div class="order-2 md:order-none flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($vehicle->images as $img)
                    <div class="w-24 h-24 rounded-xl border border-outline-variant/30 cursor-pointer overflow-hidden flex-shrink-0 bg-surface hover:border-primary hover:shadow-md transition-all duration-300" 
                         onclick="document.getElementById('main-image').style.opacity=0; setTimeout(() => { document.getElementById('main-image').src='{{ Storage::url($img->image_url) }}'; document.getElementById('main-image').style.opacity=1; }, 150);"
                         title="Lihat gambar ini">
                        <img src="{{ Storage::url($img->image_url) }}" alt="Thumbnail {{ $vehicle->name }}" loading="lazy" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="order-5 md:order-none mt-2 md:mt-4 bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow hover:shadow-lg transition-shadow">
                    <h3 class="font-montserrat font-bold text-xl mb-4">Informasi Kendaraan</h3>
                    <p class="font-inter text-on-surface-variant leading-relaxed text-justify">
                        {{ $vehicle->description ?? "Mobil {$vehicle->name} dengan transmisi {$vehicle->transmission} ini sangat cocok untuk perjalanan Anda. Dirawat secara rutin dan siap digunakan untuk dalam maupun luar kota." }}
                    </p>
                </div>

                <div class="order-6 md:order-none mt-2 md:mt-4 bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-center mb-6 border-b border-outline-variant/20 pb-4">
                        <h3 class="font-montserrat font-bold text-xl flex items-center gap-2 text-on-surface">
                            <span class="material-symbols-outlined text-primary text-[24px]">forum</span>
                            Ulasan Pelanggan
                        </h3>
                        
                        @if(isset($vehicle->reviews) && $vehicle->reviews->count() > 0)
                        <div class="flex items-center gap-1.5 bg-[#FFF8E7] text-[#B87503] px-3 py-1 rounded-lg font-bold text-[14px]">
                            <span class="material-symbols-outlined icon-fill text-[18px]">star</span>
                            {{ number_format($vehicle->reviews->avg('vehicle_rating'), 1) }} 
                            <span class="text-[12px] font-medium opacity-80">({{ $vehicle->reviews->count() }} ulasan)</span>
                        </div>
                        @endif
                    </div>

                    @if(isset($vehicle->reviews) && $vehicle->reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($vehicle->reviews()->latest()->get() as $review)
                            <div class="border-b border-outline-variant/10 pb-5 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start mb-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-[14px] uppercase shrink-0">
                                            {{ substr($review->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[14px] text-on-surface font-montserrat">{{ $review->user->name ?? 'Anonim' }}</p>
                                            <p class="text-[11px] text-on-surface-variant font-inter">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex text-[#B87503] gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[16px] {{ $i <= $review->vehicle_rating ? 'icon-fill' : 'text-outline-variant/30' }}">star</span>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-[13px] text-on-surface-variant/90 leading-relaxed font-inter italic ml-13 border-l-2 border-primary/20 pl-3">
                                        "{{ $review->comment }}"
                                    </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-3 text-on-surface-variant/50">
                                <span class="material-symbols-outlined text-3xl">speaker_notes_off</span>
                            </div>
                            <p class="font-montserrat font-semibold text-on-surface text-[15px]">Belum Ada Ulasan</p>
                            <p class="font-inter text-sm text-on-surface-variant mt-1">Jadilah yang pertama memberikan ulasan setelah menyewa mobil ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="contents md:flex flex-col gap-6">
                
                <div class="order-3 md:order-none bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded-full font-inter font-bold text-xs uppercase shadow-sm">{{ $vehicle->type ?? 'UMUM' }}</span>
                        
                        @if($vehicle->status === 'available')
                            <span class="bg-forest-light text-forest-green px-2.5 py-0.5 rounded-full font-inter font-bold text-xs shadow-sm flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-forest-green animate-pulse"></span> Tersedia
                            </span>
                        @elseif($vehicle->status === 'rented')
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full font-inter font-bold text-xs shadow-sm flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Sedang Disewa
                            </span>
                        @elseif($vehicle->status === 'maintenance')
                            <span class="bg-red-100 text-red-600 px-2.5 py-0.5 rounded-full font-inter font-bold text-xs shadow-sm flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Perawatan
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="font-montserrat text-2xl font-bold text-on-surface mb-1">{{ $vehicle->name }}</h1>
                    
                    <div class="mt-4 pt-4 border-t border-outline-variant/30">
                        <span class="text-sm text-on-surface-variant font-inter">Harga Sewa</span>
                        <div class="text-3xl font-montserrat font-bold text-primary mt-1">
                            Rp {{ number_format($vehicle->price_per_day ?? 0, 0, ',', '.') }}<span class="text-sm text-on-surface-variant font-medium">/hari</span>
                        </div>
                    </div>

                    @if($vehicle->status === 'available')
                        <a href="{{ route('booking.create', $vehicle->id) }}" class="mt-6 w-full bg-primary text-white font-inter font-bold py-3.5 rounded-xl hover:bg-primary/90 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-primary/30 focus:ring-4 focus:ring-primary/20">
                            Sewa Sekarang
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">arrow_forward</span>
                        </a>
                    @else
                        <button disabled class="mt-6 w-full bg-surface-container-highest text-on-surface-variant/50 font-inter font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">lock</span>
                            {{ $vehicle->status === 'rented' ? 'Sedang Disewa' : 'Sedang Perawatan' }}
                        </button>
                    @endif
                </div>

                <div class="order-4 md:order-none bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow hover:shadow-lg transition-shadow">
                    <h3 class="font-montserrat font-bold text-lg mb-4">Spesifikasi Detail</h3>
                    
                    <div class="flex flex-col gap-4 font-inter text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20 hover:text-primary transition-colors">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">settings</span> Transmisi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->transmission ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20 hover:text-primary transition-colors">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">group</span> Kapasitas Kursi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->seats ?? 0 }} Penumpang</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20 hover:text-primary transition-colors">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">luggage</span> Kapasitas Bagasi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->luggage_capacity ?? 0 }} Koper</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20 hover:text-primary transition-colors">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">local_gas_station</span> Bahan Bakar
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->fuel_type ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>