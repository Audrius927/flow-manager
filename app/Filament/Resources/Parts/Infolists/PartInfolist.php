<?php

namespace App\Filament\Resources\Parts\Infolists;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Detalės identifikavimo duomenys')
                    ->components([
                        TextEntry::make('part_number')
                            ->label('Detalės numeris')
                            ->badge()
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Detalės numeris nukopijuotas')
                            ->columnSpan(1),
                        TextEntry::make('title')
                            ->label('Pavadinimas')
                            ->columnSpan(1),
                        TextEntry::make('partCategory.title')
                            ->label('Detalių kategorija')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nepriskirta kategorija')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Kainos ir gamintojo informacija')
                    ->description('Finansinė ir gamintojo detalės informacija')
                    ->components([
                        TextEntry::make('manufacturer')
                            ->label('Gamintojas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas gamintojas')
                            ->columnSpan(1),
                        TextEntry::make('price')
                            ->label('Kaina')
                            ->money('EUR')
                            ->color('success')
                            ->placeholder('Kaina nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('description')
                            ->label('Aprašymas')
                            ->placeholder('Aprašymas nesukurta')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Sandėliavimo statistika')
                    ->description('Informacija apie detalės buvimą sandėlyje')
                    ->components([
                        TextEntry::make('storages_count')
                            ->label('Sandėliavimo įrašų kiekis')
                            ->state(fn ($record) => $record->storages()->count())
                            ->badge()
                            ->color('info')
                            ->columnSpan(1),
                        TextEntry::make('total_quantity')
                            ->label('Bendras kiekis sandėlyje')
                            ->state(function ($record) {
                                return $record->storages()->sum('quantity') ?? 0;
                            })
                            ->badge()
                            ->color('success')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Sistemos informacija')
                    ->description('Metaduomenys apie įrašą')
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Sukurta')
                            ->dateTime('Y-m-d H:i:s')
                            ->placeholder('Nėra duomenų')
                            ->columnSpan(1),
                        TextEntry::make('updated_at')
                            ->label('Atnaujinta')
                            ->dateTime('Y-m-d H:i:s')
                            ->placeholder('Nėra duomenų')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
            ]);
    }
}
