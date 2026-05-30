<x-app-layout>
    <!-- CSS Flatpickr & Modal Scrollbar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <style>
        .flatpickr-calendar.flatpickr-centered-modal {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 100 !important;
            margin: 0 !important;
            box-shadow: 0px 20px 25px -5px rgba(0, 0, 0, 0.15) !important;
            border-radius: 1rem !important;
            animation: flatpickrModalFadeIn 0.2s ease-out;
        }

        @keyframes flatpickrModalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -45%) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Custom Scrollbar Modal */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--color-outline-variant);
            border-radius: 10px;
        }

        /* ================================================== */
        /* FLATPICKR DARK MODE OVERRIDE                       */
        /* ================================================== */
        .dark .flatpickr-calendar {
            background: var(--color-surface) !important;
            border: 1px solid var(--color-outline-variant) !important;
            box-shadow: 0px 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .dark .flatpickr-months .flatpickr-month,
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months,
        .dark .flatpickr-weekday {
            background: var(--color-surface) !important;
            color: var(--color-on-surface) !important;
        }
        .dark .flatpickr-prev-month svg, 
        .dark .flatpickr-next-month svg {
            fill: var(--color-on-surface) !important;
        }
        .dark .flatpickr-prev-month:hover svg, 
        .dark .flatpickr-next-month:hover svg {
            fill: var(--color-primary) !important;
        }
        .dark .flatpickr-day {
            color: var(--color-on-surface) !important;
        }
        .dark .flatpickr-day:hover, 
        .dark .flatpickr-day.prevMonthDay:hover, 
        .dark .flatpickr-day.nextMonthDay:hover {
            background: var(--color-surface-container) !important;
            border-color: var(--color-surface-container) !important;
        }
        .dark .flatpickr-day.selected {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: #ffffff !important;
        }
        .dark .flatpickr-day.flatpickr-disabled {
            color: var(--color-on-surface-variant) !important;
            opacity: 0.3 !important;
        }
        .dark .flatpickr-time {
            border-top-color: var(--color-outline-variant) !important;
        }
        .dark .numInputWrapper span.arrowUp:after {
            border-bottom-color: var(--color-on-surface) !important;
        }
        .dark .numInputWrapper span.arrowDown:after {
            border-top-color: var(--color-on-surface) !important;
        }
        .dark .flatpickr-time input, 
        .dark .flatpickr-time .flatpickr-am-pm {
            color: var(--color-on-surface) !important;
        }
        .dark .flatpickr-time input:hover, 
        .dark .flatpickr-time .flatpickr-am-pm:hover {
            background: var(--color-surface-container) !important;
        }
    </style>

    <!-- State Alpine.js untuk Modal dan Value Supir & Nomor WA -->
    <div x-data="{ 
            showDriverModal: false, 
            driverId: '', 
            driverName: 'Tanpa Supir (Lepas Kunci)', 
            driverPrice: '+ Rp 0/hari',
            showPhoneModal: {{ $errors->has('phone_number') ? 'true' : 'false' }},
            userPhone: '{{ old('phone_number', auth()->user()->phone_number) }}'
         }"
        class="max-w-5xl mx-auto px-4 py-10">

        <!-- Header -->
        <div class="mb-8 border-b border-outline-variant/30 pb-6">
            <h1 class="font-montserrat text-3xl font-bold text-on-surface">Form Penyewaan Mobil</h1>
            <p class="font-inter text-on-surface-variant mt-2 text-lg">
                Sewa <span class="font-bold text-primary">{{ $vehicle->name }}</span>
                <span class="text-on-surface-variant/70 font-normal">| Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }} per hari</span>
            </p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start"
              @submit.prevent="if(!userPhone || userPhone.trim() === '') { showPhoneModal = true; } else { $el.submit(); }">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
            <input type="hidden" name="phone_number" x-model="userPhone">

            <!-- Kolom Kiri: Input Data -->
            <div class="lg:col-span-2 bg-surface border border-outline-variant/60 rounded-2xl p-5 md:p-6 premium-shadow space-y-6">

                <!-- Tgl Ambil & Kembali -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl & Jam Ambil</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">calendar_today</span>
                            <!-- EDIT: bg-white dihapus, diganti bg-surface-container-lowest -->
                            <input type="text" name="start_date" id="start_date" required placeholder="YYYY-MM-DD HH:MM" data-min-date="{{ date('Y-m-d H:i') }}"
                                class="datetime-picker w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl & Jam Kembali</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">event_busy</span>
                            <!-- EDIT: bg-white dihapus, diganti bg-surface-container-lowest -->
                            <input type="text" name="end_date" id="end_date" required placeholder="YYYY-MM-DD HH:MM" data-min-date="{{ date('Y-m-d H:i') }}"
                                class="datetime-picker w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- Zona Jemput & Kembali -->
                @php $selectClass = "w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-10 text-[14px] text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none cursor-pointer bg-none transition-all"; @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Zona Penjemputan</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">location_on</span>
                            <select name="pickup_zone_id" id="pickup_zone_id" required class="{{ $selectClass }}">
                                <option value="">-- Pilih Lokasi Jemput --</option>
                                @foreach($zones as $zone) <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option> @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                        </div>
                    </div>
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Zona Pengembalian</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">wrong_location</span>
                            <select name="dropoff_zone_id" id="dropoff_zone_id" required class="{{ $selectClass }}">
                                <option value="">-- Pilih Lokasi Kembali --</option>
                                @foreach($zones as $zone) <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option> @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <!-- OPSI SUPIR -->
                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Opsi Supir</label>
                    <input type="hidden" name="driver_id" id="driver_input" x-model="driverId">
                    <button type="button" @click="showDriverModal = true" class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl p-3 flex justify-between items-center hover:border-primary/50 transition-all group text-left">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-primary text-[20px]">person_check</span>
                            </div>
                            <div>
                                <p class="font-montserrat font-bold text-[14px] text-on-surface line-clamp-1" x-text="driverName"></p>
                                <p class="font-inter text-[12px] text-primary font-medium" x-text="driverPrice"></p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant/50 group-hover:text-primary transition-colors">edit</span>
                    </button>
                </div>

                <!-- Kode Promo -->
                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Kode Promo</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">local_offer</span>
                        <input type="text" name="promo_code" id="promo_code" placeholder="Masukkan kode promo jika ada..."
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Tagihan -->
            <!-- EDIT: glass-panel dihapus, diganti bg-surface/80 backdrop-blur-xl agar mengikuti Dark Mode variables -->
            <div class="bg-surface/80 backdrop-blur-xl p-5 md:p-6 rounded-2xl border border-outline-variant/30 h-max sticky top-24 premium-shadow">
                <h3 class="font-montserrat font-bold text-[16px] text-on-surface mb-4 pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span> Rincian Tagihan
                </h3>

                <div class="space-y-3.5 font-inter text-[13px]">
                    <div class="flex justify-between items-center">
                        <span id="detail-duration" class="text-on-surface-variant">Sewa Mobil (0 Hari):</span>
                        <strong id="detail-vehicle-price" class="text-on-surface font-semibold text-[14px]">Rp 0</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span id="detail-driver-duration" class="text-on-surface-variant">Jasa Supir (0 Hari):</span>
                        <strong id="detail-driver-price" class="text-on-surface font-semibold text-[14px]">Rp 0</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Titik Jemput:</span>
                        <strong id="detail-pickup" class="text-on-surface font-semibold text-[14px]">Rp 0</strong>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">Titik Kembali:</span>
                        <strong id="detail-dropoff" class="text-on-surface font-semibold text-[14px]">Rp 0</strong>
                    </div>

                    <!-- Promo -->
                    <div id="promo-row" class="hidden justify-between items-center text-forest-green pt-2.5 border-t border-dashed border-outline-variant/60">
                        <strong id="detail-promo-label" class="font-semibold text-[13px]">Diskon Promo:</strong>
                        <strong id="detail-promo-discount" class="font-bold text-[14px]">- Rp 0</strong>
                    </div>
                    <p id="live-promo-msg" class="text-[12px] font-medium transition-all duration-300 empty:hidden"></p>

                    <!-- PAJAK -->
                    <div class="flex justify-between items-center text-on-surface-variant pt-2">
                        <span class="text-[13px]">Pajak (PPN 11%):</span>
                        <strong id="detail-tax" class="font-semibold text-[14px]">Rp 0</strong>
                    </div>

                    <!-- Total Akhir -->
                    <div class="flex justify-between items-center pt-4 border-t border-outline-variant/40 gap-2 min-w-0 w-full">
                        <h2 class="font-montserrat font-bold text-[14px] text-on-surface whitespace-nowrap">Total Pembayaran:</h2>
                        <div class="overflow-hidden text-right flex-grow min-w-0">
                            <h2 id="live-price" class="font-montserrat font-bold text-[20px] text-primary whitespace-nowrap inline-block w-full text-right">Rp 0</h2>
                        </div>
                    </div>
                </div>
                
                <!-- EDIT: bg-error di-adjust agar enak dipandang saat Dark Mode -->
                <div class="mt-4 flex items-start gap-2 p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30">
                    <span class="material-symbols-outlined text-[16px] text-red-600 mt-0.5">warning</span>
                    <p class="font-inter text-[11px] text-on-surface-variant leading-relaxed">
                        <strong class="text-on-surface font-semibold">Catatan Penting:</strong> Total tagihan belum termasuk biaya tambahan jika terjadi <strong>keterlambatan, kerusakan fisik kendaraan (lecet/penyok), kendala kebersihan, atau kehilangan aksesori</strong> selama masa sewa sesuai Syarat & Ketentuan.
                    </p>
                </div>
                <button type="submit" id="btn-submit" disabled
                    class="w-full mt-6 bg-primary text-white font-inter font-bold text-[14px] py-3 rounded-xl disabled:bg-outline-variant/50 disabled:text-on-surface-variant/40 disabled:cursor-not-allowed disabled:shadow-none hover:bg-primary/90 transition-all active:scale-[0.98] shadow-lg shadow-primary/20 flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">payment</span> Lanjut Pembayaran
                </button>
            </div>
        </form>

        <!-- ============================================== -->
        <!-- MODAL POPUP CENTER DRIVER SELECTION            -->
        <!-- ============================================== -->
        <div x-show="showDriverModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
            <div x-show="showDriverModal" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showDriverModal = false"></div>

            <div x-show="showDriverModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative bg-surface w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] border border-outline-variant/30">

                <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container/50 rounded-t-2xl">
                    <div>
                        <h3 class="font-montserrat font-bold text-lg text-on-surface">Pilih Opsi Pengemudi</h3>
                        <p class="font-inter text-[12px] text-on-surface-variant">Harga akan ditambahkan ke total sewa per hari.</p>
                    </div>
                    <button @click="showDriverModal = false" type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:text-red-600 hover:border-red-600/30 transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <!-- Grid Card Supir -->
                <div class="p-5 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Card: Lepas Kunci -->
                        <div @click="driverId = ''; driverName = 'Tanpa Supir (Lepas Kunci)'; driverPrice = '+ Rp 0/hari'; showDriverModal = false; window.triggerCalc();"
                            class="cursor-pointer bg-surface border-2 rounded-xl p-4 transition-all duration-300 hover:border-primary hover:bg-primary/5 flex items-center gap-4"
                            :class="driverId === '' ? 'border-primary bg-primary/5' : 'border-outline-variant/40'">
                            <div class="w-14 h-14 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/50">
                                <span class="material-symbols-outlined text-on-surface-variant text-[28px]">key</span>
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-montserrat font-bold text-[15px] text-on-surface">Lepas Kunci</h4>
                                <p class="font-inter text-[12px] text-on-surface-variant mt-0.5">Tanpa tambahan biaya</p>
                            </div>
                            <div class="shrink-0" x-show="driverId === ''">
                                <span class="material-symbols-outlined text-primary icon-fill text-[24px]">check_circle</span>
                            </div>
                        </div>

                        <!-- Card: Looping Data Supir -->
                        @foreach($drivers as $driver)
                        <div @click="driverId = '{{ $driver->id }}'; driverName = '{{ $driver->name }}'; driverPrice = '+ Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}/hari'; showDriverModal = false; window.triggerCalc();"
                            class="cursor-pointer bg-surface border-2 rounded-xl p-4 transition-all duration-300 hover:border-primary hover:bg-primary/5 flex items-center gap-4"
                            :class="driverId === '{{ $driver->id }}' ? 'border-primary bg-primary/5' : 'border-outline-variant/40'">

                            <div class="w-14 h-14 rounded-full bg-surface-container shrink-0 border border-outline-variant/50 overflow-hidden relative">
                                <img src="{{ $driver->image_url ? asset('storage/' . $driver->image_url) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=e4dfff&color=140067' }}" class="w-full h-full object-cover">
                                <span class="absolute bottom-0 right-1 w-3.5 h-3.5 bg-forest-green border-2 border-surface rounded-full"></span>
                            </div>

                            <div class="flex-grow">
                                <h4 class="font-montserrat font-bold text-[15px] text-on-surface line-clamp-1">{{ $driver->name }}</h4>

                                <div class="flex items-center gap-2 mt-1 mb-1.5">
                                    <div class="flex items-center gap-1 bg-[#FFF8E7] dark:bg-[#B87503]/20 text-[#B87503] dark:text-[#FBBF24] px-1.5 py-0.5 rounded text-[10px] font-bold">
                                        <span class="material-symbols-outlined icon-fill text-[12px]">star</span>
                                        {{ number_format($driver->reviews_avg_driver_rating ?? 5.0, 1) }}
                                    </div>
                                    <span class="text-outline-variant/80 text-[10px]">|</span>
                                    <div class="flex items-center gap-1 text-on-surface-variant text-[11px] font-medium">
                                        <span class="material-symbols-outlined text-[14px]">work_history</span>
                                        {{ $driver->bookings_count ?? 0 }}x Disewa
                                    </div>
                                </div>

                                <p class="font-inter font-bold text-[13px] text-primary">
                                    + Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}<span class="text-on-surface-variant/70 text-[11px] font-normal"> /hari</span>
                                </p>
                            </div>

                            <div class="shrink-0" x-show="driverId === '{{ $driver->id }}'">
                                <span class="material-symbols-outlined text-primary icon-fill text-[24px]">check_circle</span>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================== -->

        <!-- PERUBAHAN: MODAL POPUP JIKA NOMOR WHATSAPP KOSONG -->
        <div x-show="showPhoneModal" class="fixed inset-0 z-[105] flex items-center justify-center p-4 sm:p-6" style="display: none;">
            <div x-show="showPhoneModal" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div x-show="showPhoneModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative bg-surface w-full max-w-md rounded-2xl shadow-2xl flex flex-col border border-outline-variant/30 overflow-hidden">

                <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container/50">
                    <h3 class="font-montserrat font-bold text-lg text-on-surface">Lengkapi Profil Anda</h3>
                    <button @click="showPhoneModal = false" type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:text-red-600 hover:border-red-600/30 transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="p-6">
                    <div class="w-14 h-14 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4 mx-auto border border-primary/20">
                        <span class="material-symbols-outlined text-[28px]">forum</span>
                    </div>
                    <p class="font-inter text-[13.5px] text-on-surface-variant text-center mb-6 leading-relaxed">
                        Sistem mendeteksi Anda belum mengatur <strong>Nomor WhatsApp</strong>. Nomor ini diwajibkan untuk koordinasi penjemputan dengan tim atau pengemudi kami.
                    </p>

                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">
                            Nomor WhatsApp <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">call</span>
                            <input type="tel" x-model="userPhone" placeholder="Contoh: 081234567890" required
                                class="w-full bg-surface-container-lowest border @error('phone_number') border-red-600 focus:border-red-600 focus:ring-red-600 @else border-outline-variant/50 focus:border-primary focus:ring-primary @enderror rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:ring-1 outline-none transition-all">
                        </div>
                        @error('phone_number')
                            <p class="text-red-600 text-[11px] mt-1.5 font-inter font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="p-5 border-t border-outline-variant/30 bg-surface-container/30 flex justify-end gap-3">
                    <button type="button" @click="showPhoneModal = false" class="px-4 py-2 text-[13px] font-bold font-inter text-on-surface-variant hover:bg-surface-container border border-transparent hover:border-outline-variant/30 rounded-xl transition-all">Batal</button>
                    <!-- Tombol ini memicu submit form utama setelah mengisi data -->
                    <button type="button" @click="if(userPhone && userPhone.trim() !== '') { showPhoneModal = false; $nextTick(() => document.getElementById('bookingForm').submit()); }" 
                        class="px-5 py-2 text-[13px] font-bold font-inter text-white bg-primary rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center gap-1">
                        Simpan & Lanjut <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- ============================================== -->

    </div>

    <!-- JS Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- SCRIPT AJAX UNTUK LIVE CALCULATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Tangkap tanggal yang di-disable dari controller (Jeda H+2)
            const disabledDates = @json($bookedRanges);

            // Inisialisasi Flatpickr
            flatpickr(".datetime-picker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                disable: disabledDates, // Blokir kalender sesuai jadwal booking
                onOpen: function(selectedDates, dateStr, instance) {
                    let backdrop = document.getElementById('flatpickr-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.id = 'flatpickr-backdrop';
                        backdrop.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[99] transition-opacity duration-200 opacity-0';
                        document.body.appendChild(backdrop);
                        backdrop.offsetHeight;
                        backdrop.classList.add('opacity-100');
                        backdrop.addEventListener('click', () => instance.close());
                    }
                    instance.calendarContainer.classList.add('flatpickr-centered-modal');
                },
                onClose: function(selectedDates, dateStr, instance) {
                    const backdrop = document.getElementById('flatpickr-backdrop');
                    if (backdrop) {
                        backdrop.classList.remove('opacity-100');
                        backdrop.classList.add('opacity-0');
                        setTimeout(() => backdrop.remove(), 200);
                    }
                },
                onReady: function(selectedDates, dateStr, instance) {
                    if (!instance.calendarContainer.querySelector('.flatpickr-action-buttons')) {
                        const actionsContainer = document.createElement('div');
                        actionsContainer.className = 'flatpickr-action-buttons flex justify-end gap-2 p-2 border-t border-outline-variant/40 bg-surface-container/60';

                        const cancelButton = document.createElement('button');
                        cancelButton.type = 'button';
                        cancelButton.className = 'px-3 py-1.5 text-[12px] font-inter font-bold text-on-surface-variant hover:bg-surface-container rounded-lg transition-colors border border-outline-variant/40';
                        cancelButton.innerText = 'Cancel';
                        cancelButton.addEventListener('click', () => instance.close());

                        const okButton = document.createElement('button');
                        okButton.type = 'button';
                        okButton.className = 'px-4 py-1.5 text-[12px] font-inter font-bold text-white bg-primary hover:bg-primary/90 rounded-lg transition-colors shadow-sm';
                        okButton.innerText = 'OK';
                        okButton.addEventListener('click', () => {
                            instance.close();
                            calculatePrice();
                        });

                        actionsContainer.appendChild(cancelButton);
                        actionsContainer.appendChild(okButton);
                        instance.calendarContainer.appendChild(actionsContainer);
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    calculatePrice();
                }
            });

            // Deklarasi Variabel DOM
            const form = document.getElementById('bookingForm');
            const inputs = form.querySelectorAll('input:not(#driver_input):not([name="phone_number"]), select');
            const btnSubmit = document.getElementById('btn-submit');

            const elDuration = document.getElementById('detail-duration');
            const elVehiclePrice = document.getElementById('detail-vehicle-price');
            const elDriverDuration = document.getElementById('detail-driver-duration');
            const elDriverPrice = document.getElementById('detail-driver-price');
            const elPickup = document.getElementById('detail-pickup');
            const elDropoff = document.getElementById('detail-dropoff');
            const elTax = document.getElementById('detail-tax');
            const elTotalPrice = document.getElementById('live-price');

            const promoRow = document.getElementById('promo-row');
            const elPromoLabel = document.getElementById('detail-promo-label');
            const elPromoDiscount = document.getElementById('detail-promo-discount');
            const elPromoMsg = document.getElementById('live-promo-msg');

            function adjustPriceFontSize() {
                elTotalPrice.style.transition = 'none';
                elTotalPrice.style.fontSize = '20px';
                const containerWidth = elTotalPrice.parentElement.clientWidth;
                let currentSize = 20;
                while (elTotalPrice.scrollWidth > containerWidth && currentSize > 12) {
                    currentSize -= 1;
                    elTotalPrice.style.fontSize = currentSize + 'px';
                }
            }

            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(adjustPriceFontSize, 50);
            });

            window.triggerCalc = function() {
                setTimeout(calculatePrice, 100);
            };

            function calculatePrice() {
                const formData = new FormData(form);

                fetch("{{ route('booking.calculatePrice') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            elDuration.innerText = `Sewa Mobil (${data.duration_days} Hari):`;
                            elVehiclePrice.innerText = data.vehicle_cost;

                            elDriverDuration.innerText = `Jasa Supir (${data.duration_days} Hari):`;
                            elDriverPrice.innerText = data.driver_cost;

                            elPickup.innerText = data.pickup_cost;
                            elDropoff.innerText = data.dropoff_cost;
                            elTax.innerText = data.tax_cost;
                            elTotalPrice.innerText = data.total_price;

                            if (document.getElementById('promo_code').value.trim() !== '') {
                                elPromoMsg.innerText = data.promo_message;
                                if (data.promo_valid) {
                                    // Gunakan class bawaan Tailwind untuk text color agar kompatibel dark mode
                                    elPromoMsg.className = "text-[12px] font-medium transition-all duration-300 text-green-600 dark:text-green-500 mt-2";
                                    promoRow.style.display = 'flex';
                                    elPromoLabel.innerText = `Diskon Promo (${data.promo_percentage}%):`;
                                    elPromoDiscount.innerText = data.promo_discount;
                                } else {
                                    elPromoMsg.className = "text-[12px] font-medium transition-all duration-300 text-red-600 dark:text-red-400 mt-2";
                                    promoRow.style.display = 'none';
                                }
                            } else {
                                elPromoMsg.innerText = '';
                                promoRow.style.display = 'none';
                            }
                            btnSubmit.disabled = false;
                        } else {
                            elDuration.innerText = `Sewa Mobil (0 Hari):`;
                            elVehiclePrice.innerText = 'Rp 0';
                            elDriverDuration.innerText = `Jasa Supir (0 Hari):`;
                            elDriverPrice.innerText = 'Rp 0';
                            elPickup.innerText = 'Rp 0';
                            elDropoff.innerText = 'Rp 0';
                            elTax.innerText = 'Rp 0';
                            elTotalPrice.innerText = 'Rp 0';
                            promoRow.style.display = 'none';
                            elPromoMsg.innerText = data.message || '';
                            elPromoMsg.className = "text-[12px] font-medium transition-all duration-300 text-red-600 dark:text-red-400 mt-2";
                            btnSubmit.disabled = true;
                        }
                        setTimeout(adjustPriceFontSize, 50);
                    })
                    .catch(error => console.error('Error:', error));
            }
            inputs.forEach(input => {
                input.addEventListener('change', calculatePrice);
                input.addEventListener('keyup', calculatePrice);
            });
        });
    </script>
</x-app-layout>