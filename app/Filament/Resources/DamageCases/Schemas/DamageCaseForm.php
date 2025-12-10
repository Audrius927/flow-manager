<?php

namespace App\Filament\Resources\DamageCases\Schemas;

use App\Filament\Forms\Components\GoogleMapsAutocomplete;
use App\Models\DamageCase;
use App\Services\DamageCases\DamageCaseFieldPermissionResolver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DamageCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();
        $permissions = app(DamageCaseFieldPermissionResolver::class);
        $canView = fn (string ...$fields): bool => $permissions->canViewAny($user, ...$fields);
        $canEdit = fn (string $field): bool => $permissions->canEditField($user, $field);

        return $schema
            ->components([
                Section::make('Užsakymo informacija')
                    ->description('Visi laukai vienoje formoje')
                    ->schema([
                        TextInput::make('damage_number')
                            ->label('Žalos nr.')
                            ->maxLength(100)
                            ->columnSpan(1)
                            ->visible($canView('damage_number'))
                            ->disabled(fn () => ! $canEdit('damage_number')),
                        Select::make('insurance_company_id')
                            ->label('Draudimo kompanija')
                            ->relationship('insuranceCompany', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite draudimo kompaniją')
                            ->columnSpan(1)
                            ->visible($canView('insurance_company'))
                            ->disabled(fn () => ! $canEdit('insurance_company')),
                        Select::make('product_id')
                            ->label('Produktas')
                            ->options(function () {
                                return \App\Models\Product::whereNull('parent_id')
                                    ->orderBy('title')
                                    ->pluck('title', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite produktą')
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(fn ($set) => $set('product_sub_id', null))
                            ->columnSpan(1)
                            ->visible($canView('product'))
                            ->disabled(fn () => ! $canEdit('product')),
                        Select::make('product_sub_id')
                            ->label('Transporto kategorija')
                            ->options(function (callable $get) {
                                $productId = $get('product_id');
                                if (!$productId) {
                                    return [];
                                }
                                
                                $product = \App\Models\Product::find($productId);
                                if (!$product || $product->title !== 'TRANSPORTAS') {
                                    return [];
                                }
                                
                                return \App\Models\Product::where('parent_id', $productId)
                                    ->orderBy('title')
                                    ->pluck('title', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->visible(function (callable $get) use ($canView) {
                                if (!$canView('product')) {
                                    return false;
                                }
                                $productId = $get('product_id');
                                if (!$productId) {
                                    return false;
                                }
                                $product = \App\Models\Product::find($productId);
                                return $product && $product->title === 'TRANSPORTAS';
                            })
                            ->disabled(function (callable $get) use ($canEdit) {
                                return !$canEdit('product');
                            })
                            ->columnSpan(1),
                        DatePicker::make('order_date')
                            ->label('Užsakymo data')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('order_date'))
                            ->disabled(fn () => ! $canEdit('order_date')),
                        DateTimePicker::make('received_at')
                            ->label('Perėmimo data / laikas')
                            ->displayFormat('Y-m-d H:i')
                            ->timezone('Europe/Vilnius')
                            ->columnSpan(1)
                            ->visible($canView('received_at'))
                            ->disabled(fn () => ! $canEdit('received_at')),
                        Select::make('car_mark_id')
                            ->label('Markė')
                            ->options(\App\Models\CarMark::pluck('title', 'id'))
                            ->optionsLimit(1000)
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite markę')
                            ->reactive()
                            ->afterStateUpdated(fn ($set) => $set('car_model_id', null))
                            ->columnSpan(1)
                            ->visible($canView('car_mark_id'))
                            ->disabled(fn () => ! $canEdit('car_mark_id')),
                        Select::make('car_model_id')
                            ->label('Modelis')
                            ->options(function ($get, $record) {
                                $markId = $get('car_mark_id') ?? $record?->car_mark_id ?? ($record?->carModel?->car_mark_id ?? null);
                                if (!$markId) {
                                    return [];
                                }
                                return \App\Models\CarModel::where('car_mark_id', $markId)
                                    ->with('mark')
                                    ->get()
                                    ->mapWithKeys(fn ($model) => [$model->id => ($model->mark ? "{$model->mark->title} - {$model->title}" : $model->title)]);
                            })
                            ->optionsLimit(1000)
                            ->searchable()
                            ->preload()
                            ->placeholder('Pirma pasirinkite markę')
                            ->disabled(function ($get) use ($canEdit) {
                                if (! $get('car_mark_id')) {
                                    return true;
                                }

                                return ! $canEdit('car_model_id');
                            })
                            ->dehydrated()
                            ->columnSpan(1)
                            ->visible($canView('car_model_id')),
                        TextInput::make('license_plate')
                            ->label('Valst nr.')
                            ->maxLength(20)
                            ->columnSpan(1)
                            ->visible($canView('license_plate'))
                            ->disabled(fn () => ! $canEdit('license_plate')),
                        TextInput::make('first_name')
                            ->label('Vardas')
                            ->maxLength(100)
                            ->columnSpan(1)
                            ->visible($canView('first_name'))
                            ->disabled(fn () => ! $canEdit('first_name')),
                        TextInput::make('last_name')
                            ->label('Pavardė')
                            ->maxLength(100)
                            ->columnSpan(1)
                            ->visible($canView('last_name'))
                            ->disabled(fn () => ! $canEdit('last_name')),
                        TextInput::make('phone')
                            ->label('Tel nr.')
                            ->tel()
                            ->maxLength(20)
                            ->columnSpan(1)
                            ->visible($canView('phone'))
                            ->disabled(fn () => ! $canEdit('phone')),
                        Select::make('city_id')
                            ->label('Miestas')
                            ->relationship('city', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite miestą')
                            ->columnSpan(1)
                            ->visible($canView('received_location'))
                            ->disabled(fn () => ! $canEdit('received_location')),
                        GoogleMapsAutocomplete::make('received_location')
                            ->label('Perėmimo vieta (adresas)')
                            ->maxLength(255)
                            ->placeholder('Pradėkite rašyti adresą...')
                            ->columnSpan(1)
                            ->visible($canView('received_location'))
                            ->disabled(fn () => ! $canEdit('received_location')),
                        GoogleMapsAutocomplete::make('storage_location')
                            ->label('Saugojimo vieta')
                            ->maxLength(255)
                            ->placeholder('Pradėkite rašyti adresą...')
                            ->columnSpan(1)
                            ->visible($canView('storage_location'))
                            ->disabled(fn () => ! $canEdit('storage_location')),
                        DatePicker::make('removed_from_storage_at')
                            ->label('Išvežtas iš saugojimo vietos (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('removed_from_storage_at'))
                            ->disabled(fn () => ! $canEdit('removed_from_storage_at')),
                        DatePicker::make('returned_to_storage_at')
                            ->label('Grąžintas į saugojimo vietą (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('returned_to_storage_at'))
                            ->disabled(fn () => ! $canEdit('returned_to_storage_at')),
                        DatePicker::make('returned_to_client_at')
                            ->label('Grąžintas klientui (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('returned_to_client_at'))
                            ->disabled(fn () => ! $canEdit('returned_to_client_at')),
                        Select::make('repair_company_id')
                            ->label('Remonto įmonė')
                            ->relationship('repairCompany', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pasirinkite remonto įmonę')
                            ->columnSpan(1)
                            ->visible($canView('repair_company'))
                            ->disabled(fn () => ! $canEdit('repair_company')),
                        DatePicker::make('planned_repair_start')
                            ->label('Planuojama remonto pradžia (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('planned_repair_start'))
                            ->disabled(fn () => ! $canEdit('planned_repair_start')),
                        DatePicker::make('planned_repair_end')
                            ->label('Planuojama remonto pabaiga (Data)')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('planned_repair_end'))
                            ->disabled(fn () => ! $canEdit('planned_repair_end')),
                        DatePicker::make('finished_at')
                            ->label('Baigta')
                            ->displayFormat('Y-m-d')
                            ->columnSpan(1)
                            ->visible($canView('finished_at'))
                            ->disabled(fn () => ! $canEdit('finished_at')),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Section::make('Prisegti failai')
                    ->description('Dokumentai ir nuotraukos, susiję su žalos byla')
                    ->schema([
                        FileUpload::make('documents_uploads')
                            ->label('Dokumentai')
                            ->multiple()
                            ->disk('private')
                            ->directory('damage-cases/documents')
                            ->preserveFilenames()
                            ->visibility('private')
                            ->placeholder('Paspauskite „Įkelti dokumentus“ arba nutempkite čia')
                            ->loadingIndicatorPosition('center')
                            ->uploadButtonPosition('center')
                            ->openable()
                            ->downloadable()
                            ->maxFiles(5)
                            ->afterStateHydrated(function (callable $set, ?DamageCase $record) {
                                if ($record) {
                                    $set('documents_uploads', $record->documents->pluck('path')->all());
                                }
                            })
                            ->dehydrateStateUsing(fn ($state) => $state ?? [])
                            ->visible($canView('documents'))
                            ->disabled(fn () => ! $canEdit('documents'))
                            ->helperText('Galima pridėti kelis PDF ar kitus dokumentus.'),
                        FileUpload::make('photos_uploads')
                            ->label('Nuotraukos')
                            ->multiple()
                            ->disk('private')
                            ->directory('damage-cases/photos')
                            ->image()
                            ->imageEditor()
                            ->reorderable()
                            ->visibility('private')
                            ->placeholder('Paspauskite „Įkelti nuotraukas“ arba nutempkite čia')
                            ->loadingIndicatorPosition('center')
                            ->uploadButtonPosition('center')
                            ->openable()
                            ->downloadable()
                            ->maxFiles(5)
                            ->afterStateHydrated(function (callable $set, ?DamageCase $record) {
                                if ($record) {
                                    $set('photos_uploads', $record->photos->pluck('path')->all());
                                }
                            })
                            ->dehydrateStateUsing(fn ($state) => $state ?? [])
                            ->helperText('Galite įkelti nuotraukas tiesiai iš telefono.')
                            ->extraAttributes([
                                'capture' => 'environment',
                                'accept' => 'image/*',
                            ])
                            ->visible($canView('photos'))
                            ->disabled(fn () => ! $canEdit('photos')),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
