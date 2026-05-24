<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm text-on-surface-variant font-inter">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Katalog Mobil</a>
            <span class="mx-2">/</span>
            <span class="font-semibold text-on-surface">{{ $vehicle->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: Galeri Gambar -->
            <div class="md:col-span-2 flex flex-col gap-4">
                <!-- Gambar Utama -->
                <div class="w-full h-[400px] bg-surface rounded-2xl border border-outline-variant/30 flex items-center justify-center overflow-hidden premium-shadow p-4">
                    <img id="main-image" src="{{ $vehicle->primaryImage ? Storage::url($vehicle->primaryImage->image_url) : 'https://placehold.co/800x500?text=Mobil' }}" 
                         alt="{{ $vehicle->name }}" class="w-full h-full object-contain">
                </div>
                
                <!-- Thumbnail Gambar Lainnya -->
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach($vehicle->images as $img)
                    <div class="w-24 h-24 rounded-xl border border-outline-variant/30 cursor-pointer overflow-hidden flex-shrink-0 bg-surface hover:border-primary transition-all" onclick="document.getElementById('main-image').src='{{ Storage::url($img->image_url) }}'">
                        <img src="{{ Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>

                <!-- Section Deskripsi/Review (Opsional) -->
                <div class="mt-8 bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow">
                    <h3 class="font-montserrat font-bold text-xl mb-4">Informasi Kendaraan</h3>
                    <p class="font-inter text-on-surface-variant leading-relaxed">
                        Mobil {{ $vehicle->name }} dengan transmisi {{ $vehicle->transmission }} ini sangat cocok untuk perjalanan Anda. Dirawat secara rutin dan siap digunakan untuk dalam maupun luar kota.
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: Detail & Checkout -->
            <div class="flex flex-col gap-6">
                <!-- Box Harga & Nama -->
                <div class="bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-primary/10 text-primary px-2.5 py-0.5 rounded-full font-inter font-bold text-xs uppercase">{{ $vehicle->type }}</span>
                        <span class="bg-forest-light text-forest-green px-2.5 py-0.5 rounded-full font-inter font-bold text-xs">Tersedia</span>
                    </div>
                    
                    <h1 class="font-montserrat text-2xl font-bold text-on-surface mb-1">{{ $vehicle->name }}</h1>
                    
                    <div class="mt-4 pt-4 border-t border-outline-variant/30">
                        <span class="text-sm text-on-surface-variant font-inter">Harga Sewa</span>
                        <div class="text-3xl font-montserrat font-bold text-primary mt-1">
                            Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span class="text-sm text-on-surface-variant font-medium">/hari</span>
                        </div>
                    </div>

                    <!-- Tombol Lanjut Pesan -->
                    <a href="{{ route('booking.create', $vehicle->id) }}" class="mt-6 w-full bg-primary text-white font-inter font-bold py-3.5 rounded-xl hover:bg-primary/90 transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary/30">
                        Sewa Sekarang
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </a>
                </div>

                <!-- Box Spesifikasi -->
                <div class="bg-surface rounded-2xl p-6 border border-outline-variant/30 premium-shadow">
                    <h3 class="font-montserrat font-bold text-lg mb-4">Spesifikasi Detail</h3>
                    
                    <div class="flex flex-col gap-4 font-inter text-sm">
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]">settings</span> Transmisi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->transmission }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]">group</span> Kapasitas Kursi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->seats }} Penumpang</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]">luggage</span> Kapasitas Bagasi
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->luggage_capacity }} Koper</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-outline-variant/20">
                            <div class="flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[20px]">local_gas_station</span> Bahan Bakar
                            </div>
                            <span class="font-semibold text-on-surface">{{ $vehicle->fuel_type }}</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>