<?php

namespace App\Filament\Admin\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. FOTO KENDARAAN
                ImageColumn::make('image_url')
                    ->label('Foto')
                    ->circular(),

                // 2. NAMA KENDARAAN + SPESIFIKASI (Digabung agar ringkas)
                TextColumn::make('name')
                    ->label('Kendaraan & Spesifikasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): string => "{$record->transmission} • {$record->fuel_type} • {$record->seats} Kursi • {$record->luggage_capacity} Koper"),

                // 3. KELAS + TIPE
                TextColumn::make('class')
                    ->label('Kelas & Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VIP' => 'warning',      // Kuning/Emas untuk VIP
                        'Premium' => 'info',     // Biru untuk Premium
                        'Standard' => 'success', // Hijau untuk Standard
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => $record->type),

                // 4. HARGA
                TextColumn::make('price_per_day')
                    ->label('Harga / Hari')
                    ->money('IDR', locale: 'id') 
                    ->sortable(),

                // 5. STATUS EDITABLE (Bisa langsung diubah tanpa masuk menu edit)
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),

                // WAKTU (Disembunyikan by default)
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter cepat untuk Status Kendaraan
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    ]),
                    
                // Filter cepat untuk Kelas Kendaraan
                SelectFilter::make('class')
                    ->label('Filter Kelas')
                    ->options([
                        'VIP' => 'VIP',
                        'Premium' => 'Premium',
                        'Standard' => 'Standard',
                    ]),
            ])
            ->recordActions([
                // Ikon edit diperkecil biar tabel makin lega
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}