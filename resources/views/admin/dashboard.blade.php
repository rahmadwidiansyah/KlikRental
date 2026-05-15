<x-app-layout>
    <div style="padding: 20px;">
        <h1>Dashboard Administrator KlikRental</h1>
        <p>Halo Admin {{ Auth::user()->name }}, selamat bekerja!</p>
        <hr>

        <h2>Menu Cepat</h2>
        <ul>
            <li><a href="#">Kelola Armada Mobil</a></li>
            <li><a href="#">Kelola Zona Lokasi</a></li>
            <li><a href="#">Kelola Supir</a></li>
            <li><a href="#">Kelola Kode Promo</a></li>
        </ul>

        <hr>

        <h2>Pesanan Terbaru (Pending)</h2>
        <!-- Ini kerangka tabel untuk nanti diisi data Booking -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Pelanggan</th>
                    <th>Mobil</th>
                    <th>Tgl Sewa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada pesanan masuk.</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app-layout>