<x-app-layout>
    <!-- HERO SECTION (Dioptimalkan ukurannya) -->
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

    <!-- CONTENT SECTION (Overlap tipis -mt-6) -->
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
                <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 mb-4 border border-orange-200">
                    <span class="material-symbols-outlined text-[32px]">schedule</span>
                </div>
                <h3 class="font-montserrat text-[18px] font-bold text-on-surface mb-2">Jam Operasional</h3>
                <p class="font-inter text-[14px] text-on-surface-variant">Setiap Hari (Senin - Minggu)</p>
                <p class="font-inter text-[15px] font-bold text-primary mt-1">08:00 - 22:00 WIB</p>
            </div>
        </div>
    </section>

    <section class="py-12 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="font-montserrat text-[28px] font-bold text-on-surface mb-4">Lokasi Kami</h2>
                    <p class="font-inter text-[15px] text-on-surface-variant mb-6 leading-relaxed">
                        Anda dapat mengambil kendaraan secara langsung di garasi kami atau menggunakan layanan antar-jemput zona yang telah disediakan pada saat proses <i>booking</i>.
                    </p>
                    <div class="flex items-start gap-3 mb-4">
                        <span class="material-symbols-outlined text-primary mt-0.5">location_on</span>
                        <div>
                            <h4 class="font-montserrat font-bold text-[15px] text-on-surface">Kantor Pusat KlikRental</h4>
                            <p class="font-inter text-[14px] text-on-surface-variant mt-1">Semarang, Jawa Tengah, Indonesia</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-0.5">mail</span>
                        <div>
                            <h4 class="font-montserrat font-bold text-[15px] text-on-surface">Email</h4>
                            <p class="font-inter text-[14px] text-on-surface-variant mt-1">cs@klikrental.widihhh.my.id</p>
                        </div>
                    </div>
                </div>
                <div class="bg-surface p-2 rounded-2xl border border-outline-variant/40 premium-shadow overflow-hidden h-[300px]">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126715.7941094033!2d110.3470241972656!3d-7.0247246!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b4d3f0d024d%3A0x1e0432b9da5cb9f2!2sSemarang%2C%20Semarang%20City%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0; border-radius: 0.75rem;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

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
</x-app-layout>