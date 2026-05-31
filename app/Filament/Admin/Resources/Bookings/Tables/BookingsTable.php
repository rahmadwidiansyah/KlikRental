<?php

namespace App\Filament\Admin\Resources\Bookings\Tables;

// PERHATIKAN: Namespace sudah dikembalikan sesuai kode aslimu
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

                // 2. GABUNGAN KENDARAAN & TOTAL HARGA
                TextColumn::make('vehicle.name')
                    ->label('Kendaraan & Total')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => 'Rp ' . number_format($record->total_price, 0, ',', '.')),

                // 3. JADWAL SEWA (Tanggal Ambil di atas, Tanggal Kembali di bawah)
                TextColumn::make('start_date')
                    ->label('Jadwal Sewa')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(fn ($record): string => 's/d ' . Carbon::parse($record->end_date)->format('d M Y, H:i')),

                // 4. STATUS (Bisa di-klik dan diubah langsung tanpa masuk ke Edit)
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
                // Filter Status agar shortcut dari Dashboard bisa berjalan
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
            // PERHATIKAN: Menggunakan recordActions sesuai kode aslimu
            ->recordActions([
                EditAction::make()->iconButton(),
            ])
            // PERHATIKAN: Menggunakan toolbarActions sesuai kode aslimu
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}