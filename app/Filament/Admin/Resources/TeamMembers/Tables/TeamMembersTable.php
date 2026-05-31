<?php

namespace App\Filament\Admin\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. FOTO DENGAN TRICK ABSOLUTE URL SEPERTI DRIVER
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->getStateUsing(function ($record) {
                        if ($record->photo) {
                            return asset('storage/' . $record->photo);
                        }
                        // Fallback avatar kalau fotonya kosong
                        return 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=e4dfff&color=140067';
                    }),

                // 2. GABUNGAN NAMA & ROLE
                TextColumn::make('name')
                    ->label('Nama & Role')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn ($record): string => $record->role ?? 'Belum ada role'),

                // 3. WAKTU PEMBUATAN (Tersembunyi secara default)
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Kita kosongkan filternya karena Team Member biasanya 
                // tidak punya status rumit seperti 'available/on_duty'
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