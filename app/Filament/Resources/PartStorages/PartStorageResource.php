<?php

namespace App\Filament\Resources\PartStorages;

use App\Filament\Resources\PartStorages\Infolists\PartStorageInfolist;
use App\Filament\Resources\PartStorages\Pages\CreatePartStorage;
use App\Filament\Resources\PartStorages\Pages\EditPartStorage;
use App\Filament\Resources\PartStorages\Pages\ListPartStorages;
use App\Filament\Resources\PartStorages\Pages\ViewPartStorage;
use App\Filament\Resources\PartStorages\Schemas\PartStorageForm;
use App\Filament\Resources\PartStorages\Tables\PartStoragesTable;
use App\Models\PartStorage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartStorageResource extends Resource
{
    protected static ?string $model = PartStorage::class;

    protected static ?string $recordTitleAttribute = 'storage_location';

    protected static ?string $modelLabel = 'Detalės sandėliavimas';

    protected static ?string $pluralModelLabel = 'Detalių sandėliavimas';

    protected static ?string $navigationLabel = 'Detalių sandėliavimas';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'Sandėlys';
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Schema $schema): Schema
    {
        return PartStorageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartStoragesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartStorageInfolist::configure($schema);
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
}
