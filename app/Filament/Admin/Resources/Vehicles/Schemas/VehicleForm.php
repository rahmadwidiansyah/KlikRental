<?php

namespace App\Filament\Admin\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater; 
use Filament\Forms\Components\FileUpload; 
use Filament\Forms\Components\Toggle; 
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kendaraan')
                    ->required()
                    ->maxLength(255),

                // TAMBAHAN: Input Plat Nomor dengan Auto-Generate Plat Semarang (H)
                TextInput::make('license_plate')
                    ->label('Plat Nomor (Nomor Polisi)')
                    ->required()
                    ->maxLength(15)
                    ->placeholder('Contoh: H 1234 ABG')
                    // Logic Auto-Generate khusus sewaktu input data mobil baru
                    ->default(function () {
                        $angkaRandom = rand(1000, 9999); // Angka 4 digit acak
                        $panjangHuruf = rand(2, 3); // Panjang suffix huruf (2 atau 3 karakter)
                        $hurufRandom = '';
                        for ($i = 0; $i < $panjangHuruf; $i++) {
                            $hurufRandom .= chr(rand(65, 90)); // Mengambil karakter huruf kapital A-Z secara acak
                        }
                        return "H {$angkaRandom} {$hurufRandom}";
                    })
                    // Memastikan data yang disimpan ke DB otomatis dikonversi ke huruf kapital
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                
                Select::make('class')
                    ->options([
                        'Standard' => 'Standard',
                        'Premium' => 'Premium',
                        'VIP' => 'VIP',
                    ])
                    ->default('Standard')
                    ->required(),

                Select::make('type')
                    ->options([
                        'SUV' => 'SUV',
                        'MPV' => 'MPV',
                        'Sedan' => 'Sedan',
                        'Hatchback' => 'Hatchback',
                        'Minibus' => 'Minibus',
                    ])
                    ->required(),

                Select::make('transmission')
                    ->options([
                        'Manual' => 'Manual', 
                        'Automatic' => 'Automatic'
                    ])
                    ->required(),
                    
                Select::make('fuel_type')
                    ->options([
                        'Bensin' => 'Bensin', 
                        'Diesel' => 'Diesel', 
                        'Listrik' => 'Listrik'
                    ])
                    ->required(),
                    
                TextInput::make('seats')
                    ->required()
                    ->numeric(),
                    
                TextInput::make('luggage_capacity')
                    ->required()
                    ->numeric(),
                    
                TextInput::make('price_per_day')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                    
                Select::make('status')
                    ->options([
                        'available' => 'Tersedia', 
                        'rented' => 'Disewa', 
                        'maintenance' => 'Perawatan'
                    ])
                    ->required()
                    ->default('available'),
                
                Repeater::make('images') 
                    ->relationship() 
                    ->schema([
                        FileUpload::make('image_url') 
                            ->disk('public')
                            ->image()
                            ->directory('vehicles')
                            ->required(),
                        Toggle::make('is_primary')
                            ->label('Jadikan Foto Utama')
                    ])
                    ->label('Galeri Foto Mobil')
                    ->addActionLabel('Tambah Foto Lain')
                    ->columnSpanFull(),
            ]);
    }
}