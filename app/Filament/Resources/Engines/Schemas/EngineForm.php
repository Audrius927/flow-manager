<?php

namespace App\Filament\Resources\Engines\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EngineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Įveskite variklio pavadinimą')
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
