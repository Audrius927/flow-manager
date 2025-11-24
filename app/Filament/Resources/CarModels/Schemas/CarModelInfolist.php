<?php

namespace App\Filament\Resources\CarModels\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarModelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->components([
                        TextEntry::make('mark.title')
                            ->label('Markė')
                            ->badge()
                            ->color('info')
                            ->columnSpan(1),
                        TextEntry::make('title')
                            ->label('Modelio pavadinimas')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
