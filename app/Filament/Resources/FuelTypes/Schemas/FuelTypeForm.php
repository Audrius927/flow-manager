<?php

namespace App\Filament\Resources\FuelTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FuelTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Įveskite kuro tipo pavadinimą')
                    ->schema([
                        TextInput::make('title')
                            ->label('Pavadinimas')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(1),
            ]);
    }
}
