<?php

namespace App\Filament\Resources\PartStorages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartStorageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalės informacija')
                    ->description('Pasirinkite detalę ir nustatykite pagrindinius parametrus')
                    ->components([
                        Select::make('part_id')
                            ->label('Detalė')
                            ->relationship('part', 'title')
                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->part_number ? $record->part_number . ' - ' : '') . $record->title)
                            ->searchable(['title', 'part_number'])
                            ->preload()
                            ->required(),
                        TextInput::make('storage_location')
                            ->label('Sandėlio vieta')
                            ->maxLength(100)
                            ->placeholder('Pvz.: A-1-2, B-3-5')
                            ->columnSpan(1),
                        TextInput::make('quantity')
                            ->label('Kiekis')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->maxValue(999999)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Suderinamumas')
                    ->description('Nustatykite su kokiomis automobilių charakteristikomis suderinama detalė')
                    ->components([
                        Select::make('car_mark_filter')
                            ->label('Markė')
                            ->options(\App\Models\CarMark::pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite markę')
                            ->default(fn ($record) => $record?->carModel?->car_mark_id ?? null)
                            ->reactive()
                            ->afterStateUpdated(fn ($set) => $set('car_model_id', null))
                            ->dehydrated(false)
                            ->columnSpan(1),
                        Select::make('car_model_id')
                            ->label('Modelis')
                            ->options(function ($get, $record) {
                                $markId = $get('car_mark_filter');
                                if (!$markId) {
                                    return \App\Models\CarModel::with('mark')
                                        ->get()
                                        ->mapWithKeys(fn ($model) => [$model->id => ($model->mark ? "{$model->mark->title} - {$model->title}" : $model->title)]);
                                }
                                return \App\Models\CarModel::where('car_mark_id', $markId)
                                    ->with('mark')
                                    ->get()
                                    ->mapWithKeys(fn ($model) => [$model->id => ($model->mark ? "{$model->mark->title} - {$model->title}" : $model->title)]);
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite modelį')
                            ->columnSpan(1),
                        Select::make('engine_id')
                            ->label('Variklis')
                            ->relationship('engine', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Visi varikliai')
                            ->columnSpan(1),
                        Select::make('fuel_type_id')
                            ->label('Kuro tipas')
                            ->relationship('fuelType', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Visi kuro tipai')
                            ->columnSpan(1),
                        Select::make('body_type_id')
                            ->label('Kėbulo tipas')
                            ->relationship('bodyType', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Visi kėbulo tipai')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Būklė')
                    ->description('Nustatykite detalės būklę')
                    ->components([
                        Select::make('condition')
                            ->label('Būklė')
                            ->options([
                                'new' => 'Nauja',
                                'used' => 'Naudota',
                                'damaged' => 'Sugedusi',
                                'repaired' => 'Suremontuota',
                            ])
                            ->default('new')
                            ->required()
                            ->columnSpan(1),
                        DateTimePicker::make('received_at')
                            ->label('Gavimo data')
                            ->displayFormat('d/m/Y H:i')
                            ->timezone('Europe/Vilnius')
                            ->columnSpan(1),
                    ])
                    ->columns(2),
                Section::make('Papildoma informacija')
                    ->description('Pastabos ir kiti komentarai')
                    ->components([
                        Textarea::make('notes')
                            ->label('Pastabos')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
