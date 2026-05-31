<?php

namespace App\Filament\Admin\Resources\Zones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn; // TAMBAHAN
use Filament\Tables\Table;

class ZonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. NAMA ZONA + IKON
                TextColumn::make('zone_name')
                    ->label('Nama Zona')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-m-map-pin')
                    ->iconColor('primary')
                    ->description(fn ($record): string => $record->is_office ? '🏢 Kantor Cabang' : '📍 Titik Antar/Jemput'),

                // 2. BIAYA TAMBAHAN
                TextColumn::make('additional_cost')
                    ->label('Biaya Tambahan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                // 3. TOGGLE KANTOR (Bisa di-klik langsung dari tabel)
                ToggleColumn::make('is_office')
                    ->label('Status Kantor'),

                // 4. TOGGLE AKTIF (Bisa di-klik langsung dari tabel)
                ToggleColumn::make('is_active')
                    ->label('Status Aktif'),

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
                EditAction::make()->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('zone_name', 'asc');
    }
}