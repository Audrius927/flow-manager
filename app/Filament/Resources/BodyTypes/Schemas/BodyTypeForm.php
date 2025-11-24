<?php

namespace App\Filament\Resources\BodyTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BodyTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Įveskite kėbulo tipo pavadinimą')
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
