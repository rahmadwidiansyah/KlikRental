<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-on-surface-variant font-inter" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors" title="Kembali ke Katalog">Katalog Mobil</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="font-semibold text-on-surface" aria-current="page">{{ $vehicle->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: Galeri Gambar -->
            <div class="md:col-span-2 flex flex-col gap-4">
                <!-- Gambar Utama -->
                <div class="w-full h-[400px] bg-surface rounded-2xl border border-outline-variant/30 flex items-center justify-center overflow-hidden premium-shadow p-4 group">
                    <img id="main-image" src="{{ $vehicle->primaryImage ? Storage::url($vehicle->primaryImage->image_url) : 'https://placehold.co/800x500?text=Mobil' }}" 
                         alt="Gambar Utama {{ $vehicle->name }}" class="w-full h-full object-contain transition-opacity duration-300 group-hover:scale-105">
                </div>
                
                <!-- Thumbnail Gambar Lainnya -->
                @if($vehicle->images && $vehicle->images->count() > 0)
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($vehicle->images as $img)
                    <div class="w-24 h-24 rounded-xl border border-outline-variant/30 cursor-pointer overflow-hidden flex-shrink-0 bg-surface hover:border-primary hover:shadow-md transition-all duration-300" 
                         onclick="document.getElementById('main-image').style.opacity=0; setTimeout(() => { document.getElementById('main-image').src='{{ Storage::url($img->image_url) }}'; document.getElementById('main-image').style.opacity=1; }, 150);"
                         title="Lihat gambar ini">
                        <img src="{{ Storage::url($img->image_url) }}" alt="Thumbnail {{ $vehicle->name }}" loading="lazy" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Section Deskripsi/Review (Opsional) -->
                <div class="mt-8 bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow hover:shadow-lg transition-shadow">
                    <h3 class="font-montserrat font-bold text-xl mb-4">Informasi Kendaraan</h3>
                    <p class="font-inter text-on-surface-variant leading-relaxed text-justify">
                        {{-- Menggunakan data database jika ada, jika tidak gunakan teks bawaan Anda --}}
                        {{ $vehicle->description ?? "Mobil {$vehicle->name} dengan transmisi {$vehicle->transmission} ini sangat cocok untuk perjalanan Anda. Dirawat secara rutin dan siap digunakan untuk dalam maupun luar kota." }}
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: Detail & Checkout -->
            <div class="flex flex-col gap-6">
                <!-- Box Harga & Nama -->
                <div class="bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded-full font-inter font-bold text-xs uppercase shadow-sm">{{ $vehicle->type ?? 'UMUM' }}</span>
                        
                        {{-- Edit: Mengecek status agar label Tersedia/Disewa menjadi dinamis tanpa menghapus class aslinya --}}
                        <span class="px-2.5 py-0.5 rounded-full font-inter font-bold text-xs shadow-sm {{ (!isset($vehicle->status) || $vehicle->status === 'available') ? 'bg-forest-light text-forest-green' : 'bg-red-100 text-red-600' }}">
                            {{ (!isset($vehicle->status) || $vehicle->status === 'available') ? 'Tersedia' : 'Sedang Disewa' }}
                        </span>
                    </div>
                    
                    <h1 class="font-montserrat text-2xl font-bold text-on-surface mb-1">{{ $vehicle->name }}</h1>
                    
                    <div class="mt-4 pt-4 border-t border-outline-variant/30">
                        <span class="text-sm text-on-surface-variant font-inter">Harga Sewa</span>
                        <div class="text-3xl font-montserrat font-bold text-primary mt-1">
                            Rp {{ number_format($vehicle->price_per_day ?? 0, 0, ',', '.') }}<span class="text-sm text-on-surface-variant font-medium">/hari</span>
                        </div>
                    </div>

                    <!-- Tombol Lanjut Pesan -->
                    <a href="{{ route('booking.create', $vehicle->id) }}" class="mt-6 w-full bg-primary text-white font-inter font-bold py-3.5 rounded-xl hover:bg-primary/90 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-primary/30 focus:ring-4 focus:ring-primary/20">
                        Sewa Sekarang
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>

                <!-- Box Spesifikasi -->
                <div class="bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow hover:shadow-lg transition-shadow">
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