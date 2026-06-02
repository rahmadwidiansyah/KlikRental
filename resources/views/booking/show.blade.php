<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-10">
        <!-- Main Card Invoice -->
        <div class="bg-surface border border-outline-variant/60 rounded-2xl overflow-hidden premium-shadow p-6 md:p-8">
            
            <!-- Header Invoice -->
            <div class="text-center border-b border-outline-variant/40 pb-6 mb-6">
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-[26px]">receipt</span>
                </div>
                <h1 class="font-montserrat text-[20px] md:text-[22px] font-bold text-on-surface tracking-wide">INVOICE PEMBAYARAN</h1>
                <p class="font-inter text-[13px] text-on-surface-variant mt-1">Kode Booking: <span class="font-mono font-bold text-primary">{{ $booking->booking_code }}</span></p>
                
                <div class="mt-3">
                    @if($booking->status == 'pending')
                        <span class="bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full font-inter font-bold text-[11px] inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ strtoupper($booking->status) }}
                        </span>
                    @elseif($booking->status == 'cancelled')
                        <span class="bg-red-100 text-red-700 border border-red-200 px-3 py-1 rounded-full font-inter font-bold text-[11px] inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> {{ strtoupper($booking->status) }}
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full font-inter font-bold text-[11px] inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> {{ strtoupper($booking->status) }}
                        </span>
                    @endif
                </div>

                @if($booking->status == 'pending')
                <div class="mt-6 p-4 bg-surface-container border border-outline-variant/60 rounded-xl inline-block w-full max-w-sm">
                    <p class="font-inter text-[12px] text-on-surface-variant mb-2 uppercase tracking-widest font-bold">Batas Waktu Pembayaran</p>
                    <div id="countdown" class="font-mono text-2xl font-bold text-primary flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined animate-spin text-[24px]">schedule</span>
                        -- : -- : --
                    </div>
                    <p class="font-inter text-[11px] text-on-surface-variant/70 mt-2 italic">Pesanan otomatis batal jika tidak dibayar dalam 60 menit.</p>
                </div>
                @endif
            </div>

            <!-- Detail Perjalanan -->
            <div class="mb-8">
                <h2 class="font-montserrat font-bold text-[15px] text-on-surface mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[18px]">commute</span>
                    Informasi Perjalanan
                </h2>
                
                <div class="bg-surface-container/50 border border-outline-variant/40 rounded-xl p-4 font-inter text-[13px] md:text-[14px] space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Mobil</span>
                        <span class="col-span-2 text-on-surface font-semibold">: {{ $booking->vehicle->name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Jadwal Ambil</span>
                        <span class="col-span-2 text-on-surface font-medium">: {{ \Carbon\Carbon::parse($booking->start_date)->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Jadwal Kembali</span>
                        <span class="col-span-2 text-on-surface font-medium">: {{ \Carbon\Carbon::parse($booking->end_date)->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Lokasi Jemput</span>
                        <span class="col-span-2 text-on-surface">: {{ $booking->pickupZone->zone_name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Lokasi Kembali</span>
                        <span class="col-span-2 text-on-surface">: {{ $booking->dropoffZone->zone_name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-on-surface-variant font-medium">Supir</span>
                        <span class="col-span-2 text-on-surface">: {{ $booking->driver ? $booking->driver->name : 'Tanpa Supir (Lepas Kunci)' }}</span>
                    </div>
                </div>
            </div>

            <!-- Rincian Biaya -->
            <div class="mb-6">
                <h2 class="font-montserrat font-bold text-[15px] text-on-surface mb-4 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-primary text-[18px]">payments</span>
                    Rincian Biaya
                </h2>

                <div class="border border-outline-variant/50 rounded-xl overflow-hidden font-inter text-[13px] md:text-[14px]">
                    <div class="bg-surface-container border-b border-outline-variant/50 px-4 py-2.5 grid grid-cols-3 text-on-surface-variant font-semibold">
                        <div class="col-span-2">Deskripsi</div>
                        <div class="text-right">Jumlah</div>
                    </div>
                    
                    @php
                        $start = \Carbon\Carbon::parse($booking->start_date);
                        $end = \Carbon\Carbon::parse($booking->end_date);
                        $durationDays = ceil($start->diffInHours($end) / 24) ?: 1;
                    @endphp

                    <div class="divide-y divide-outline-variant/30 text-on-surface bg-surface px-4">
                        <div class="grid grid-cols-3 py-3">
                            <div class="col-span-2 text-on-surface-variant">Sewa Mobil ({{ $durationDays }} Hari)</div>
                            <div class="text-right font-medium">Rp {{ number_format($booking->vehicle->price_per_day * $durationDays, 0, ',', '.') }}</div>
                        </div>
                        
                        @if($booking->driver)
                        <div class="grid grid-cols-3 py-3">
                            <div class="col-span-2 text-on-surface-variant">Jasa Supir ({{ $durationDays }} Hari)</div>
                            <div class="text-right font-medium">Rp {{ number_format($booking->driver->daily_rate * $durationDays, 0, ',', '.') }}</div>
                        </div>
                        @endif

                        <div class="grid grid-cols-3 py-3">
                            <div class="col-span-2 text-on-surface-variant">Biaya Titik Jemput</div>
                            <div class="text-right font-medium">Rp {{ number_format($booking->pickupZone->additional_cost, 0, ',', '.') }}</div>
                        </div>
                        <div class="grid grid-cols-3 py-3">
                            <div class="col-span-2 text-on-surface-variant">Biaya Titik Kembali</div>
                            <div class="text-right font-medium">Rp {{ number_format($booking->dropoffZone->additional_cost, 0, ',', '.') }}</div>
                        </div>

                        <!-- Rumus Perhitungan PPN & Diskon -->
                        @php
                            $subtotal = ($booking->vehicle->price_per_day * $durationDays) + 
                                        ($booking->driver ? $booking->driver->daily_rate * $durationDays : 0) + 
                                        $booking->pickupZone->additional_cost + 
                                        $booking->dropoffZone->additional_cost;
                            
                            // Ekstrak harga sebelum PPN (Total Pembayaran dibagi 1.11)
                            $baseTotal = round($booking->total_price / 1.11);
                            
                            // Pajak 11%
                            $tax = $booking->total_price - $baseTotal;
                            
                            // Diskon = Subtotal Murni dikurangi Harga Sebelum PPN
                            $discounted = $subtotal - $baseTotal;
                        @endphp

                        @if($booking->promo && $discounted > 0)
                        <div class="grid grid-cols-3 py-3 text-green-700 font-semibold">
                            <div class="col-span-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">local_offer</span>
                                Diskon Promo ({{ $booking->promo->code }})
                            </div>
                            <div class="text-right">- Rp {{ number_format($discounted, 0, ',', '.') }}</div>
                        </div>
                        @endif

                        <!-- TAMBAHAN BARIS PPN 11% -->
                        <div class="grid grid-cols-3 py-3">
                            <div class="col-span-2 text-on-surface-variant font-medium">Pajak (PPN 11%)</div>
                            <div class="text-right font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- Total Block -->

                    <!-- Total Block -->
                    <div class="bg-surface-container border-t border-outline-variant/50 px-4 py-4 grid grid-cols-3 items-center">
                        <div class="col-span-2 font-montserrat font-bold text-[14px] text-on-surface">TOTAL PEMBAYARAN</div>
                        <div class="text-right font-montserrat font-bold text-[18px] text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Gabungan -->
            <div class="mt-8 pt-4 border-t border-outline-variant/30 text-center">
                @if($booking->status == 'pending')
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        
                        <button type="button" id="pay-button" class="w-full sm:w-auto bg-green-600 text-white font-inter font-bold text-[15px] px-8 py-3.5 rounded-xl hover:bg-green-700 transition-all active:scale-95 border border-transparent inline-flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                            Bayar Sekarang
                        </button>

                        <form action="{{ route('booking.cancel', $booking->booking_code) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-red-50 text-red-600 font-inter font-bold text-[15px] px-8 py-3.5 rounded-xl hover:bg-red-100 transition-all active:scale-95 border border-red-200 inline-flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                                Batalkan
                            </button>
                        </form>
                        
                    </div>

                    <p class="text-[11px] text-on-surface-variant/70 mt-4 flex items-center justify-center gap-1 font-inter">
                        <span class="material-symbols-outlined text-[14px]">lock</span>
                        Sistem pembayaran aman dan terintegrasi otomatis dengan Midtrans
                    </p>

                @elseif($booking->status == 'cancelled')
                    <button disabled class="w-full sm:w-auto bg-red-50 text-red-500 font-inter font-bold text-[15px] px-8 py-3.5 rounded-xl cursor-not-allowed inline-flex items-center justify-center gap-2 border border-red-200">
                        <span class="material-symbols-outlined text-[20px]">block</span>
                        Dibatalkan
                    </button>
                    
                @else
                    <button disabled class="w-full sm:w-auto bg-gray-100 text-gray-500 font-inter font-bold text-[15px] px-8 py-3.5 rounded-xl cursor-not-allowed inline-flex items-center justify-center gap-2 border border-gray-200">
                        <span class="material-symbols-outlined text-[20px] text-green-600 icon-fill">check_circle</span>
                        Lunas
                    </button>
                @endif
            </div>

        </div>
    </div>

    <!-- SCRIPT MIDTRANS INTEGRATED -->
    @if($booking->status == 'pending' && isset($snapToken))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    alert('Pembayaran sukses!');
                    window.location.href = "{{ route('booking.index') }}"; 
                },
                onPending: function (result) {
                    alert('Menunggu pembayaran!');
                    window.location.reload();
                },
                onError: function (result) {
                    alert('Pembayaran gagal!');
                },
                onClose: function () {
                    alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>

    <script>
        // Countdown Timer Logic
        (function() {
            const createdAt = new Date("{{ $booking->created_at->toIso8601String() }}").getTime();
            const expiryTime = createdAt + (60 * 60 * 1000); // 1 Jam
            const countdownEl = document.getElementById('countdown');

            if (!countdownEl) return;

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = expiryTime - now;

                if (distance < 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "<span class='text-red-500 font-bold'>WAKTU HABIS</span>";
                    countdownEl.classList.add('text-red-500');
                    
                    // Auto-reload setelah 2 detik untuk sinkronisasi status cancelled dari backend
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const displayHours = String(hours).padStart(2, '0');
                const displayMinutes = String(minutes).padStart(2, '0');
                const displaySeconds = String(seconds).padStart(2, '0');

                countdownEl.innerHTML = `
                    <span class="material-symbols-outlined text-[24px]">schedule</span>
                    ${displayHours}:${displayMinutes}:${displaySeconds}`;
            }, 1000);
        })();
    </script>
    @endif
</x-app-layout>