<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use App\Filament\Admin\Resources\Bookings\BookingResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class BookingStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $omset = Booking::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        return [
            Stat::make('Order Masuk Hari Ini', Booking::whereDate('created_at', Carbon::today())->count())
                ->description('Lihat semua order hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                // Shortcut ke tabel Booking tanpa filter status (semua order)
                ->url(BookingResource::getUrl('index')), 

            Stat::make('Order Dibayar (Paid)', Booking::where('status', 'paid')->count())
                ->description('Klik untuk proses serah terima')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                // Shortcut otomatis filter tabel ke status 'paid'
                ->url(BookingResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'paid']]])),

            Stat::make('Mobil Sedang Disewa', Booking::where('status', 'in_use')->count())
                ->description('Klik untuk pantau mobil keluar')
                ->descriptionIcon('heroicon-m-key')
                ->color('warning')
                // Shortcut otomatis filter tabel ke status 'in_use'
                ->url(BookingResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'in_use']]])),

            Stat::make('Omset Bulan Ini', $this->formatUang($omset))
                ->description('Total pendapatan kotor bulan ini')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success'),
        ];
    }

    private function formatUang($angka)
    {
        if ($angka >= 1000000000) {
            return 'Rp ' . rtrim(rtrim(number_format($angka / 1000000000, 2, ',', '.'), '0'), ',') . ' Miliar';
        } elseif ($angka >= 1000000) {
            return 'Rp ' . rtrim(rtrim(number_format($angka / 1000000, 2, ',', '.'), '0'), ',') . ' Juta';
        }
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}