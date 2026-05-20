<x-app-layout>
    <div style="padding: 20px;">
        <h1>Selamat Datang di KlikRental, {{ Auth::user()->name }}!</h1>

        <p>Silakan pilih kendaraan yang ingin Anda sewa hari ini.</p>
        <hr>

        <h2>Katalog Mobil Tersedia</h2>
        <!-- Menampilkan data dari database dalam bentuk list sederhana -->
        <ul style="list-style-type: none; padding-left: 0;">
            @foreach($vehicles as $car)
                <li style="margin-bottom: 15px; border: 1px solid #d1d5db; padding: 15px; width: 350px; border-radius: 8px; background-color: #f9fafb;">
                    
                    <!-- Menampilkan Thumbnail Utama -->
                    <div style="margin-bottom: 10px;">
                        <!-- Pakai placeholder jika foto belum ada di DB -->
                        <img src="{{ $car->primaryImage->image_url ?? 'https://placehold.co/350x200?text=No+Image' }}" alt="Foto Utama {{ $car->name }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px;">
                    </div>

                    <!-- Menampilkan Galeri Foto Lainnya (Thumbnail kecil) -->
                    <div class="gallery" style="display: flex; gap: 5px; margin-bottom: 15px; overflow-x: auto;">
                        @foreach($car->images as $foto)
                            <img src="{{ $foto->image_url }}" alt="Galeri {{ $car->name }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 3px; border: 1px solid #ccc;">
                        @endforeach
                    </div>

                    <h3 style="margin-top: 0;">{{ $car->name }}</h3>
                    <p style="margin: 5px 0;"><strong>Tipe:</strong> {{ $car->type }} ({{ $car->transmission }})</p>
                    <p style="margin: 5px 0;"><strong>Bahan Bakar:</strong> {{ $car->fuel_type }}</p>
                    <p style="margin: 5px 0;"><strong>Kapasitas:</strong> {{ $car->seats }} Kursi | {{ $car->luggage_capacity }} Koper</p>
                    <h4 style="color: #10b981; margin: 10px 0;">Rp {{ number_format($car->price_per_day, 0, ',', '.') }} / hari</h4>
                    
                    <!-- Tombol ini diarahkan ke form pemesanan -->
                    <a href="{{ route('booking.create', $car->id) }}" style="text-decoration: none;">
                        <button style="width: 100%; padding: 10px; background-color: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                            Sewa Mobil Ini
                        </button>
                    </a>
                </li>
            @endforeach
        </ul>
        @if($vehicles->isEmpty())
            <p style="color: #ef4444; font-style: italic;">Maaf, saat ini semua armada mobil sedang disewa.</p>
        @endif
    </div>
</x-app-layout>