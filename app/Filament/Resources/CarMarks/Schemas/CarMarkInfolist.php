<?php

namespace App\Filament\Resources\CarMarks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarMarkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->components([
                        TextEntry::make('title')
                            ->label('Markės pavadinimas')
                            ->size(TextEntry\TextEntrySize::Large),
                        TextEntry::make('models_count')
                            ->label('Modelių kiekis')
                            ->state(fn ($record) => $record->models()->count())
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(2),
            ]);
    }
}
