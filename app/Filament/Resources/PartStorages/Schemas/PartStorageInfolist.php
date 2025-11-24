<?php

namespace App\Filament\Resources\PartStorages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartStorageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalės informacija')
                    ->description('Pagrindiniai sandėlyje esančios detalės duomenys')
                    ->columns(2)
                    ->components([
                        TextEntry::make('part_number')
                            ->label('Detalės numeris')
                            ->placeholder('Nenurodytas'),
                        TextEntry::make('partCategory.title')
                            ->label('Kategorija')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('year')
                            ->label('Metai')
                            ->badge()
                            ->color('warning')
                            ->placeholder('Nenurodyta'),
                        TextEntry::make('quantity')
                            ->label('Kiekis')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('notes')
                            ->label('Pastabos')
                            ->columnSpanFull()
                            ->placeholder('Nėra papildomos informacijos'),
                    ]),
                Section::make('Suderinamumas')
                    ->description('Susiejimai su transporto priemonėmis ir parametrais')
                    ->columns(2)
                    ->components([
                        TextEntry::make('carMark.title')
                            ->label('Markė')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta'),
                TextEntry::make('carModel.title')
                            ->label('Modelis')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('engine.title')
                            ->label('Variklis')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('fuelType.title')
                            ->label('Kuro tipas')
                            ->placeholder('Nenurodytas'),
                TextEntry::make('bodyType.title')
                            ->label('Kėbulo tipas')
                            ->placeholder('Nenurodytas'),
                    ]),
                Section::make('Sistemos informacija')
                    ->description('Įrašo sukurimo ir atnaujinimo datos')
                    ->columns(2)
                    ->components([
                TextEntry::make('created_at')
                            ->label('Sukurta')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta'),
                TextEntry::make('updated_at')
                            ->label('Atnaujinta')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta'),
                    ]),
            ]);
    }
}
