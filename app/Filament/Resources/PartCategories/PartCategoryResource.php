<?php

namespace App\Filament\Resources\PartCategories;

use App\Filament\Resources\PartCategories\Pages\CreatePartCategory;
use App\Filament\Resources\PartCategories\Pages\EditPartCategory;
use App\Filament\Resources\PartCategories\Pages\ListPartCategories;
use App\Filament\Resources\PartCategories\Schemas\PartCategoryForm;
use App\Filament\Resources\PartCategories\Tables\PartCategoriesTable;
use App\Models\PartCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PartCategoryResource extends Resource
{
    protected static ?string $model = PartCategory::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'Detalių kategorija';

    protected static ?string $pluralModelLabel = 'Detalių kategorijos';

    protected static ?string $navigationLabel = 'Detalių kategorijos';

    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return 'Automobilių detalės';
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Schema $schema): Schema
    {
        return PartCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartCategoriesTable::configure($table);
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
            'index' => ListPartCategories::route('/'),
            'create' => CreatePartCategory::route('/create'),
            'edit' => EditPartCategory::route('/{record}/edit'),
        ];
    }
}
