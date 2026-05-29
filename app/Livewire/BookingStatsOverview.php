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

    // Supaya widgetnya mengambil full lebar layar, aktifkan ini jika mau:
    // protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
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

            // 4. (Bonus) Total Omset Bulan Ini dari Order yang Selesai
            Stat::make('Omset Bulan Ini', 'Rp ' . number_format(Booking::where('status', 'completed')->whereMonth('created_at', Carbon::now()->month)->sum('total_price'), 0, ',', '.'))
                ->description('Total pendapatan kotor bulan ini')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success'),
        ];
    }
}