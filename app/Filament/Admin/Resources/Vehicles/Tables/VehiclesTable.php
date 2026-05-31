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

                // 2. NAMA KENDARAAN + SPESIFIKASI + PLAT NOMOR (Disatukan agar compact)
                TextColumn::make('name')
                    ->label('Kendaraan & Spesifikasi')
                    ->searchable(query: function ($query, string $search) {
                        // Fitur searching cerdas: tabel bisa dicari berdasarkan Nama atau Plat Nomor sekaligus
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('license_plate', 'like', "%{$search}%");
                    })
                    ->sortable()
                    ->weight('bold')
                    // Plat nomor ditampilkan di deskripsi terbawah dengan style tebal pembeda
                    ->description(fn ($record): string => "{$record->transmission} • {$record->fuel_type} • {$record->seats} Kursi • {$record->luggage_capacity} Koper" . ($record->license_plate ? " \n [ {$record->license_plate} ]" : '')),

                // 3. KELAS + TIPE
                TextColumn::make('class')
                    ->label('Kelas & Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VIP' => 'warning',
                        'Premium' => 'info',
                        'Standard' => 'success',
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

                // 5. STATUS EDITABLE
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),

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
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    ]),
                    
                SelectFilter::make('class')
                    ->label('Filter Kelas')
                    ->options([
                        'VIP' => 'VIP',
                        'Premium' => 'Premium',
                        'Standard' => 'Standard',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}