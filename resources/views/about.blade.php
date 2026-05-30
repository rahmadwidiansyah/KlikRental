<x-app-layout>
    <!-- HERO SECTION (Dioptimalkan ukurannya) -->
    <section class="relative bg-primary pt-8 pb-10 overflow-hidden">
        <!-- Abstract Background Ornaments -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                Mendigitalisasi <span class="text-white/90">Mobilitas Anda.</span>
            </h1>
            
            <p class="font-inter text-[14px] md:text-[15px] text-white/80 max-w-2xl mx-auto leading-relaxed mb-4">
                KlikRental hadir sebagai solusi sistem informasi manajemen rental kendaraan modern. Kami memastikan pengalaman sewa yang transparan, otomatis, dan 100% tanpa ribet.
            </p>

            <div class="flex flex-wrap justify-center gap-3 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">rocket_launch</span>
                    Berbasis di Semarang, Jawa Tengah
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI SECTION (Overlap tipis -mt-6) -->
    <section class="relative -mt-6 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Visi -->
            <div class="bg-surface rounded-2xl p-8 shadow-xl border border-outline-variant/30 premium-shadow flex flex-col justify-center transition-transform hover:-translate-y-1 duration-300">
                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-5 border border-primary/20">
                    <span class="material-symbols-outlined text-[32px]">visibility</span>
                </div>
                <h3 class="font-montserrat text-[22px] font-bold text-on-surface mb-3">Visi Kami</h3>
                <p class="font-inter text-[14.5px] text-on-surface-variant leading-relaxed">
                    Mendigitalisasi operasional UMKM rental kendaraan di Indonesia menjadi lebih modern, terstruktur, dan sepenuhnya terbebas dari kendala operasional konvensional maupun <i>human error</i>.
                </p>
            </div>
            
            <!-- Misi -->
            <div class="bg-surface rounded-2xl p-8 shadow-xl border border-outline-variant/30 premium-shadow flex flex-col justify-center transition-transform hover:-translate-y-1 duration-300">
                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-5 border border-primary/20">
                    <span class="material-symbols-outlined text-[32px]">flag</span>
                </div>
                <h3 class="font-montserrat text-[22px] font-bold text-on-surface mb-3">Misi Kami</h3>
                <p class="font-inter text-[14.5px] text-on-surface-variant leading-relaxed">
                    Memberikan pengalaman sewa mobil yang transparan, aman, dan nyaman melalui pemanfaatan teknologi <i>real-time availability</i>, pembayaran digital terintegrasi, serta notifikasi operasional pintar.
                </p>
            </div>
        </div>
    </section>

    <!-- KEUNGGULAN SISTEM -->
    <section class="py-16 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-montserrat text-[28px] md:text-[32px] font-bold text-on-surface mb-4">Mengapa Memilih Layanan Kami?</h2>
                <p class="font-inter text-[15px] text-on-surface-variant max-w-2xl mx-auto">
                    Lebih dari sekadar aplikasi penyewaan, KlikRental dibangun dengan infrastruktur yang memprioritaskan keamanan data dan kenyamanan pelanggan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Keunggulan 1 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-surface border border-outline-variant/40 rounded-full flex items-center justify-center text-primary mb-5 premium-shadow group-hover:scale-110 group-hover:border-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-[36px]">event_available</span>
                    </div>
                    <h4 class="font-montserrat font-bold text-[17px] text-on-surface mb-2">Anti Double-Booking</h4>
                    <p class="font-inter text-[13.5px] text-on-surface-variant leading-relaxed">Sistem sinkronisasi <i>real-time</i> kami mengunci ketersediaan armada, menjamin mobil pesanan Anda pasti tersedia.</p>
                </div>

                <!-- Keunggulan 2 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-surface border border-outline-variant/40 rounded-full flex items-center justify-center text-primary mb-5 premium-shadow group-hover:scale-110 group-hover:border-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-[36px]">receipt_long</span>
                    </div>
                    <h4 class="font-montserrat font-bold text-[17px] text-on-surface mb-2">Harga 100% Transparan</h4>
                    <p class="font-inter text-[13.5px] text-on-surface-variant leading-relaxed">Kalkulasi pintar untuk durasi, layanan supir, dan zona lokasi tanpa adanya biaya tersembunyi (<i>hidden fees</i>).</p>
                </div>

                <!-- Keunggulan 3 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-surface border border-outline-variant/40 rounded-full flex items-center justify-center text-primary mb-5 premium-shadow group-hover:scale-110 group-hover:border-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-[36px]">car_repair</span>
                    </div>
                    <h4 class="font-montserrat font-bold text-[17px] text-on-surface mb-2">Armada Prima & Terawat</h4>
                    <p class="font-inter text-[13.5px] text-on-surface-variant leading-relaxed">Setiap kendaraan melewati proses inspeksi ketat untuk menjamin kebersihan, performa mesin, dan kenyamanan Anda.</p>
                </div>

                <!-- Keunggulan 4 -->
                <div class="text-center group">
                    <div class="w-20 h-20 mx-auto bg-surface border border-outline-variant/40 rounded-full flex items-center justify-center text-primary mb-5 premium-shadow group-hover:scale-110 group-hover:border-primary/50 transition-all duration-300">
                        <span class="material-symbols-outlined text-[36px]">mark_email_unread</span>
                    </div>
                    <h4 class="font-montserrat font-bold text-[17px] text-on-surface mb-2">Asisten & Notifikasi Pintar</h4>
                    <p class="font-inter text-[13.5px] text-on-surface-variant leading-relaxed">Peringatan pengembalian otomatis via WhatsApp dan <i>login</i> instan menjadikan proses lebih praktis dari sebelumnya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TIM PENGEMBANG (KELOMPOK 6) -->
    <section class="py-16 bg-surface border-t border-outline-variant/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary font-inter font-bold text-[13px] tracking-widest uppercase mb-2 block">Di Balik Layar</span>
                <h2 class="font-montserrat text-[28px] md:text-[32px] font-bold text-on-surface mb-3">Tim Pengembang KlikRental</h2>
                <p class="font-inter text-[14.5px] text-on-surface-variant max-w-2xl mx-auto">
                    Sistem informasi ini dikembangkan dengan dedikasi tinggi oleh <strong>Kelompok 6</strong> sebagai bentuk implementasi teknologi modern di industri penyewaan.
                </p>
            </div>

            <!-- Dosen Pembimbing -->
            <div class="flex justify-center mb-10">
                <div class="bg-surface-container-lowest border border-primary/20 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-5 premium-shadow w-full max-w-lg hover:border-primary/50 transition-all">
                    <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center shrink-0 border border-primary/30 text-primary">
                        <span class="material-symbols-outlined text-[40px]">school</span>
                    </div>
                    <div class="text-center sm:text-left">
                        <span class="font-inter text-[11px] font-bold text-primary uppercase tracking-wider mb-1 block">Dosen Pengampu</span>
                        <h4 class="font-montserrat font-bold text-[18px] text-on-surface">Maya Utami Dewi, S.Kom, M.Kom</h4>
                        <p class="font-inter text-[13px] text-on-surface-variant mt-1">Membimbing pengembangan arsitektur sistem dan kesesuaian proyek.</p>
                    </div>
                </div>
            </div>

            <!-- Anggota Tim -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- PM -->
                <div class="bg-surface border border-outline-variant/40 rounded-2xl p-6 text-center premium-shadow hover:-translate-y-2 hover:border-primary/40 transition-all duration-300">
                    <div class="w-24 h-24 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4 border-4 border-surface shadow-md">
                        <img src="https://ui-avatars.com/api/?name=Rahmad+Widiansyah&background=e4dfff&color=140067&size=128" alt="Rahmad" class="w-full h-full rounded-full object-cover">
                    </div>
                    <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Rahmad Widiansyah</h4>
                    <span class="font-inter text-[12px] font-medium text-primary bg-primary/10 px-3 py-1 rounded-full mt-2 inline-block">Project Manager & SA</span>
                </div>

                <!-- Frontend -->
                <div class="bg-surface border border-outline-variant/40 rounded-2xl p-6 text-center premium-shadow hover:-translate-y-2 hover:border-primary/40 transition-all duration-300">
                    <div class="w-24 h-24 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4 border-4 border-surface shadow-md">
                        <img src="https://ui-avatars.com/api/?name=Ilham+Puji&background=e4dfff&color=140067&size=128" alt="Ilham" class="w-full h-full rounded-full object-cover">
                    </div>
                    <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Ilham Puji Wira P.</h4>
                    <span class="font-inter text-[12px] font-medium text-secondary bg-secondary/10 px-3 py-1 rounded-full mt-2 inline-block">Frontend Developer</span>
                </div>

                <!-- UI/UX -->
                <div class="bg-surface border border-outline-variant/40 rounded-2xl p-6 text-center premium-shadow hover:-translate-y-2 hover:border-primary/40 transition-all duration-300">
                    <div class="w-24 h-24 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4 border-4 border-surface shadow-md">
                        <img src="https://ui-avatars.com/api/?name=Iqbal+Hamdani&background=e4dfff&color=140067&size=128" alt="Iqbal" class="w-full h-full rounded-full object-cover">
                    </div>
                    <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Iqbal Hamdani</h4>
                    <span class="font-inter text-[12px] font-medium text-pink-600 bg-pink-50 px-3 py-1 rounded-full mt-2 inline-block">UI/UX Designer</span>
                </div>

                <!-- Backend -->
                <div class="bg-surface border border-outline-variant/40 rounded-2xl p-6 text-center premium-shadow hover:-translate-y-2 hover:border-primary/40 transition-all duration-300">
                    <div class="w-24 h-24 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-4 border-4 border-surface shadow-md">
                        <img src="https://ui-avatars.com/api/?name=Fengki+Andriansyah&background=e4dfff&color=140067&size=128" alt="Fengki" class="w-full h-full rounded-full object-cover">
                    </div>
                    <h4 class="font-montserrat font-bold text-[16px] text-on-surface">Fengki Andriansyah</h4>
                    <span class="font-inter text-[12px] font-medium text-forest-green bg-forest-light px-3 py-1 rounded-full mt-2 inline-block">Backend Developer</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="py-16 bg-primary relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="font-montserrat text-[28px] md:text-[36px] font-bold text-white mb-4">Siap Memulai Perjalanan Anda?</h2>
            <p class="font-inter text-[15px] md:text-[16px] text-white/80 mb-8 max-w-2xl mx-auto">
                Jangan biarkan prosedur yang rumit menghambat rencana Anda. Temukan kendaraan impian Anda sekarang dan rasakan pengalaman menyewa mobil masa depan.
            </p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-white text-primary font-inter font-bold text-[15px] px-8 py-3.5 rounded-xl hover:bg-surface-container hover:-translate-y-1 transition-all duration-300 shadow-xl shadow-black/20">
                Lihat Katalog Armada <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>
    </section>
</x-app-layout>