<x-app-layout>
    <section class="py-10 bg-background min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-surface border border-outline-variant/50 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="font-montserrat text-[24px] font-bold text-on-surface leading-tight">
                        Katalog Armada {{ request('class') && request('class') !== 'all' ? 'Kelas ' . request('class') : 'Lengkap' }}
                    </h1>
                    <p class="font-inter text-[14px] text-on-surface-variant">Pilih kendaraan yang paling sesuai untuk perjalanan Anda.</p>
                </div>
            </div>

            @if($vehicles->isEmpty())
                <div class="bg-surface text-center py-10 rounded-2xl border border-outline-variant/50">
                    <p class="font-inter text-on-surface-variant">Belum ada kendaraan di kategori ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($vehicles as $car)
                        <!-- COPY CARD MOBIL DARI DASHBOARD KE SINI -->
                        <a href="{{ route('vehicle.show', $car->id) }}" class="vehicle-card block bg-surface border border-outline-variant/60 rounded-xl overflow-hidden transition-all duration-300 premium-shadow group relative flex flex-col hover:border-primary/50 hover:shadow-lg">
                            <div class="h-44 overflow-hidden bg-surface-container flex items-center justify-center p-4 relative">
                                <img src="{{ isset($car->primaryImage) && $car->primaryImage ? Storage::url($car->primaryImage->image_url) : 'https://placehold.co/400x250?text=Mobil' }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-4 flex-grow flex flex-col">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-montserrat text-[16px] font-bold text-on-surface mb-0.5 line-clamp-1">{{ $car->name }}</h3>
                                        <p class="font-inter text-on-surface-variant text-[12px] flex gap-2">
                                            <span class="font-semibold">{{ $car->class }}</span> • {{ $car->transmission }}
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0 ml-2">
                                        <span class="font-inter text-[10px] text-on-surface-variant block">Mulai dari</span>
                                        <span class="font-montserrat text-[15px] font-bold text-primary">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full mt-auto bg-primary/5 text-primary font-inter font-bold text-[13px] py-2.5 rounded-lg group-hover:bg-primary group-hover:text-white transition-all flex items-center justify-center gap-1.5">
                                    Pesan Sekarang
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $vehicles->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>
</x-app-layout>