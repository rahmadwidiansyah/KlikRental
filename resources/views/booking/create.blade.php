<x-app-layout>
    <!-- CSS Flatpickr & Kustomisasi Modal Tengah -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    <style>
        /* Memaksa kalender Flatpickr melayang di tengah layar */
        .flatpickr-calendar.flatpickr-centered-modal {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 100 !important;
            margin: 0 !important;
            box-shadow: 0px 20px 25px -5px rgba(0, 0, 0, 0.15), 0px 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            border: 1px solid rgba(196, 200, 185, 0.6) !important;
            border-radius: 1rem !important;
            overflow: hidden;
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
    </style>

    <div class="max-w-5xl mx-auto px-4 py-10">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-montserrat text-2xl md:text-3xl font-bold text-on-surface leading-tight">Form Penyewaan Mobil</h1>
            <p class="font-inter text-[14px] md:text-[15px] text-on-surface-variant mt-1.5">
                Anda memilih armada premium <strong class="text-primary font-semibold">{{ $vehicle->name }}</strong>
                <span class="text-on-surface-variant/70 italic">(Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }} / hari)</span>
            </p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

            <!-- Kolom Kiri: Input Data -->
            <div class="lg:col-span-2 bg-surface border border-outline-variant/60 rounded-2xl p-5 md:p-6 premium-shadow space-y-6">

                <!-- Slicing Date Picker & Time -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl & Jam Ambil</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">calendar_today</span>
                            <input type="text" name="start_date" id="start_date" required placeholder="YYYY-MM-DD HH:MM" data-min-date="{{ date('Y-m-d H:i') }}"
                                class="datetime-picker w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all cursor-pointer bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Tgl & Jam Kembali</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px] z-10">event_busy</span>
                            <input type="text" name="end_date" id="end_date" required placeholder="YYYY-MM-DD HH:MM" data-min-date="{{ date('Y-m-d H:i') }}"
                                class="datetime-picker w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all cursor-pointer bg-white">
                        </div>
                    </div>
                </div>

                <!-- Lokasi & Driver -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Zona Penjemputan</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">location_on</span>
                            <select name="pickup_zone_id" id="pickup_zone_id" required class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-10 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none appearance-none cursor-pointer transition-all">
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
                            <select name="dropoff_zone_id" id="dropoff_zone_id" required class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-10 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none appearance-none cursor-pointer transition-all">
                                <option value="">-- Pilih Lokasi Kembali --</option>
                                @foreach($zones as $zone) <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option> @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Opsi Supir</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">person</span>
                        <select name="driver_id" id="driver_id" class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-10 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none appearance-none cursor-pointer transition-all">
                            <option value="">-- Tanpa Supir (Lepas Kunci) --</option>
                            @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }} (+ Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}/hari)</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50 text-[20px] pointer-events-none">expand_more</span>
                    </div>
                </div>

                <div>
                    <label class="font-inter font-semibold text-[13px] text-on-surface-variant mb-1.5 block">Kode Promo</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary text-[20px]">local_offer</span>
                        <input type="text" name="promo_code" id="promo_code" placeholder="Masukkan kode promo jika ada..."
                            class="w-full bg-surface border border-outline-variant/50 rounded-xl py-2.5 pl-10 pr-4 text-[14px] text-on-surface focus:border-primary focus:ring-0 outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Tagihan -->
            <div class="glass-panel p-5 md:p-6 rounded-2xl border border-outline-variant/30 h-max sticky top-24 premium-shadow">
                <h3 class="font-montserrat font-bold text-[16px] text-on-surface mb-4 pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span>
                    Rincian Tagihan
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

                    <div id="promo-row" class="hidden justify-between items-center text-forest-green pt-2.5 border-t border-dashed border-outline-variant/60">
                        <strong id="detail-promo-label" class="font-semibold text-[13px]">Diskon Promo (0%):</strong>
                        <strong id="detail-promo-discount" class="font-bold text-[14px]">- Rp 0</strong>
                    </div>

                    <p id="live-promo-msg" class="text-[12px] font-medium transition-all duration-300 empty:hidden"></p>

                    <!-- EDIT: Mengunci baris total pembayaran agar tidak wrap & support auto font shrink -->
                    <div class="flex justify-between items-center pt-4 border-t border-outline-variant/40 gap-2 min-w-0 w-full">
                        <h2 class="font-montserrat font-bold text-[14px] text-on-surface whitespace-nowrap">Total Pembayaran:</h2>
                        <div class="overflow-hidden text-right flex-grow min-w-0">
                            <h2 id="live-price" class="font-montserrat font-bold text-[20px] text-primary whitespace-nowrap inline-block w-full text-right">Rp 0</h2>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn-submit" disabled
                    class="w-full mt-6 bg-primary text-white font-inter font-bold text-[14px] py-3 rounded-xl disabled:bg-outline-variant/50 disabled:text-on-surface-variant/40 disabled:cursor-not-allowed disabled:shadow-none hover:bg-primary/90 transition-all active:scale-[0.98] shadow-lg shadow-primary/20 flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">payment</span>
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </div>

<!-- JS Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- SCRIPT AJAX UNTUK LIVE CALCULATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Inisialisasi Flatpickr dengan Floating Center & Tombol Aksi Custom + Format 24 Jam
            flatpickr(".datetime-picker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today",
                onOpen: function(selectedDates, dateStr, instance) {
                    // Tambahkan overlay gelap di latar belakang
                    let backdrop = document.getElementById('flatpickr-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.id = 'flatpickr-backdrop';
                        backdrop.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[99] transition-opacity duration-200 opacity-0';
                        document.body.appendChild(backdrop);
                        backdrop.offsetHeight; // trigger reflow
                        backdrop.classList.add('opacity-100');
                        backdrop.addEventListener('click', () => instance.close());
                    }
                    instance.calendarContainer.classList.add('flatpickr-centered-modal');
                },
                onClose: function(selectedDates, dateStr, instance) {
                    // Hapus overlay saat kalender ditutup
                    const backdrop = document.getElementById('flatpickr-backdrop');
                    if (backdrop) {
                        backdrop.classList.remove('opacity-100');
                        backdrop.classList.add('opacity-0');
                        setTimeout(() => backdrop.remove(), 200);
                    }
                },
                onReady: function(selectedDates, dateStr, instance) {
                    // Menyisipkan Bar Tombol OK & CANCEL di bagian bawah kalender secara dinamis
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

            // 2. Deklarasi Variabel DOM
            const form = document.getElementById('bookingForm');
            const inputs = form.querySelectorAll('input, select');
            const btnSubmit = document.getElementById('btn-submit');

            const elDuration = document.getElementById('detail-duration');
            const elVehiclePrice = document.getElementById('detail-vehicle-price');
            const elDriverDuration = document.getElementById('detail-driver-duration');
            const elDriverPrice = document.getElementById('detail-driver-price');
            const elPickup = document.getElementById('detail-pickup');
            const elDropoff = document.getElementById('detail-dropoff');
            const elTotalPrice = document.getElementById('live-price');
            
            const promoRow = document.getElementById('promo-row');
            const elPromoLabel = document.getElementById('detail-promo-label');
            const elPromoDiscount = document.getElementById('detail-promo-discount');
            const elPromoMsg = document.getElementById('live-promo-msg');

            // 3. Fungsi Auto-Shrink Font untuk Total Pembayaran (Solusi Ukuran Bergetar)
            function adjustPriceFontSize() {
                elTotalPrice.style.transition = 'none'; // Matikan transisi agar tidak bouncing
                elTotalPrice.style.fontSize = '20px'; // Reset ke ukuran default
                
                const containerWidth = elTotalPrice.parentElement.clientWidth;
                
                let currentSize = 20;
                while (elTotalPrice.scrollWidth > containerWidth && currentSize > 12) {
                    currentSize -= 1;
                    elTotalPrice.style.fontSize = currentSize + 'px';
                }
            }

            // Gunakan Debounce saat layar di-resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(adjustPriceFontSize, 50);
            });

            // 4. Fungsi Utama Kalkulasi Harga
            function calculatePrice() {
                const formData = new FormData(form);

                fetch('{{ route('booking.calculatePrice') }}', {
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
                        // Tampilkan data rincian mobil dan supir
                        elDuration.innerText = `Sewa Mobil (${data.duration_days} Hari):`;
                        elVehiclePrice.innerText = data.vehicle_cost;
                        
                        elDriverDuration.innerText = `Jasa Supir (${data.duration_days} Hari):`;
                        elDriverPrice.innerText = data.driver_cost;

                        elPickup.innerText = data.pickup_cost;
                        elDropoff.innerText = data.dropoff_cost;
                        elTotalPrice.innerText = data.total_price;

                        // Logic Tampilan Promo
                        if (document.getElementById('promo_code').value.trim() !== '') {
                            elPromoMsg.innerText = data.promo_message;
                            if (data.promo_valid) {
                                elPromoMsg.style.color = '#476428'; 
                                promoRow.style.display = 'flex';
                                elPromoLabel.innerText = `Diskon Promo (${data.promo_percentage}%):`;
                                elPromoDiscount.innerText = data.promo_discount;
                            } else {
                                elPromoMsg.style.color = '#ef4444';
                                promoRow.style.display = 'none';
                            }
                        } else {
                            elPromoMsg.innerText = '';
                            promoRow.style.display = 'none';
                        }

                        btnSubmit.disabled = false;
                    } else {
                        // Reset kalau data belum lengkap / error tanggal mundur
                        elDuration.innerText = `Sewa Mobil (0 Hari):`;
                        elVehiclePrice.innerText = 'Rp 0';
                        elDriverDuration.innerText = `Jasa Supir (0 Hari):`;
                        elDriverPrice.innerText = 'Rp 0';
                        elPickup.innerText = 'Rp 0';
                        elDropoff.innerText = 'Rp 0';
                        elTotalPrice.innerText = 'Rp 0';
                        promoRow.style.display = 'none';
                        elPromoMsg.innerText = data.message || '';
                        elPromoMsg.style.color = '#ef4444';
                        btnSubmit.disabled = true;
                    }
                    
                    // Panggil fungsi penyesuaian font setelah text harga diupdate
                    setTimeout(adjustPriceFontSize, 50);
                })
                .catch(error => console.error('Error:', error));
            }

            // 5. Dengarkan setiap perubahan pada input/select
            inputs.forEach(input => {
                input.addEventListener('change', calculatePrice);
                input.addEventListener('keyup', calculatePrice);
            });
        });
    </script>>
</x-app-layout>