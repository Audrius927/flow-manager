<?php

namespace App\Filament\Resources\CarMarks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarMarkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Įveskite automobilių markės pavadinimą')
                    ->components([
                        TextInput::make('title')
                            ->label('Markės pavadinimas')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
