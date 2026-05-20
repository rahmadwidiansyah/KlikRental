<?php

namespace App\Filament\Admin\Resources\Promos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('discount_percentage')
                    ->required()
                    ->numeric(),
                TextInput::make('max_discount')
                    ->required()
                    ->numeric(),
                DatePicker::make('valid_until')
                    ->required(),
            ]);
    }
}
