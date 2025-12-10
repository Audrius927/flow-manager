<?php

namespace App\Filament\Resources\PartStorages\Schemas;

use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\PartStorage as PartStorageModel;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartStorageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Automobilio detalės informacija')
                    ->schema([
                        Select::make('car_mark_filter')
                            ->label('Markė')
                            ->options(fn() => CarMark::orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (callable $set, ?PartStorageModel $record): void {
                                if ($record?->carModel?->car_mark_id) {
                                    $set('car_mark_filter', $record->carModel->car_mark_id);
                                }
                            })
                            ->placeholder('Pasirinkite markę'),
                        Select::make('car_model_id')
                            ->label('Modelis')
                            ->options(function (callable $get) {
                                $markId = $get('car_mark_filter');

                                return CarModel::query()
                                    ->when($markId, fn($query) => $query->where('car_mark_id', $markId))
                                    ->orderBy('title')
                                    ->pluck('title', 'id');
                            })
                            ->disabled(fn(callable $get) => blank($get('car_mark_filter')))
                            ->searchable()
                            ->placeholder('Pasirinkite modelį'),
                        Select::make('engine_id')
                            ->label('Variklis')
                            ->relationship('engine', 'title')
                            ->optionsLimit(1000)
                            ->searchable()
                            ->preload(),
                        Select::make('fuel_type_id')
                            ->label('Kuro tipas')
                            ->relationship('fuelType', 'title')
                            ->searchable()
                            ->preload(),
                        Select::make('body_type_id')
                            ->label('Kėbulo tipas')
                            ->relationship('bodyType', 'title')
                            ->searchable()
                            ->preload(),
                        Select::make('year')
                            ->label('Metai')
                            ->options(function () {
                                $currentYear = now()->year;
                                $years = [];
                                for ($year = 1900; $year <= $currentYear + 1; $year++) {
                                    $years[$year] = $year;
                                }
                                return array_reverse($years, true);
                            })
                            ->searchable()
                            ->placeholder('Pasirinkite metus'),
                        TextInput::make('part_number')
                            ->label('Detalės numeris')
                            ->placeholder('PVZ: 7M3 123 456')
                            ->maxLength(100),
                        TextInput::make('vin_code')
                            ->label('VIN kodas')
                            ->placeholder('PVZ: WVWZZZ1JZXW000001')
                            ->maxLength(50),
                        SelectTree::make('part_category_id')
                            ->label('Detalės kategorija')
                            ->relationship('partCategory', 'title', 'parent_id')
                            ->searchable()
                            ->required()
                            ->placeholder('Pasirinkite kategoriją'),
                        TextInput::make('quantity')
                            ->label('Kiekis')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(999999)
                            ->default(1)
                            ->required(),
                        Textarea::make('notes')
                            ->label('Pastabos')
                            ->rows(4)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ])
                    ->columnSpanFull(),
                Section::make('Nuotraukos')
                    ->description('Detalės nuotraukos')
                    ->schema([
                        FileUpload::make('images_uploads')
                            ->label('Nuotraukos')
                            ->multiple()
                            ->disk('private')
                            ->directory('part-storages/images')
                            ->image()
                            ->imageEditor()
                            ->reorderable()
                            ->visibility('private')
                            ->placeholder('Paspauskite „Įkelti nuotraukas" arba nutempkite čia')
                            ->loadingIndicatorPosition('center')
                            ->uploadButtonPosition('center')
                            ->openable()
                            ->downloadable()
                            ->maxFiles(10)
                            ->afterStateHydrated(function (callable $set, ?PartStorageModel $record) {
                                if ($record) {
                                    $set('images_uploads', $record->images->pluck('path')->all());
                                }
                            })
                            ->dehydrateStateUsing(fn ($state) => $state ?? [])
                            ->helperText('Galite įkelti nuotraukas tiesiai iš telefono.')
                            ->extraAttributes([
                                'capture' => 'environment',
                                'accept' => 'image/*',
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
