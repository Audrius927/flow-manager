<?php

namespace App\Filament\Resources\PartCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Įveskite detalių kategorijos informaciją')
                    ->schema([
                        TextInput::make('title')
                            ->label('Pavadinimas')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(1),
                Section::make('Hierarchija')
                    ->description('Nustatykite kategorijos hierarchiją')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Tėvinė kategorija')
                            ->relationship('parent', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite tėvinę kategoriją'),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }
}
