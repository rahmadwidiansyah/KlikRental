<?php

namespace App\Filament\Admin\Resources\Zones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('zone_name')
                    ->required(),
                TextInput::make('additional_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}
