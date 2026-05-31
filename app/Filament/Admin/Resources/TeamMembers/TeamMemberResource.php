<?php

namespace App\Filament\Admin\Resources\TeamMembers; 

use App\Filament\Admin\Resources\TeamMembers\Pages; 
use App\Models\TeamMember;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

// PASTIKAN DUA BARIS INI ADA AGAR LARAVEL TAHU LOKASI FILE TABLE DAN FORM-NYA
use App\Filament\Admin\Resources\TeamMembers\Schemas\TeamMemberForm;
use App\Filament\Admin\Resources\TeamMembers\Tables\TeamMembersTable;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Tim Pengembang';
    protected static ?string $pluralModelLabel = 'Tim Pengembang';

    public static function form(Schema $schema): Schema
    {
        return TeamMemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamMembersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}