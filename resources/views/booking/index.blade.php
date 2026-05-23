<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="font-montserrat text-[24px] font-bold text-on-surface leading-tight">Riwayat Pesanan Saya</h1>
            <p class="font-inter text-[14px] text-on-surface-variant mt-1">Pantau status pemesanan dan kelola administrasi sewa Anda di sini.</p>
        </div>

        <!-- Success Session Toast/Banner -->
        @if(session('success'))
        <div class="mb-6 bg-forest-light text-forest-green border border-forest-green/20 p-4 rounded-xl font-inter flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-[20px] icon-fill text-forest-green">check_circle</span>
            <p class="text-[14px] font-semibold">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Table Container Modern -->
        <div class="w-full bg-surface border border-outline-variant/60 rounded-xl overflow-hidden premium-shadow">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left font-inter text-[14px]">
                    <thead>
                        <tr class="bg-surface-container border-b border-outline-variant/50 text-on-surface-variant font-semibold text-[13px]">
                            <th class="px-6 py-4">Kode Booking</th>
                            <th class="px-6 py-4">Mobil</th>
                            <th class="px-6 py-4">Jadwal Sewa</th>
                            <th class="px-6 py-4">Total Harga</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30 text-on-surface">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-primary text-[13px]">
                                {{ $booking->booking_code }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-montserrat font-bold text-on-surface text-[14px] block">{{ $booking->vehicle->name }}</span>
                                <span class="text-[12px] text-on-surface-variant/80">{{ $booking->vehicle->type }}</span>
                            </td>
                            <td class="px-6 py-4 text-[13px] leading-relaxed text-on-surface-variant">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-primary">play_circle</span>
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y H:i') }}
                                </div>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[15px] text-error/80">stop_circle</span>
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-montserrat font-bold text-on-surface">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status == 'pending')
                                <span class="bg-amber-50 text-amber-700 border border-amber-200/60 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> {{ strtoupper($booking->status) }}
                                </span>
                                @else
                                <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-forest-green"></span> {{ strtoupper($booking->status) }}
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($booking->status == 'pending')
                                <a href="{{ route('booking.show', $booking->booking_code) }}" class="inline-block">
                                    <button type="button" class="bg-primary text-white font-semibold text-[12px] py-1.5 px-4 rounded-lg hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/10 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">payments</span>
                                        Detail / Bayar
                                    </button>
                                </a>
                                @else
                                <a href="{{ route('booking.show', $booking->booking_code) }}" class="font-semibold text-[13px] text-secondary hover:text-primary transition-colors inline-flex items-center gap-0.5">
                                    Lihat Detail
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant/70 italic bg-surface-container-lowest/50">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant/30 block mb-2">calendar_today</span>
                                Anda belum memiliki riwayat pesanan kendaraan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>