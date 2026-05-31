<?php

namespace App\Filament\Admin\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('role')
                    ->label('Role / Jobdesk')
                    ->placeholder('Contoh: Frontend Developer')
                    ->required()
                    ->maxLength(255),
                    
                FileUpload::make('photo')
                    ->label('Unggah Foto')
                    ->image()
                    ->disk('public')
                    ->directory('team-photos') 
                    ->maxSize(2048),
            ]);
    }
}