<?php

namespace App\Filament\Admin\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. FOTO DRIVER DENGAN TRICK ABSOLUTE URL
                ImageColumn::make('image_url')
                    ->label('Foto')
                    ->circular()
                    // Kita bajak datanya biar Filament membaca URL utuh (http://...)
                    ->getStateUsing(function ($record) {
                        if ($record->image_url) {
                            return asset('storage/' . $record->image_url);
                        }
                        // Fallback avatar kalau fotonya kosong
                        return 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=e4dfff&color=140067';
                    }),

                // 2. GABUNGAN NAMA & NOMOR HP
                TextColumn::make('name')
                    ->label('Nama & Kontak')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->phone_number ?? 'Belum ada nomor'),

                // 3. TARIF HARIAN
                TextColumn::make('daily_rate')
                    ->label('Tarif Harian')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                // 4. STATUS EDITABLE LANGSUNG DI TABEL
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'on_duty' => 'Sedang Bertugas',
                        'inactive' => 'Tidak Aktif',
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
                        'on_duty' => 'Sedang Bertugas',
                        'inactive' => 'Tidak Aktif',
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