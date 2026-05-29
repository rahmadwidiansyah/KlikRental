<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class BookingStatsOverview extends BaseWidget
{
    // Mengatur urutan agar widget tampil paling atas di dashboard
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Hitung total omset bulan ini (ditambah filter tahun agar lebih akurat)
        $omset = Booking::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        return [
            // 1. Total Order Hari Ini
            Stat::make('Order Masuk Hari Ini', Booking::whereDate('created_at', Carbon::today())->count())
                ->description('Jumlah booking yang dibuat hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // 2. Order Dibayar (Menunggu Penjemputan)
            Stat::make('Order Dibayar (Paid)', Booking::where('status', 'paid')->count())
                ->description('Booking lunas, siap jalan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            // 3. Order Aktif (Mobil Sedang Dipakai)
            Stat::make('Mobil Sedang Disewa', Booking::where('status', 'in_use')->count())
                ->description('Status in_use saat ini')
                ->descriptionIcon('heroicon-m-key')
                ->color('warning'),

            // 4. Omset Bulan Ini (Dengan Format Rapi)
            Stat::make('Omset Bulan Ini', $this->formatUang($omset))
                ->description('Total pendapatan kotor bulan ini')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success'),
        ];
    }

    /**
     * Fungsi kustom untuk meringkas angka jutaan/miliaran
     */
    private function formatUang($angka)
    {
        if ($angka >= 1000000000) {
            // Format Miliar (Contoh: Rp 1,5 Miliar)
            return 'Rp ' . rtrim(rtrim(number_format($angka / 1000000000, 2, ',', '.'), '0'), ',') . ' Miliar';
        } elseif ($angka >= 1000000) {
            // Format Juta (Contoh: Rp 15,5 Juta)
            return 'Rp ' . rtrim(rtrim(number_format($angka / 1000000, 2, ',', '.'), '0'), ',') . ' Juta';
        }
        
        // Format normal di bawah 1 Juta (Contoh: Rp 500.000)
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}