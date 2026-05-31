<?php

namespace App\Filament\Admin\Resources\Zones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Zona')
                    ->schema([
                        TextInput::make('zone_name')
                            ->label('Nama Zona / Lokasi')
                            ->required(),
                            
                        TextInput::make('additional_cost')
                            ->label('Biaya Tambahan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp') 
                            ->default(0),
                            
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Matikan jika zona ini sedang tidak bisa diakses.'),
                    ])->columns(2),

                Section::make('Pengaturan Kantor Cabang')
                    ->description('Isi bagian ini HANYA jika zona merupakan kantor fisik.')
                    ->schema([
                        Toggle::make('is_office')
                            ->label('Jadikan sebagai Kantor Cabang')
                            ->default(false)
                            ->live(),
                            
                        Textarea::make('address')
                            ->label('Alamat Lengkap Kantor')
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn ($get) => $get('is_office')),
                            
                        // Input Latitude
                        TextInput::make('latitude')
                            ->label('Latitude (Garis Lintang)')
                            ->numeric()
                            ->helperText('Contoh: -7.0247246')
                            ->visible(fn ($get) => $get('is_office')),
                            
                        // Input Longitude
                        TextInput::make('longitude')
                            ->label('Longitude (Garis Bujur)')
                            ->numeric()
                            ->helperText('Contoh: 110.3470241')
                            ->visible(fn ($get) => $get('is_office')),
                    ])->columns(2),
            ]);
    }
}