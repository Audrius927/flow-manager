<?php

namespace App\Filament\Resources\CarMarks;

use App\Filament\Resources\CarMarks\Pages\CreateCarMark;
use App\Filament\Resources\CarMarks\Pages\EditCarMark;
use App\Filament\Resources\CarMarks\Pages\ListCarMarks;
use App\Filament\Resources\CarMarks\Pages\ViewCarMark;
use App\Filament\Resources\CarMarks\Schemas\CarMarkForm;
use App\Filament\Resources\CarMarks\Schemas\CarMarkInfolist;
use App\Filament\Resources\CarMarks\Tables\CarMarksTable;
use App\Models\CarMark;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CarMarkResource extends Resource
{
    protected static ?string $model = CarMark::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Automobilių markė';

    protected static ?string $pluralModelLabel = 'Automobilių markės';

    protected static ?string $navigationLabel = 'Markės';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Automobilių detalės';
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-flag';

    public static function form(Schema $schema): Schema
    {
        return CarMarkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CarMarkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarMarksTable::configure($table);
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
            'index' => ListCarMarks::route('/'),
            'create' => CreateCarMark::route('/create'),
            'view' => ViewCarMark::route('/{record}'),
            'edit' => EditCarMark::route('/{record}/edit'),
        ];
    }
}
