<?php

namespace App\Filament\Resources\DamageCases\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DamageCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Draudimo ir gedimo atvejo duomenys')
                    ->components([
                        TextInput::make('damage_number')
                            ->label('Gedimo numeris')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('insurance_company')
                            ->label('Draudimo bendrovė')
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('product')
                            ->label('Produktas')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        DatePicker::make('order_date')
                            ->label('Užsakymo data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DateTimePicker::make('received_at')
                            ->label('Gavimo data ir laikas')
                            ->displayFormat('Y-m-d H:i')
                            ->timezone('Europe/Vilnius')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Automobilio informacija')
                    ->description('Automobilio duomenys')
                    ->components([
                        Select::make('car_mark_filter')
                            ->label('Markė')
                            ->options(\App\Models\CarMark::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite markę')
                            ->default(function ($get, $record) {
                                if ($record) {
                                    return $record->car_mark_id ?? ($record->carModel?->car_mark_id ?? null);
                                }
                                return null;
                            })
                            ->reactive()
                            ->afterStateUpdated(fn ($set) => $set('car_model_id', null))
                            ->dehydrated(false)
                            ->columnSpan(1),
                        Select::make('car_model_id')
                            ->label('Modelis')
                            ->options(function ($get, $record) {
                                $markId = $get('car_mark_filter');
                                if (!$markId && $record) {
                                    $markId = $record->car_mark_id ?? ($record->carModel?->car_mark_id ?? null);
                                }
                                if (!$markId) {
                                    return [];
                                }
                                return \App\Models\CarModel::where('car_mark_id', $markId)
                                    ->with('mark')
                                    ->get()
                                    ->mapWithKeys(fn ($model) => [$model->id => ($model->mark ? "{$model->mark->title} - {$model->title}" : $model->title)]);
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Pirma pasirinkite markę')
                            ->disabled(fn ($get) => !$get('car_mark_filter'))
                            ->dehydrated()
                            ->columnSpan(1),
                        TextInput::make('license_plate')
                            ->label('Valstybinis numeris')
                            ->maxLength(20)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Kliento informacija')
                    ->description('Kliento kontaktinė informacija')
                    ->components([
                        TextInput::make('first_name')
                            ->label('Vardas')
                            ->maxLength(100)
                            ->columnSpan(1),
                        TextInput::make('last_name')
                            ->label('Pavardė')
                            ->maxLength(100)
                            ->columnSpan(1),
                        TextInput::make('phone')
                            ->label('Telefono numeris')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Sandėliavimo informacija')
                    ->description('Informacija apie sandėliavimą')
                    ->components([
                        TextInput::make('received_location')
                            ->label('Gavimo vieta')
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('storage_location')
                            ->label('Sandėlio vieta')
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('removed_from_storage_at')
                            ->label('Pašalinta iš sandėlio')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('returned_to_storage_at')
                            ->label('Grąžinta į sandėlį')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('returned_to_client_at')
                            ->label('Grąžinta klientui')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),
                Section::make('Remonto informacija')
                    ->description('Remonto proceso duomenys')
                    ->components([
                        TextInput::make('repair_company')
                            ->label('Remonto įmonė')
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('planned_repair_start')
                            ->label('Planuotas remonto pradžios data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('planned_repair_end')
                            ->label('Planuotas remonto pabaigos data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('finished_at')
                            ->label('Baigimo data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
