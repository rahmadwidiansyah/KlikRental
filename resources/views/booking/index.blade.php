<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-6">
            <h1 class="font-montserrat text-[24px] font-bold text-on-surface leading-tight">Riwayat Pesanan Saya</h1>
            <p class="font-inter text-[14px] text-on-surface-variant mt-1">Pantau status pemesanan dan kelola administrasi sewa Anda di sini.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-forest-light text-forest-green border border-forest-green/20 p-4 rounded-xl font-inter flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-[20px] icon-fill text-forest-green">check_circle</span>
            <p class="text-[14px] font-semibold">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-error/10 text-error border border-error/20 p-4 rounded-xl font-inter flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-[20px] icon-fill text-error">error</span>
            <p class="text-[14px] font-semibold">{{ session('error') }}</p>
        </div>
        @endif

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
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> PENDING
                                    </span>
                                @elseif($booking->status == 'in_use')
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200/60 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> IN USE
                                    </span>
                                @elseif($booking->status == 'late')
                                    <span class="bg-red-50 text-red-700 border border-red-200/60 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> LATE
                                    </span>
                                @elseif($booking->status == 'completed')
                                    <span class="bg-forest-light text-forest-green border border-forest-green/20 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-forest-green"></span> COMPLETED
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 border border-gray-300/60 px-2.5 py-1 rounded-full font-semibold text-[11px] inline-flex items-center gap-1">
                                        {{ strtoupper($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                @if($booking->status == 'pending')
                                    <a href="{{ route('booking.show', $booking->booking_code) }}" class="inline-block">
                                        <button type="button" class="bg-primary text-white font-semibold text-[12px] py-1.5 px-4 rounded-lg hover:bg-primary/90 transition-all active:scale-95 shadow-md shadow-primary/10 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">payments</span>
                                            Detail / Bayar
                                        </button>
                                    </a>
                                @elseif($booking->status == 'completed')
                                    @if(!$booking->review)
                                        <button type="button" 
                                                x-data=""
                                                x-on:click="$dispatch('open-review-modal', { 
                                                    code: '{{ $booking->booking_code }}', 
                                                    vehicle: '{{ addslashes($booking->vehicle->name) }}',
                                                    hasDriver: {{ $booking->driver_id ? 'true' : 'false' }},
                                                    driverName: '{{ $booking->driver_id ? addslashes($booking->driver->name) : '' }}'
                                                })"
                                                class="bg-amber-500 text-white font-semibold text-[12px] py-1.5 px-3 rounded-lg hover:bg-amber-600 transition-all shadow-md flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px] icon-fill">star</span> Rate
                                        </button>
                                    @else
                                        <span class="text-forest-green font-semibold text-[12px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px] icon-fill">check_circle</span> Diulas
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('booking.show', $booking->booking_code) }}" class="font-semibold text-[13px] text-secondary hover:text-primary transition-colors inline-flex items-center gap-0.5 ml-2">
                                        Detail <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
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

    <div x-data="{ 
            show: false, 
            code: '', 
            vehicle: '', 
            hasDriver: false, 
            driverName: '',
            formAction: ''
         }" 
         @open-review-modal.window="
            show = true; 
            code = $event.detail.code; 
            vehicle = $event.detail.vehicle; 
            hasDriver = $event.detail.hasDriver; 
            driverName = $event.detail.driverName;
            formAction = '/booking/' + code + '/review';
         "
         x-show="show" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
        
        <div x-show="show" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="show = false"></div>
        
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative bg-surface w-full max-w-lg rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-outline-variant/30">
            
            <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container/50">
                <h3 class="font-montserrat font-bold text-lg text-on-surface">Beri Ulasan Pesanan</h3>
                <button @click="show = false" type="button" class="text-on-surface-variant hover:text-error transition-all">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="formAction" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant block mb-2">
                        Kondisi Kendaraan (<span x-text="vehicle" class="font-bold text-primary"></span>)
                    </label>
                    <select name="vehicle_rating" required class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2 px-3 text-[14px]">
                        <option value="5">⭐⭐⭐⭐⭐ - Sangat Baik</option>
                        <option value="4">⭐⭐⭐⭐ - Baik</option>
                        <option value="3">⭐⭐⭐ - Cukup</option>
                        <option value="2">⭐⭐ - Buruk</option>
                        <option value="1">⭐ - Sangat Buruk</option>
                    </select>
                </div>

                <template x-if="hasDriver">
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant block mb-2">
                            Pelayanan Supir (<span x-text="driverName" class="font-bold text-primary"></span>)
                        </label>
                        <select name="driver_rating" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2 px-3 text-[14px]">
                            <option value="5">⭐⭐⭐⭐⭐ - Sangat Baik</option>
                            <option value="4">⭐⭐⭐⭐ - Baik</option>
                            <option value="3">⭐⭐⭐ - Cukup</option>
                            <option value="2">⭐⭐ - Buruk</option>
                            <option value="1">⭐ - Sangat Buruk</option>
                        </select>
                    </div>
                </template>

                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant block mb-2">Pelayanan KlikRental Keseluruhan</label>
                    <select name="company_rating" required class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2 px-3 text-[14px]">
                        <option value="5">⭐⭐⭐⭐⭐ - Sangat Baik</option>
                        <option value="4">⭐⭐⭐⭐ - Baik</option>
                        <option value="3">⭐⭐⭐ - Cukup</option>
                        <option value="2">⭐⭐ - Buruk</option>
                        <option value="1">⭐ - Sangat Buruk</option>
                    </select>
                </div>

                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant block mb-2">Komentar Tambahan (Opsional)</label>
                    <textarea name="comment" rows="3" placeholder="Bagaimana pengalaman Anda menyewa dengan kami?" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2 px-3 text-[14px] resize-none focus:border-primary focus:ring-0"></textarea>
                </div>

                <button type="submit" class="w-full bg-primary text-white font-bold text-[14px] py-3 rounded-xl hover:bg-primary/90 transition-all active:scale-[0.98] shadow-md mt-2">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>