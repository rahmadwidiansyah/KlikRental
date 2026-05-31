<?php

namespace App\Filament\Admin\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Vehicle;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_code')
                    ->required()
                    ->readOnly(),

                Select::make('user_id')
                    ->label('Pelanggan')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // PERBAIKAN: Menampilkan Nama Mobil + Plat Nomor di pilihan dropdown
                Select::make('vehicle_id')
                    ->label('Kendaraan')
                    ->options(function () {
                        return Vehicle::all()->pluck('name', 'id')->map(function ($name, $id) {
                            $vehicle = Vehicle::find($id);
                            $plat = $vehicle->license_plate ? " [{$vehicle->license_plate}]" : '';
                            return "{$name}{$plat}";
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('driver_id')
                    ->label('Supir')
                    ->relationship('driver', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('pickup_zone_id')
                    ->label('Titik Jemput')
                    ->relationship('pickupZone', 'zone_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('dropoff_zone_id')
                    ->label('Titik Kembali')
                    ->relationship('dropoffZone', 'zone_name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('promo_id')
                    ->label('Promo')
                    ->relationship('promo', 'code')
                    ->searchable()
                    ->preload(),

                DateTimePicker::make('start_date')
                    ->required(),

                DateTimePicker::make('end_date')
                    ->required(),

                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),

                TextInput::make('tax_rate')
                    ->required()
                    ->numeric()
                    ->default(11),

                TextInput::make('tax_amount')
                    ->required()
                    ->numeric(),

                TextInput::make('total_price')
                    ->required()
                    ->numeric(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'in_use' => 'In Use',
                        'late' => 'Late',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}