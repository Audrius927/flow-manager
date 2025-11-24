<?php

namespace App\Filament\Resources\Parts;

use App\Filament\Resources\Parts\Infolists\PartInfolist;
use App\Filament\Resources\Parts\Pages\CreatePart;
use App\Filament\Resources\Parts\Pages\EditPart;
use App\Filament\Resources\Parts\Pages\ListParts;
use App\Filament\Resources\Parts\Pages\ViewPart;
use App\Filament\Resources\Parts\Schemas\PartForm;
use App\Filament\Resources\Parts\Tables\PartsTable;
use App\Models\Part;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartResource extends Resource
{
    protected static ?string $model = Part::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Detalė';

    protected static ?string $pluralModelLabel = 'Detalės';

    protected static ?string $navigationLabel = 'Detalės';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Sandėlys';
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cube';

    public static function form(Schema $schema): Schema
    {
        return PartForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartInfolist::configure($schema);
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
            'index' => ListParts::route('/'),
            'create' => CreatePart::route('/create'),
            'view' => ViewPart::route('/{record}'),
            'edit' => EditPart::route('/{record}/edit'),
        ];
    }
}
