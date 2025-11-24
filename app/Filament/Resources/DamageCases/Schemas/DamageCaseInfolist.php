<?php

namespace App\Filament\Resources\DamageCases\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DamageCaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Draudimo ir gedimo atvejo duomenys')
                    ->components([
                        TextEntry::make('damage_number')
                            ->label('Gedimo numeris')
                            ->badge()
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Gedimo numeris nukopijuotas')
                            ->columnSpan(1),
                        TextEntry::make('insurance_company')
                            ->label('Draudimo bendrovė')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('product')
                            ->label('Produktas')
                            ->placeholder('Nenurodytas')
                            ->columnSpanFull(),
                        TextEntry::make('order_date')
                            ->label('Užsakymo data')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('received_at')
                            ->label('Gavimo data ir laikas')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Automobilio informacija')
                    ->description('Automobilio duomenys')
                    ->components([
                        TextEntry::make('carMark.title')
                            ->label('Markė')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('carModel.title')
                            ->label('Modelis')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                        TextEntry::make('license_plate')
                            ->label('Valstybinis numeris')
                            ->badge()
                            ->color('info')
                            ->copyable()
                            ->copyMessage('Valstybinis numeris nukopijuotas')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Kliento informacija')
                    ->description('Kliento kontaktinė informacija')
                    ->components([
                        TextEntry::make('first_name')
                            ->label('Vardas')
                            ->placeholder('Nenurodytas')
                            ->columnSpan(1),
                        TextEntry::make('last_name')
                            ->label('Pavardė')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('phone')
                            ->label('Telefono numeris')
                            ->placeholder('Nenurodytas')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Sandėliavimo informacija')
                    ->description('Informacija apie sandėliavimą')
                    ->components([
                        TextEntry::make('received_location')
                            ->label('Gavimo vieta')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('storage_location')
                            ->label('Sandėlio vieta')
                            ->badge()
                            ->color('info')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('removed_from_storage_at')
                            ->label('Pašalinta iš sandėlio')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('returned_to_storage_at')
                            ->label('Grąžinta į sandėlį')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('returned_to_client_at')
                            ->label('Grąžinta klientui')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),
                Section::make('Remonto informacija')
                    ->description('Remonto proceso duomenys')
                    ->components([
                        TextEntry::make('repair_company')
                            ->label('Remonto įmonė')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('planned_repair_start')
                            ->label('Planuotas remonto pradžios data')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('planned_repair_end')
                            ->label('Planuotas remonto pabaigos data')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
                            ->columnSpan(1),
                        TextEntry::make('finished_at')
                            ->label('Baigimo data')
                            ->date('Y-m-d')
                            ->placeholder('Nenurodyta')
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
