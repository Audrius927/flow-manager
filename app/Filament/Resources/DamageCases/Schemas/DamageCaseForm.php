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
                Section::make('Užsakymo informacija')
                    ->description('Visi laukai vienoje formoje')
                    ->schema([
                        TextInput::make('damage_number')
                            ->label('Žalos nr.')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('insurance_company')
                            ->label('Draudimo kompanija')
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('product')
                            ->label('Produktas')
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('order_date')
                            ->label('Užsakymo data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DateTimePicker::make('received_at')
                            ->label('Perėmimo data / laikas')
                            ->displayFormat('Y-m-d H:i')
                            ->timezone('Europe/Vilnius')
                            ->columnSpan(1),
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
                            ->label('Valst nr.')
                            ->maxLength(20)
                            ->columnSpan(1),
                        TextInput::make('first_name')
                            ->label('Vardas')
                            ->maxLength(100)
                            ->columnSpan(1),
                        TextInput::make('last_name')
                            ->label('Pavardė')
                            ->maxLength(100)
                            ->columnSpan(1),
                        TextInput::make('phone')
                            ->label('Tel nr.')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1),
                        TextInput::make('received_location')
                            ->label('Perėmimo vieta (adresas)')
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('storage_location')
                            ->label('Saugojimo vieta')
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('removed_from_storage_at')
                            ->label('Išvežtas iš saugojimo vietos (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('returned_to_storage_at')
                            ->label('Grąžintas į saugojimo vietą (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('returned_to_client_at')
                            ->label('Grąžintas klientui (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        TextInput::make('repair_company')
                            ->label('Remonto įmonė')
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('planned_repair_start')
                            ->label('Planuojama remonto pradžia (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('planned_repair_end')
                            ->label('Planuojama remonto pabaiga (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                        DatePicker::make('finished_at')
                            ->label('Baigta')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
}
