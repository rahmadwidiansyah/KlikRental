<?php

namespace App\Filament\Admin\Resources\Promos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;

class PromosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. GABUNGAN KODE PROMO & PERSENTASE DISKON
                TextColumn::make('code')
                    ->label('Kode Promo & Diskon')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary')
                    ->description(fn ($record): string => 'Diskon: ' . $record->discount_percentage . '%'),
                
                // 2. GABUNGAN MAKSIMAL DISKON & MASA BERLAKU
                TextColumn::make('max_discount')
                    ->label('Maks. Diskon & Masa Berlaku')
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->description(fn ($record): string => 'S/d: ' . Carbon::parse($record->valid_until)->translatedFormat('d M Y')),
                
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
                EditAction::make()->iconButton(), // Diperkecil jadi ikon
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}