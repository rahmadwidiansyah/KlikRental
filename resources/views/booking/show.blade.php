<x-app-layout>
    <div style="padding: 20px; max-width: 800px; margin: auto;">
        <!-- Header Invoice -->
        <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            <h1>INVOICE PEMBAYARAN</h1>
            <h3>Kode Booking: {{ $booking->booking_code }}</h3>
            <p>Status: <strong style="color: {{ $booking->status == 'pending' ? 'orange' : 'green' }};">{{ strtoupper($booking->status) }}</strong></p>
        </div>

        <!-- Detail Perjalanan -->
        <div style="margin-bottom: 20px;">
            <h2>Informasi Perjalanan</h2>
            <table width="100%" cellpadding="5">
                <tr>
                    <td width="30%"><strong>Mobil</strong></td>
                    <td>: {{ $booking->vehicle->name }}</td>
                </tr>
                <tr>
                    <td><strong>Jadwal Ambil</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($booking->start_date)->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Jadwal Kembali</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($booking->end_date)->format('d F Y, H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Lokasi Jemput</strong></td>
                    <td>: {{ $booking->pickupZone->zone_name }}</td>
                </tr>
                <tr>
                    <td><strong>Lokasi Kembali</strong></td>
                    <td>: {{ $booking->dropoffZone->zone_name }}</td>
                </tr>
                <tr>
                    <td><strong>Supir</strong></td>
                    <td>: {{ $booking->driver ? $booking->driver->name : 'Tanpa Supir (Lepas Kunci)' }}</td>
                </tr>
            </table>
        </div>

        <hr>

        <!-- Rincian Biaya -->
        <div style="margin-bottom: 20px;">
            <h2>Rincian Biaya</h2>
            <table width="100%" cellpadding="5" border="1" cellspacing="0">
                <tr style="background-color: #f3f4f6;">
                    <th>Deskripsi</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
                
                @php
                    // Hitung ulang durasi untuk ditampilkan di struk
                    $start = \Carbon\Carbon::parse($booking->start_date);
                    $end = \Carbon\Carbon::parse($booking->end_date);
                    $durationDays = ceil($start->diffInHours($end) / 24) ?: 1;
                @endphp

                <tr>
                    <td>Sewa Mobil ({{ $durationDays }} Hari)</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->vehicle->price_per_day * $durationDays, 0, ',', '.') }}</td>
                </tr>
                
                @if($booking->driver)
                <tr>
                    <td>Jasa Supir ({{ $durationDays }} Hari)</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->driver->daily_rate * $durationDays, 0, ',', '.') }}</td>
                </tr>
                @endif

                <tr>
                    <td>Biaya Titik Jemput</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->pickupZone->additional_cost, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Biaya Titik Kembali</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->dropoffZone->additional_cost, 0, ',', '.') }}</td>
                </tr>

                @if($booking->promo)
                <tr style="color: green;">
                    <td>Diskon Promo ({{ $booking->promo->code }})</td>
                    <!-- Karena kita simpan total_price bersih di DB, kita tidak simpan nominal diskonnya. -->
                    <!-- Untuk tampilan sederhana, kita hitung selisihnya -->
                    @php
                        $subtotal = ($booking->vehicle->price_per_day * $durationDays) + 
                                    ($booking->driver ? $booking->driver->daily_rate * $durationDays : 0) + 
                                    $booking->pickupZone->additional_cost + 
                                    $booking->dropoffZone->additional_cost;
                        $discounted = $subtotal - $booking->total_price;
                    @endphp
                    <td style="text-align: right;">- Rp {{ number_format($discounted, 0, ',', '.') }}</td>
                </tr>
                @endif

                <tr style="font-weight: bold; font-size: 1.2em; background-color: #e5e7eb;">
                    <td>TOTAL PEMBAYARAN</td>
                    <td style="text-align: right; color: #2563eb;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Tombol Aksi -->
        <div style="text-align: center; margin-top: 30px;">
            @if($booking->status == 'pending')
                <button type="button" style="padding: 15px 30px; background-color: #10b981; color: white; border: none; border-radius: 8px; font-size: 1.2em; cursor: pointer; font-weight: bold;">
                    💳 Bayar Sekarang
                </button>
                <p style="font-size: 12px; color: #6b7280; margin-top: 10px;">*Sistem pembayaran menggunakan Midtrans</p>
            @else
                <button disabled style="padding: 15px 30px; background-color: #9ca3af; color: white; border: none; border-radius: 8px; font-size: 1.2em; cursor: not-allowed; font-weight: bold;">
                    ✅ Lunas
                </button>
            @endif
        </div>
    </div>
</x-app-layout>