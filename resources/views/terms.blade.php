<x-app-layout>
    <!-- HERO SECTION (Dioptimalkan ukurannya) -->
    <section class="relative bg-primary pt-8 pb-10 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/10 rounded-full blur-2xl -mb-10 -mr-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05] pointer-events-none"></div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            
            <h1 class="font-montserrat text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight leading-tight">
                Syarat & <span class="text-white/80">Ketentuan</span>
            </h1>
            
            <p class="font-inter text-[14px] md:text-[15px] text-white/80 max-w-2xl mx-auto leading-relaxed mb-4">
                Aturan main penyewaan kendaraan di KlikRental. Harap baca dengan saksama sebelum melakukan transaksi untuk kenyamanan bersama.
            </p>

            <div class="flex flex-wrap justify-center gap-3 w-full max-w-3xl">
                <div class="flex items-center gap-1.5 bg-white/10 px-4 py-1.5 rounded-full text-white font-inter text-[12px] font-semibold border border-white/20 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">gavel</span>
                    Kesepakatan Layanan
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
                    Syarat dan Ketentuan berikut mengatur penggunaan layanan penyewaan kendaraan yang disediakan oleh <strong>Tim Pengembang Kelompok 6 (KlikRental)</strong>. Dengan melakukan pemesanan dan pembayaran di platform kami, Anda (Penyewa) dianggap telah membaca, menyetujui, dan tunduk pada seluruh ketentuan di bawah ini.
                </p>
            </div>

            <div class="space-y-8">
                <!-- Poin 1 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">id_card</span>
                        1. Persyaratan Umum & Dokumen
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Penyewa diwajibkan berusia minimal 18 tahun dan cakap secara hukum.</li>
                        <li>Untuk layanan <strong>Sewa Lepas Kunci (Tanpa Supir)</strong>, Penyewa wajib menunjukkan <strong>SIM A yang masih berlaku</strong> dan menyerahkan <strong>KTP Asli</strong> sebagai jaminan fisik selama masa sewa.</li>
                        <li>KlikRental berhak menolak atau membatalkan pesanan di tempat jika dokumen identitas yang ditunjukkan tidak sesuai, kedaluwarsa, atau terindikasi palsu tanpa pengembalian dana.</li>
                    </ul>
                </div>

                <!-- Poin 2 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">credit_card_off</span>
                        2. Kebijakan Pembayaran, Batal, & Reschedule
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Pembayaran wajib dilakukan lunas di muka melalui <i>Payment Gateway</i> (Midtrans) yang tersedia di platform.</li>
                        <li><strong>Tidak Ada Pengembalian Dana (No Refund):</strong> Pesanan yang sudah dibayar <u>tidak dapat dibatalkan secara sepihak</u> oleh Penyewa dengan alasan apa pun.</li>
                        <li><strong>Reschedule:</strong> Perubahan jadwal sewa hanya dapat dilakukan maksimal <strong>H-1 sebelum waktu penjemputan</strong> dengan menghubungi Customer Service. Reschedule sangat bergantung pada ketersediaan armada dan tidak dapat diubah ke armada dengan kelas yang lebih rendah.</li>
                    </ul>
                </div>

                <!-- Poin 3 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">timer</span>
                        3. Kebijakan Waktu & Denda Keterlambatan
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Masa sewa dihitung berdasarkan waktu yang tertera pada sistem pemesanan.</li>
                        <li>KlikRental memberikan <strong>masa tenggang (grace period) selama 30 menit</strong> dari jadwal pengembalian.</li>
                        <li>Jika Penyewa mengembalikan kendaraan melewati masa tenggang tersebut, sistem akan secara otomatis menandai status <strong>LATE</strong> dan memberlakukan denda keterlambatan sebesar <strong>Rp 50.000,- per jam</strong>.</li>
                        <li>Jika Penyewa ingin memperpanjang masa sewa, Penyewa wajib menginformasikan kepada Admin maksimal 3 jam sebelum masa sewa berakhir, dan persetujuan bergantung pada ketersediaan mobil.</li>
                    </ul>
                </div>

                <!-- Poin 4 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">car_crash</span>
                        4. Kerusakan, Kehilangan, & Kebersihan
                    </h2>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Penyewa bertanggung jawab penuh atas keamanan dan keutuhan kendaraan selama masa sewa.</li>
                        <li>Setiap kerusakan fisik pada kendaraan (lecet, penyok, kaca pecah, dsb) akibat kelalaian Penyewa akan dikenakan <strong>biaya perbaikan</strong> sesuai estimasi dari bengkel resmi rekanan KlikRental, ditambah biaya kompensasi mobil menganggur selama masa perbaikan.</li>
                        <li>Penyewa dilarang meninggalkan noda permanen, bau menyengat (seperti asap rokok, durian), atau kotoran ekstrem. Pelanggaran kebersihan akan dikenakan biaya <i>detailing/salon mobil</i> mulai dari Rp 150.000,-.</li>
                        <li>Kehilangan kendaraan atau aksesori bawaan kendaraan menjadi tanggung jawab mutlak Penyewa dan akan diproses secara hukum pidana maupun perdata.</li>
                    </ul>
                </div>

                <!-- Poin 5 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">do_not_disturb_on</span>
                        5. Batasan Penggunaan Kendaraan
                    </h2>
                    <p class="text-[15px] mb-2">Penyewa dengan tegas <strong>DILARANG</strong> menggunakan kendaraan untuk:</p>
                    <ul class="list-disc pl-5 space-y-2 text-[14.5px]">
                        <li>Tindak kejahatan, pengangkutan barang terlarang/ilegal, atau kegiatan yang bertentangan dengan hukum Negara Kesatuan Republik Indonesia.</li>
                        <li>Balapan liar, <i>off-road</i> ekstrim (di luar batas wajar spesifikasi mobil), atau kegiatan *motorsport* lainnya.</li>
                        <li>Menyewakan, menggadaikan, atau memindahtangankan kendaraan kepada pihak ketiga mana pun tanpa izin tertulis dari KlikRental.</li>
                    </ul>
                </div>

                <!-- Poin 6 -->
                <div>
                    <h2 class="font-montserrat text-[20px] font-bold text-on-surface mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">gavel</span>
                        6. Penyelesaian Sengketa
                    </h2>
                    <p class="text-[15px]">
                        Segala bentuk perselisihan yang timbul akibat pelaksanaan perjanjian ini akan diselesaikan secara musyawarah untuk mufakat. Jika tidak mencapai titik temu, kedua belah pihak sepakat untuk menyelesaikannya sesuai dengan hukum yang berlaku di Indonesia melalui yurisdiksi Pengadilan Negeri di wilayah Kota Semarang.
                    </p>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-outline-variant/30 text-center">
                <p class="text-[13px] text-on-surface-variant/70 italic font-semibold">
                    Syarat dan Ketentuan ini dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu tersedia di halaman ini.
                </p>
            </div>

        </div>
    </section>
</x-app-layout>