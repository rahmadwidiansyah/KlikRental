<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    // Set urutan ke-2 (di bawah Stats Overview)
    protected static ?int $sort = 2;

    // Buat widget ini mengambil lebar penuh layar (full width)
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Mengambil 5 data transaksi terbaru
                Booking::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan'),
                    
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Kendaraan'),
                    
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tgl Ambil')
                    ->dateTime('d M Y H:i'),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Bayar')
                    ->money('IDR', locale: 'id'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'paid' => 'info',
                        'in_use' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'primary',
                    }),
            ])
            // Matikan paginasi karena kita cuma mau lihat 5 data terbaru di dashboard
            ->paginated(false); 
    }
}