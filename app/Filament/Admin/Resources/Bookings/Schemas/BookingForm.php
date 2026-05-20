<?php

namespace App\Filament\Admin\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_code')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('vehicle_id')
                    ->required()
                    ->numeric(),
                TextInput::make('driver_id')
                    ->numeric(),
                TextInput::make('pickup_zone_id')
                    ->required()
                    ->numeric(),
                TextInput::make('dropoff_zone_id')
                    ->required()
                    ->numeric(),
                TextInput::make('promo_id')
                    ->numeric(),
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
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
