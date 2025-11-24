<?php

namespace App\Filament\Resources\CarModels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Pasirinkite markę ir įveskite modelio pavadinimą')
                    ->components([
                        Select::make('car_mark_id')
                            ->label('Markė')
                            ->relationship('mark', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('title')
                            ->label('Modelio pavadinimas')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
