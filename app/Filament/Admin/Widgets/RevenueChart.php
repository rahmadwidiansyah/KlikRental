<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    // Hapus kata 'static' di sini. Cukup pakai 'protected ?string'
    protected ?string $heading = 'Grafik Pendapatan Tahun Ini';
    
    // Untuk $sort tetap pakai static
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = [];
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->translatedFormat('M');
            
            $revenue = Booking::where('status', 'completed')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->sum('total_price');
                
            $data[] = $revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $data,
                    'borderColor' => '#10b981', 
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)', 
                    'fill' => true,
                    'tension' => 0.4, 
                ],
            ],
            'labels' => $months, 
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}