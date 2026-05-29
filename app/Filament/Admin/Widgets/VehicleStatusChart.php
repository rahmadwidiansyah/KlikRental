<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;

class VehicleStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Ketersediaan Armada';
    
    // Set urutan ke-4 (Biar bersebelahan dengan grafik omset di desktop)
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Hitung jumlah mobil berdasarkan statusnya
        $tersedia = Vehicle::where('status', 'available')->count();
        $disewa = Vehicle::where('status', 'rented')->count();
        $perawatan = Vehicle::where('status', 'maintenance')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kendaraan',
                    'data' => [$tersedia, $disewa, $perawatan],
                    'backgroundColor' => [
                        '#10b981', // Hijau (Tersedia)
                        '#f59e0b', // Kuning/Oranye (Disewa)
                        '#ef4444', // Merah (Perawatan)
                    ],
                    'hoverOffset' => 4
                ],
            ],
            'labels' => ['Tersedia', 'Sedang Disewa', 'Perawatan'],
        ];
    }

    protected function getType(): string
    {
        // Menggunakan grafik melingkar (donat)
        return 'doughnut';
    }
}