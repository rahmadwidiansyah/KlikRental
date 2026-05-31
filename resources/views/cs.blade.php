<x-app-layout>
    <!-- Tambahkan library CSS & JS Leaflet di atas -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- HERO SECTION -->
    <section class="relative bg-primary pt-8 pb-10 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                Pusat Bantuan & <span class="text-white/90">Layanan CS</span>
            </h1>
            <p class="font-inter text-[14px] md:text-[15px] text-white/80 max-w-2xl mx-auto leading-relaxed mb-4">
                Punya pertanyaan atau kendala di jalan? Tim operasional KlikRental siap membantu Anda. Jangan ragu untuk menghubungi kami melalui saluran di bawah ini.
            </p>
            <div class="flex flex-wrap justify-center gap-3 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">support_agent</span>
                    Layanan Pelanggan 24/7
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT CARDS SECTION -->
    <section class="relative -mt-6 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="https://wa.me/6285156642834" target="_blank" class="bg-surface rounded-2xl p-6 shadow-xl border border-outline-variant/30 premium-shadow flex flex-col items-center text-center transition-transform hover:-translate-y-2 hover:border-[#25D366]/50 duration-300 group">
                <div class="w-16 h-16 bg-[#25D366]/10 rounded-full flex items-center justify-center text-[#25D366] mb-4 border border-[#25D366]/20 group-hover:bg-[#25D366] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[32px]">chat</span>
                </div>
                <h3 class="font-montserrat text-[18px] font-bold text-on-surface mb-2">WhatsApp CS</h3>
                <p class="font-inter text-[14px] text-on-surface-variant mb-3">0851-5664-2834</p>
                <span class="text-[#25D366] font-inter text-[13px] font-bold flex items-center gap-1 mt-auto">
                    Chat Sekarang <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </span>
            </a>
            
            <a href="tel:+6285156642834" class="bg-surface rounded-2xl p-6 shadow-xl border border-outline-variant/30 premium-shadow flex flex-col items-center text-center transition-transform hover:-translate-y-2 hover:border-primary/50 duration-300 group">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-4 border border-primary/20 group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[32px]">call</span>
                </div>
                <h3 class="font-montserrat text-[18px] font-bold text-on-surface mb-2">Telepon Darurat</h3>
                <p class="font-inter text-[14px] text-on-surface-variant mb-3">0851-5664-2834</p>
                <span class="text-primary font-inter text-[13px] font-bold flex items-center gap-1 mt-auto">
                    Hubungi Kami <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </span>
            </a>

            <div class="bg-surface rounded-2xl p-6 shadow-xl border border-outline-variant/30 premium-shadow flex flex-col items-center text-center transition-transform hover:-translate-y-2 duration-300">
                <div class="w-16 h-16 bg-orange-50 dark:bg-orange-900/20 rounded-full flex items-center justify-center text-orange-500 mb-4 border border-orange-200 dark:border-orange-900/30">
                    <span class="material-symbols-outlined text-[32px]">schedule</span>
                </div>
                <h3 class="font-montserrat text-[18px] font-bold text-on-surface mb-2">Jam Operasional</h3>
                <p class="font-inter text-[14px] text-on-surface-variant">Setiap Hari (Senin - Minggu)</p>
                <p class="font-inter text-[15px] font-bold text-primary mt-1">08:00 - 22:00 WIB</p>
            </div>
        </div>
    </section>

    <!-- MAP SECTION -->
    <section class="py-12 bg-surface-container-lowest" x-data="leafletMap()" x-init="initMap()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                <div>
                    <h2 class="font-montserrat text-[28px] font-bold text-on-surface mb-4">Lokasi Kami</h2>
                    <p class="font-inter text-[15px] text-on-surface-variant mb-6 leading-relaxed">
                        Anda dapat mengambil kendaraan secara langsung di garasi kami. Klik pada daftar cabang di bawah ini untuk melihat detail lokasi di peta.
                    </p>

                    <div class="space-y-2 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                        @if(isset($officeZones) && $officeZones->count() > 0)
                            @foreach($officeZones as $office)
                                <div @click="focusMap({{ $office->latitude ?? 'null' }}, {{ $office->longitude ?? 'null' }}, {{ $office->id }})" 
                                     class="flex items-start gap-4 p-4 rounded-xl border transition-all duration-200 cursor-pointer group"
                                     :class="activeZone === {{ $office->id }} ? 'bg-primary/5 border-primary shadow-sm' : 'bg-surface border-outline-variant/30 hover:border-primary/50'">
                                    
                                    <div class="mt-1 flex-shrink-0">
                                        <span class="material-symbols-outlined text-[24px] transition-colors"
                                              :class="activeZone === {{ $office->id }} ? 'text-primary icon-fill' : 'text-on-surface-variant group-hover:text-primary'">
                                            location_on
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-montserrat font-bold text-[15px] transition-colors"
                                            :class="activeZone === {{ $office->id }} ? 'text-primary' : 'text-on-surface'">
                                            {{ $office->zone_name }}
                                        </h4>
                                        <p class="font-inter text-[13px] text-on-surface-variant mt-1 leading-relaxed">
                                            {{ $office->address ?? 'Alamat detail belum tersedia.' }}
                                        </p>
                                        @if(!$office->latitude || !$office->longitude)
                                            <p class="text-[10px] text-red-500 mt-1 italic">Koordinat belum diatur (Hubungi IT)</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-primary/5 border border-primary shadow-sm">
                                <span class="material-symbols-outlined text-primary mt-0.5 icon-fill">location_on</span>
                                <div>
                                    <h4 class="font-montserrat font-bold text-[15px] text-primary">Kantor Pusat KlikRental</h4>
                                    <p class="font-inter text-[14px] text-on-surface-variant mt-1">Belum ada data cabang kantor di database.</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3 p-4">
                            <span class="material-symbols-outlined text-primary mt-0.5">mail</span>
                            <div>
                                <h4 class="font-montserrat font-bold text-[15px] text-on-surface">Email</h4>
                                <p class="font-inter text-[14px] text-on-surface-variant mt-1">cs@klikrental.widihhh.my.id</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container untuk Leaflet Map -->
                <div class="bg-surface p-2 rounded-2xl border border-outline-variant/40 premium-shadow h-[400px] lg:sticky lg:top-24 z-10 relative">
                    <div id="main-map" class="w-full h-full rounded-xl z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="py-16 bg-surface border-t border-outline-variant/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-primary font-inter font-bold text-[13px] tracking-widest uppercase mb-2 block">Informasi Umum</span>
                <h2 class="font-montserrat text-[28px] md:text-[32px] font-bold text-on-surface mb-3">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            </div>

            <div x-data="{ activeTab: null }" class="space-y-4">
                
                <div class="bg-surface border border-outline-variant/40 rounded-2xl overflow-hidden transition-all duration-300 hover:border-primary/40 premium-shadow">
                    <button @click="activeTab = activeTab === 1 ? null : 1" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-montserrat font-bold text-[16px] text-on-surface">Apa saja syarat untuk sewa Lepas Kunci?</span>
                        <span class="material-symbols-outlined text-primary transition-transform duration-300" :class="{'rotate-180': activeTab === 1}">expand_more</span>
                    </button>
                    <div x-show="activeTab === 1" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1">
                            <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed">
                                Syarat sewa lepas kunci di KlikRental sangat mudah dan <strong>tanpa deposit uang!</strong> Anda hanya diwajibkan untuk menunjukkan <strong>SIM A yang masih aktif</strong> dan meninggalkan jaminan berupa <strong>KTP Asli</strong> saat Anda mengambil mobil.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant/40 rounded-2xl overflow-hidden transition-all duration-300 hover:border-primary/40 premium-shadow">
                    <button @click="activeTab = activeTab === 2 ? null : 2" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-montserrat font-bold text-[16px] text-on-surface">Bagaimana jika saya terlambat mengembalikan mobil?</span>
                        <span class="material-symbols-outlined text-primary transition-transform duration-300" :class="{'rotate-180': activeTab === 2}">expand_more</span>
                    </button>
                    <div x-show="activeTab === 2" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1">
                            <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed mb-3">
                                Kami memberikan masa tenggang (<i>grace period</i>) selama <strong>30 menit</strong> dari waktu pengembalian yang tertera di pesanan.
                            </p>
                            <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed">
                                Jika melewati batas toleransi tersebut, sistem kami secara otomatis akan menandai pesanan sebagai 'Late' dan Anda akan dikenakan denda keterlambatan (<i>overtime fee</i>) sebesar <strong>Rp 50.000 per jam</strong>. Rincian denda akan dikirimkan otomatis ke WhatsApp Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant/40 rounded-2xl overflow-hidden transition-all duration-300 hover:border-primary/40 premium-shadow">
                    <button @click="activeTab = activeTab === 3 ? null : 3" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-montserrat font-bold text-[16px] text-on-surface">Apakah bisa merubah jadwal atau membatalkan pesanan?</span>
                        <span class="material-symbols-outlined text-primary transition-transform duration-300" :class="{'rotate-180': activeTab === 3}">expand_more</span>
                    </button>
                    <div x-show="activeTab === 3" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1">
                            <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed">
                                Pesanan yang sudah dibayar tidak dapat dibatalkan secara sepihak. Namun, Anda dapat mengajukan <i>reschedule</i> jadwal sewa (maksimal H-1) dengan cara menghubungi Admin CS kami via WhatsApp. <i>Reschedule</i> bergantung pada ketersediaan armada.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-surface border border-outline-variant/40 rounded-2xl overflow-hidden transition-all duration-300 hover:border-primary/40 premium-shadow">
                    <button @click="activeTab = activeTab === 4 ? null : 4" class="w-full px-6 py-4 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-montserrat font-bold text-[16px] text-on-surface">Metode pembayaran apa saja yang diterima?</span>
                        <span class="material-symbols-outlined text-primary transition-transform duration-300" :class="{'rotate-180': activeTab === 4}">expand_more</span>
                    </button>
                    <div x-show="activeTab === 4" x-collapse x-cloak>
                        <div class="px-6 pb-5 pt-1">
                            <p class="font-inter text-[14px] text-on-surface-variant leading-relaxed">
                                Kami menggunakan sistem Payment Gateway Midtrans. Anda dapat membayar langsung di web secara <i>real-time</i> menggunakan berbagai metode, termasuk Transfer Bank (Virtual Account BCA, BNI, Mandiri, BRI) dan E-Wallet via QRIS (Gopay, OVO, Dana).
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SCRIPT ALPINE UNTUK MENGENDALIKAN LEAFLET -->
    <script>
        function leafletMap() {
            return {
                map: null,
                activeZone: null,
                initMap() {
                    // Tentukan koordinat pembatas (Bounding Box) KHUSUS KOTA SEMARANG
                    // (Batas kasar: Mijen/Gunungpati di Barat Daya, Genuk/Laut di Timur Laut)
                    const southWest = L.latLng(-7.1500, 110.2500); 
                    const northEast = L.latLng(-6.9000, 110.5500); 
                    const boundsSemarang = L.latLngBounds(southWest, northEast);

                    // Konfigurasi Peta dengan batasan
                    this.map = L.map('main-map', {
                        center: [-7.0247246, 110.3470241], // Titik tengah Semarang
                        zoom: 12,
                        minZoom: 11, // Mentok zoom-out seukuran kota Semarang
                        maxBounds: boundsSemarang, // Mengunci area geser hanya di Semarang
                        maxBoundsViscosity: 1.0 // Peta memantul saat nabrak batas
                    });
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);

                    const offices = @json($officeZones ?? []);
                    const bounds = [];

                    offices.forEach(office => {
                        if(office.latitude && office.longitude) {
                            const marker = L.marker([office.latitude, office.longitude]).addTo(this.map);
                            
                            marker.bindPopup(`
                                <div style="font-family: 'Montserrat', sans-serif;">
                                    <strong style="color: #140067; font-size: 14px;">${office.zone_name}</strong><br>
                                    <span style="font-size: 12px; color: #666;">${office.address || ''}</span>
                                </div>
                            `);
                            
                            bounds.push([office.latitude, office.longitude]);
                        }
                    });

                    // Hanya atur fitBounds jika batas cabang masih di dalam area
                    if(bounds.length > 1) {
                        this.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 16 });
                    } else if (bounds.length === 1) {
                        this.map.setView(bounds[0], 15);
                    }
                },
                focusMap(lat, lng, id) {
                    if(lat && lng) {
                        this.activeZone = id;
                        this.map.flyTo([lat, lng], 16, { animate: true, duration: 1.5 });
                    } else {
                        alert('Titik koordinat untuk cabang ini belum diatur oleh admin.');
                    }
                }
            }
        }
    </script>
</x-app-layout>