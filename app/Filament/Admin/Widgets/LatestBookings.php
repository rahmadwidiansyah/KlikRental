<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Carbon\Carbon;

class LatestBookings extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('booking_code')
                    ->label('Order & Pelanggan')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->user->name ?? 'Unknown'),
                    
                TextColumn::make('vehicle.name')
                    ->label('Kendaraan & Total')
                    ->description(fn ($record): string => 'Rp ' . number_format($record->total_price, 0, ',', '.')),
                    
                TextColumn::make('start_date')
                    ->label('Jadwal Sewa')
                    ->dateTime('d M Y, H:i')
                    ->description(fn ($record): string => 's/d ' . Carbon::parse($record->end_date)->format('d M Y, H:i')),
                    
                // Status Editable langsung di Dashboard
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'in_use' => 'In Use',
                        'late' => 'Late',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->selectablePlaceholder(false),
            ])
            ->paginated(false); 
    }
}