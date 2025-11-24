<?php

namespace App\Filament\Resources\PartStorages\Infolists;

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
                    ->description('Pagrindinė informacija apie sandėliavimo įrašą')
                    ->components([
                        TextEntry::make('part.title')
                            ->label('Detalė')
                            ->badge()
                            ->color('primary')
                            ->url(fn ($record) => $record->part ? \App\Filament\Resources\Parts\PartResource::getUrl('view', ['record' => $record->part]) : null)
                            ->openUrlInNewTab()
                            ->columnSpan(1),
                        TextEntry::make('part.part_number')
                            ->label('Detalės numeris')
                            ->badge()
                            ->color('gray')
                            ->copyable()
                            ->copyMessage('Detalės numeris nukopijuotas')
                            ->columnSpan(1),
                        TextEntry::make('storage_location')
                            ->label('Sandėlio vieta')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('quantity')
                            ->label('Kiekis')
                            ->badge()
                            ->color('success')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Suderinamumas')
                    ->description('Automobilių charakteristikos, su kuriomis suderinama detalė')
                    ->components([
                        TextEntry::make('car_model_full')
                            ->label('Markė / Modelis')
                            ->state(function ($record) {
                                if (!$record->carModel) {
                                    return null;
                                }
                                $mark = $record->carModel->mark;
                                return $mark ? "{$mark->title} - {$record->carModel->title}" : $record->carModel->title;
                            })
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('engine.title')
                            ->label('Variklis')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                        TextEntry::make('fuelType.title')
                            ->label('Kuro tipas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                        TextEntry::make('bodyType.title')
                            ->label('Kėbulo tipas')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Būklė')
                    ->description('Detalės būklė')
                    ->components([
                        TextEntry::make('condition')
                            ->label('Būklė')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'new' => 'Nauja',
                                'used' => 'Naudota',
                                'damaged' => 'Sugedusi',
                                'repaired' => 'Suremontuota',
                                default => $state,
                            })
                            ->color(fn ($state) => match ($state) {
                                'new' => 'success',
                                'used' => 'info',
                                'damaged' => 'danger',
                                'repaired' => 'warning',
                                default => 'gray',
                            })
                            ->columnSpan(1),
                        TextEntry::make('received_at')
                            ->label('Gavimo data')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Papildoma informacija')
                    ->description('Pastabos ir komentarai')
                    ->components([
                        TextEntry::make('notes')
                            ->label('Pastabos')
                            ->placeholder('Pastabų nėra')
                            ->columnSpanFull(),
                    ])
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
