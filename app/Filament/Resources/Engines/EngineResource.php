<?php

namespace App\Filament\Resources\Engines;

use App\Filament\Resources\Engines\Pages\CreateEngine;
use App\Filament\Resources\Engines\Pages\EditEngine;
use App\Filament\Resources\Engines\Pages\ListEngines;
use App\Filament\Resources\Engines\Schemas\EngineForm;
use App\Filament\Resources\Engines\Tables\EnginesTable;
use App\Models\Engine;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EngineResource extends Resource
{
    protected static ?string $model = Engine::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Variklis';

    protected static ?string $pluralModelLabel = 'Varikliai';

    protected static ?string $navigationLabel = 'Varikliai';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Automobilių detalės';
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Schema $schema): Schema
    {
        return EngineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnginesTable::configure($table);
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
            'index' => ListEngines::route('/'),
            'create' => CreateEngine::route('/create'),
            'edit' => EditEngine::route('/{record}/edit'),
        ];
    }
}
