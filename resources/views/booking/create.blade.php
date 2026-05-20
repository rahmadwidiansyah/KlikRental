<x-app-layout>
    <div style="padding: 20px;">
        <h1>Form Penyewaan Mobil</h1>
        <p>Anda akan menyewa: <strong>{{ $vehicle->name }}</strong> (Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }} / hari)</p>
        <hr>

        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

            <div style="margin-bottom: 15px;">
                <label>Tanggal & Jam Ambil:</label><br>
                <input type="datetime-local" name="start_date" id="start_date" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Tanggal & Jam Kembali:</label><br>
                <input type="datetime-local" name="end_date" id="end_date" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Zona Penjemputan:</label><br>
                <select name="pickup_zone_id" id="pickup_zone_id" required>
                    <option value="">-- Pilih Lokasi Jemput --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Zona Pengembalian:</label><br>
                <select name="dropoff_zone_id" id="dropoff_zone_id" required>
                    <option value="">-- Pilih Lokasi Kembali --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                    @endforeach
                </select>
            </div>

           <div style="margin-bottom: 15px;">
                <label>Opsi Supir (Lepas kunci kosongkan):</label><br>
                <select name="driver_id" id="driver_id">
                    <option value="">-- Tanpa Supir (Lepas Kunci) --</option>
                    @foreach($drivers as $driver)
                        <!-- Tampilkan Harga Supir Langsung dari Database -->
                        <option value="{{ $driver->id }}">{{ $driver->name }} (+ Rp {{ number_format($driver->daily_rate, 0, ',', '.') }}/hari)</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Kode Promo:</label><br>
                <input type="text" name="promo_code" id="promo_code" placeholder="Masukkan kode promo...">
            </div>

<div style="background-color: #f9fafb; padding: 15px; margin-bottom: 15px; border: 1px solid #d1d5db; border-radius: 8px;">
                <h3 style="margin: 0 0 10px 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">Rincian Tagihan</h3>
                
                <!-- Rincian Biaya Sewa Mobil & Supir -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span id="detail-duration">Sewa Mobil (0 Hari):</span>
                    <strong id="detail-vehicle-price">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span id="detail-driver-duration">Jasa Supir (0 Hari):</span>
                    <strong id="detail-driver-price">Rp 0</strong>
                </div>

                <!-- Rincian Biaya Zona -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Titik Jemput:</span>
                    <strong id="detail-pickup">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span>Titik Kembali:</span>
                    <strong id="detail-dropoff">Rp 0</strong>
                </div>

                <!-- Rincian Promo -->
                <div id="promo-row" style="display: none; justify-content: space-between; margin-top: 10px; margin-bottom: 5px; color: #10b981;">
                    <strong id="detail-promo-label">Diskon Promo (0%):</strong>
                    <strong id="detail-promo-discount">- Rp 0</strong>
                </div>
                <p id="live-promo-msg" style="font-size: 13px; margin: 0 0 10px 0; font-style: italic;"></p>

                <!-- Total Akhir -->
                <div style="display: flex; justify-content: space-between; margin-top: 15px; border-top: 2px dashed #cbd5e1; padding-top: 15px;">
                    <h2 style="margin: 0;">Total Pembayaran:</h2>
                    <h2 id="live-price" style="margin: 0; color: #2563eb;">Rp 0</h2>
                </div>
            </div>

            <button type="submit" id="btn-submit" style="padding: 10px 20px;" disabled>Lanjut ke Pembayaran</button>
        </form>
    </div>

    <!-- SCRIPT AJAX UNTUK LIVE CALCULATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const inputs = form.querySelectorAll('input, select');
            const btnSubmit = document.getElementById('btn-submit');

            // Ambil elemen untuk Struk
            const elDuration = document.getElementById('detail-duration');
            const elVehiclePrice = document.getElementById('detail-vehicle-price');
            const elDriverDuration = document.getElementById('detail-driver-duration');
            const elDriverPrice = document.getElementById('detail-driver-price');
            const elPickup = document.getElementById('detail-pickup');
            const elDropoff = document.getElementById('detail-dropoff');
            const elTotalPrice = document.getElementById('live-price');
            
            // Ambil elemen untuk Promo
            const promoRow = document.getElementById('promo-row');
            const elPromoLabel = document.getElementById('detail-promo-label');
            const elPromoDiscount = document.getElementById('detail-promo-discount');
            const elPromoMsg = document.getElementById('live-promo-msg');

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
                                elPromoMsg.style.color = '#10b981'; 
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
                })
                .catch(error => console.error('Error:', error));
            }

            // Dengarkan setiap perubahan pada input/select
            inputs.forEach(input => {
                input.addEventListener('change', calculatePrice);
                input.addEventListener('keyup', calculatePrice);
            });
        });
    </script>
</x-app-layout>