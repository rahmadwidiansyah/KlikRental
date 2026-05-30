<x-app-layout>
    <!-- HERO SECTION (Dioptimalkan ukurannya) -->
    <section class="relative bg-primary pt-8 pb-10 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                Kebijakan <span class="text-white/80">Privasi</span>
            </h1>
            
            <p class="font-inter text-[14px] md:text-[15px] text-white/80 max-w-2xl mx-auto leading-relaxed mb-4">
                Kepercayaan Anda adalah prioritas kami. Pelajari bagaimana KlikRental mengumpulkan, menggunakan, dan melindungi data pribadi Anda.
            </p>

            <div class="flex flex-wrap justify-center gap-3 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">shield_lock</span>
                    Keamanan Data Terjamin
                </div>
            </div>

        </div>
    </section>

    <!-- CONTENT SECTION (Overlap tipis -mt-6) -->
    <section class="relative -mt-6 z-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="bg-surface rounded-2xl p-6 md:p-10 shadow-xl border border-outline-variant/30 premium-shadow text-on-surface-variant font-inter leading-relaxed">
            
            <div class="mb-8 border-b border-outline-variant/30 pb-6">
                <p class="text-[14px]"><strong>Terakhir Diperbarui:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="mt-4 text-[15px]">
                    Selamat datang di KlikRental. Kebijakan Privasi ini menjelaskan bagaimana <strong>Tim Pengembang Kelompok 6</strong> ("kami", "milik kami", atau "KlikRental") mengumpulkan, menggunakan, membagikan, dan melindungi informasi pribadi Anda saat Anda menggunakan situs web dan layanan kami. Kebijakan ini disusun dengan mematuhi Undang-Undang Pelindungan Data Pribadi (UU PDP) yang berlaku di Indonesia.
                </p>
            </div>

            <div class="space-y-8">
                <!-- Poin 1 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">data_usage</span>
                        1. Informasi yang Kami Kumpulkan
                    </h2>
                    <p class="text-[15px] mb-3">Kami mengumpulkan beberapa jenis informasi untuk memberikan layanan penyewaan yang aman dan efisien:</p>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li><strong>Informasi Akun:</strong> Nama lengkap, alamat email (termasuk dari otentikasi Google), dan nomor telepon (WhatsApp).</li>
                        <li><strong>Data Verifikasi Identitas:</strong> Untuk penyewaan "Lepas Kunci", kami mewajibkan jaminan berupa dokumen fisik (KTP Asli) dan verifikasi Surat Izin Mengemudi (SIM A) saat serah terima kendaraan.</li>
                        <li><strong>Data Transaksi:</strong> Riwayat pemesanan, titik jemput/kembali, dan total pembayaran. (Catatan: Kami tidak menyimpan data kartu kredit Anda; pemrosesan pembayaran dilakukan dengan aman oleh Midtrans).</li>
                    </ul>
                </div>

                <!-- Poin 2 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">manage_accounts</span>
                        2. Bagaimana Kami Menggunakan Informasi Anda
                    </h2>
                    <p class="text-[15px] mb-3">Informasi yang kami kumpulkan digunakan secara spesifik untuk:</p>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Memproses pemesanan, verifikasi ketersediaan armada, dan administrasi penyewaan.</li>
                        <li>Mengirimkan notifikasi operasional (contoh: konfirmasi pesanan, pengingat pengembalian, dan tagihan denda keterlambatan via WhatsApp).</li>
                        <li>Meningkatkan keamanan layanan dan mencegah tindak penipuan, penggelapan, atau pencurian kendaraan.</li>
                        <li><strong>Keperluan Pemasaran:</strong> Mengirimkan informasi terkait penawaran spesial, promosi, dan diskon KlikRental. Anda dapat memilih untuk berhenti berlangganan (opt-out) dari pesan promosi kapan saja.</li>
                    </ul>
                </div>

                <!-- Poin 3 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">share</span>
                        3. Pembagian Data kepada Pihak Ketiga
                    </h2>
                    <p class="text-[15px] mb-3">Kami tidak pernah menjual data pribadi Anda. Data Anda hanya dibagikan kepada pihak ketiga yang terpercaya dan terikat kontrak kerahasiaan khusus untuk keperluan operasional sistem:</p>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li><strong>Payment Gateway (Midtrans):</strong> Untuk memproses pembayaran digital secara aman dan terenkripsi.</li>
                        <li><strong>Penyedia Layanan API WhatsApp:</strong> Untuk mengirimkan notifikasi sistem dan tagihan otomatis.</li>
                        <li><strong>Otoritas Hukum:</strong> Kami berhak menyerahkan data identitas (KTP/SIM) dan log transaksi kepada pihak berwajib jika terjadi pelanggaran hukum, kecelakaan lalu lintas, atau indikasi penggelapan aset rental.</li>
                    </ul>
                </div>

                <!-- Poin 4 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">security</span>
                        4. Keamanan Data Identitas & Jaminan
                    </h2>
                    <p class="text-[15px]">
                        Kami memahami bahwa dokumen seperti KTP dan SIM adalah informasi yang sangat sensitif. Jaminan fisik (KTP) akan disimpan secara aman di loker kantor kami selama masa sewa dan akan segera dikembalikan saat kendaraan dikembalikan dalam kondisi baik. Data digital di server kami dilindungi dengan enkripsi berlapis standar industri.
                    </p>
                </div>

                <!-- Poin 5 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">delete_forever</span>
                        5. Hak Anda & Penghapusan Data (Right to Erasure)
                    </h2>
                    <p class="text-[15px] mb-3">
                        Sesuai dengan UU PDP, Anda memiliki kendali penuh atas data pribadi Anda. Jika Anda tidak lagi ingin menggunakan layanan KlikRental, Anda memiliki hak untuk meminta <strong>penghapusan akun dan seluruh data terkait secara permanen</strong>.
                    </p>
                    <p class="text-[15px]">
                        Proses penghapusan mencakup riwayat pemesanan dan seluruh data verifikasi identitas di sistem kami. Permintaan penghapusan dapat diajukan dengan menghubungi layanan Pelanggan (CS) kami. <em>Catatan: Kami mungkin perlu menahan data tertentu untuk waktu yang wajar jika ada sengketa transaksi atau kendala hukum yang belum terselesaikan.</em>
                    </p>
                </div>

                <!-- Poin 6 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">contact_support</span>
                        6. Hubungi Kami
                    </h2>
                    <p class="text-[15px]">
                        Jika Anda memiliki pertanyaan, kekhawatiran, atau keluhan mengenai Kebijakan Privasi ini, silakan hubungi Tim Pengembang Kelompok 6 melalui:
                    </p>
                    <div class="mt-3 bg-surface-container-lowest border border-outline-variant/50 p-4 rounded-xl">
                        <p class="text-[14px]"><strong>Email:</strong> cs@klikrental.widihhh.my.id</p>
                        <p class="text-[14px] mt-1"><strong>WhatsApp:</strong> 0851-5664-2834</p>
                        <p class="text-[14px] mt-1"><strong>Alamat:</strong> Semarang, Jawa Tengah, Indonesia</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-outline-variant/30 text-center">
                <p class="text-[13px] text-on-surface-variant/70 italic">
                    Dengan menggunakan layanan KlikRental, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui Kebijakan Privasi ini.
                </p>
            </div>

        </div>
    </section>
</x-app-layout>