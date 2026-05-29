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
                    ->required()
                    ->maxLength(255),
                
                // Tambahan Select untuk Class Mobil
                Select::make('class')
                    ->options([
                        'Standard' => 'Standard',
                        'Premium' => 'Premium',
                        'VIP' => 'VIP',
                    ])
                    ->default('Standard')
                    ->required(),

                // TextInput diubah menjadi Select sesuai enum database
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
                    ->prefix('Rp'), // Tambahan biar cantik ada tulisan Rp nya
                    
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