<x-app-layout>
    <div style="padding: 20px;">
        <h1>Riwayat Pesanan Saya</h1>
        <hr>

        @if(session('success'))
        <p style="color: green;"><strong>{{ session('success') }}</strong></p>
        @endif

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Mobil</th>
                    <th>Jadwal Sewa</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_code }}</td>
                    <td>{{ $booking->vehicle->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y H:i') }} s/d <br> {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y H:i') }}</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        <strong>{{ strtoupper($booking->status) }}</strong>
                    </td>
                    <td>
                        <!-- Tombol Bayar jika status masih pending -->
                        @if($booking->status == 'pending')
                        <a href="{{ route('booking.show', $booking->booking_code) }}" style="text-decoration: none;">
                            <button type="button" style="padding: 5px 10px; background-color: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                Detail / Bayar
                            </button>
                        </a>
                        @else
                        <a href="#">Lihat Detail</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Anda belum memiliki riwayat pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>