<?php

namespace App\Filament\Resources\DamageCases;

use App\Filament\Resources\DamageCases\Pages\CreateDamageCase;
use App\Filament\Resources\DamageCases\Pages\EditDamageCase;
use App\Filament\Resources\DamageCases\Pages\ListDamageCases;
use App\Filament\Resources\DamageCases\Pages\ViewDamageCase;
use App\Filament\Resources\DamageCases\Schemas\DamageCaseForm;
use App\Filament\Resources\DamageCases\Schemas\DamageCaseInfolist;
use App\Filament\Resources\DamageCases\Tables\DamageCasesTable;
use App\Models\DamageCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DamageCaseResource extends Resource
{
    protected static ?string $model = DamageCase::class;

    protected static ?string $recordTitleAttribute = 'damage_number';

    protected static ?string $modelLabel = 'Gedimų atvejis';

    protected static ?string $pluralModelLabel = 'Gedimų atvejai';

    protected static ?string $navigationLabel = 'Gedimų atvejai';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return null; // Pagrindinė grupė arba atskira
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shield-exclamation';

    public static function form(Schema $schema): Schema
    {
        return DamageCaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DamageCaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DamageCasesTable::configure($table);
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
            'index' => ListDamageCases::route('/'),
            'create' => CreateDamageCase::route('/create'),
            'view' => ViewDamageCase::route('/{record}'),
            'edit' => EditDamageCase::route('/{record}/edit'),
        ];
    }
}
