<?php

namespace App\Filament\Admin\Resources\Drivers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select; 
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Supir')
                    ->required(),
                    
                TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required(),
                    
                TextInput::make('daily_rate')
                    ->label('Tarif Harian (Rp)')
                    ->required()
                    ->numeric(),
                    
                FileUpload::make('image_url')
                    ->label('Unggah Foto')
                    ->image()
                    ->directory('drivers') 
                    ->maxSize(2048),
                    
                Select::make('status')
                    ->label('Status Ketersediaan')
                    ->options([
                        'available' => 'Tersedia',
                        'on_duty' => 'Sedang Bertugas',
                        'inactive' => 'Tidak Aktif',
                    ])
                    ->default('available')
                    ->required()
            ]);
    }
}