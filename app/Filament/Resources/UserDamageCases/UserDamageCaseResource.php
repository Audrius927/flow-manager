<?php

namespace App\Filament\Resources\UserDamageCases;

use App\Filament\Resources\UserDamageCases\Pages\ListUserDamageCases;
use App\Filament\Resources\UserDamageCases\Schemas\UserDamageCaseForm;
use App\Filament\Resources\UserDamageCases\Schemas\UserDamageCaseInfolist;
use App\Filament\Resources\UserDamageCases\Tables\UserDamageCasesTable;
use App\Models\UserDamageCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserDamageCaseResource extends Resource
{
    protected static ?string $model = UserDamageCase::class;

    protected static ?string $recordTitleAttribute = null;

    protected static ?string $modelLabel = 'Vartotojo priskyrimas';

    protected static ?string $pluralModelLabel = 'Vartotojų priskyrimai';

    protected static ?string $navigationLabel = 'Vartotojų priskyrimai';

    protected static ?int $navigationSort = 2;


    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    public static function form(Schema $schema): Schema
    {
        return UserDamageCaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserDamageCaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserDamageCasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserDamageCases::route('/'),
        ];
    }
}
