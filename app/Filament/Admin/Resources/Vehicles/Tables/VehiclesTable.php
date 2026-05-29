<?php

namespace App\Filament\Admin\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                // TAMBAHAN: Kolom Class dengan Badge Warna Dinamis
                TextColumn::make('class')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VIP' => 'warning',      // Kuning/Emas untuk VIP
                        'Premium' => 'info',     // Biru untuk Premium
                        'Standard' => 'success', // Hijau untuk Standard
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge(),
                    
                TextColumn::make('transmission')
                    ->badge(),
                    
                TextColumn::make('fuel_type')
                    ->searchable(),
                    
                TextColumn::make('seats')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('luggage_capacity')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('price_per_day')
                    ->money('IDR', locale: 'id') // Diubah dikit biar otomatis pakai format Rupiah (Rp)
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',    // Hijau
                        'rented' => 'warning',       // Kuning
                        'maintenance' => 'danger',   // Merah
                        default => 'gray',
                    }),
                    
                ImageColumn::make('image_url'),
                
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}