<?php

namespace App\Filament\Admin\Resources\Bookings\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Carbon\Carbon;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. GABUNGAN KODE ORDER & PELANGGAN
                TextColumn::make('booking_code')
                    ->label('Order & Pelanggan')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->user->name ?? 'Unknown'),

                // 2. GABUNGAN KENDARAAN, PLAT NOMOR & TOTAL HARGA (Diperbarui)
                TextColumn::make('vehicle.name')
                    ->label('Kendaraan & Total')
                    ->searchable(query: function ($query, string $search) {
                        // Admin bisa cari data sewa berdasarkan nama mobil atau plat nomornya langsung
                        $query->whereHas('vehicle', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('license_plate', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->weight('bold')
                    // Memunculkan Plat Nomor dan Total Harga di deskripsi kolom kendaraan
                    ->description(fn ($record): string => ($record->vehicle->license_plate ? "[ {$record->vehicle->license_plate} ] • " : '') . 'Rp ' . number_format($record->total_price, 0, ',', '.')),

                // 3. JADWAL SEWA
                TextColumn::make('start_date')
                    ->label('Jadwal Sewa')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn ($record): string => 's/d ' . Carbon::parse($record->end_date)->format('d M Y, H:i')),

                // 4. STATUS
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
                    ->selectablePlaceholder(false)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'in_use' => 'In Use',
                        'late' => 'Late',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                EditAction::make()->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}