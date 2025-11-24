<?php

namespace App\Filament\Resources\Parts\Schemas;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->components([
                        TextInput::make('part_number')
                            ->label('Detalės numeris')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('title')
                            ->label('Pavadinimas')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        SelectTree::make('part_category_id')
                            ->label('Detalių kategorija')
                            ->relationship('partCategory', 'title', 'parent_id')
                            ->placeholder('Pasirinkite kategoriją (nebūtina)')
                            ->searchable()
                            ->multiple(false)
                            ->independent(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Papildoma informacija')
                    ->components([
                        TextInput::make('manufacturer')
                            ->label('Gamintojas')
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('price')
                            ->label('Kaina (€)')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(999999.99)
                            ->columnSpan(1),
                        Textarea::make('description')
                            ->label('Aprašymas')
                            ->rows(4)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
