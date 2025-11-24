<?php

namespace App\Filament\Resources\PartStorages;

use App\Filament\Resources\PartStorages\Pages\CreatePartStorage;
use App\Filament\Resources\PartStorages\Pages\EditPartStorage;
use App\Filament\Resources\PartStorages\Pages\ListPartStorages;
use App\Filament\Resources\PartStorages\Pages\ViewPartStorage;
use App\Filament\Resources\PartStorages\Schemas\PartStorageForm;
use App\Filament\Resources\PartStorages\Schemas\PartStorageInfolist;
use App\Filament\Resources\PartStorages\Tables\PartStoragesTable;
use App\Models\PartStorage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PartStorageResource extends Resource
{
    protected static ?string $model = PartStorage::class;

    protected static ?string $recordTitleAttribute = 'part_number';

    protected static ?string $modelLabel = 'Automobilio detalė';

    protected static ?string $pluralModelLabel = 'Automobilių detalės';

    protected static ?string $navigationLabel = 'Automobilių detalės';

    protected static ?int $navigationSort = 5;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Schema $schema): Schema
    {
        return PartStorageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartStorageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartStoragesTable::configure($table);
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
            'index' => ListPartStorages::route('/'),
            'create' => CreatePartStorage::route('/create'),
            'view' => ViewPartStorage::route('/{record}'),
            'edit' => EditPartStorage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'part_number',
            'partCategory.title',
            'carModel.title',
        ];
    }
}
