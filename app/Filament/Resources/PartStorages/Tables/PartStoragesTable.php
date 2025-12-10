<?php

namespace App\Filament\Resources\PartStorages\Tables;

use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\Engine;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PartStoragesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('part_number')
                    ->label('Detalės kodas')
                    ->placeholder('Nenurodytas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vin_code')
                    ->label('VIN kodas')
                    ->placeholder('Nenurodytas')
                    ->copyable()
                    ->copyMessage('VIN kodas nukopijuotas')
                    ->copyMessageDuration(1500),
                TextColumn::make('partCategory.title')
                    ->label('Detalės kategorija')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('carMark.title')
                    ->label('Markė')
                    ->badge()
                    ->color('info')
                    ->placeholder('Nenurodyta')
                    ->toggleable(),
                TextColumn::make('carModel.title')
                    ->label('Modelis')
                    ->placeholder('Nenurodytas')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('engine.title')
                    ->label('Variklis')
                    ->placeholder('Nenurodytas')
                    ->toggleable(),
                TextColumn::make('fuelType.title')
                    ->label('Kuro tipas')
                    ->placeholder('Nenurodytas')
                    ->toggleable(),
                TextColumn::make('bodyType.title')
                    ->label('Kėbulo tipas')
                    ->placeholder('Nenurodytas')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('year')
                    ->label('Metai')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label('Kiekis')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Pastabos')
                    ->limit(40)
                    ->placeholder('Nėra')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Sukurta')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atnaujinta')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('part_category_id')
                    ->label('Detalės kategorija')
                    ->form([
                        SelectTree::make('part_category_id')
                            ->label('Detalės kategorija')
                            ->relationship('partCategory', 'title', 'parent_id')
                            ->searchable()
                            ->placeholder('Pasirinkite kategoriją'),
                    ])
                    ->columnSpan(1)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['part_category_id'] ?? null,
                            fn (Builder $query, $categoryId) => $query->where('part_category_id', $categoryId),
                        );
                    }),
                Filter::make('vehicle')
                    ->label('Markė ir modelis')
                    ->schema([
                        Select::make('car_mark_id')
                            ->label('Markė')
                            ->options(fn () => CarMark::orderBy('title')->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->optionsLimit(2000)
                            ->live()
                            ->placeholder('Visos markės'),
                        Select::make('car_model_id')
                            ->label('Modelis')
                            ->options(function (callable $get) {
                                $markId = $get('car_mark_id');

                                return CarModel::query()
                                    ->when($markId, fn (Builder $query) => $query->where('car_mark_id', $markId))
                                    ->orderBy('title')
                                    ->pluck('title', 'id');
                            })
                            ->disabled(fn (callable $get) => blank($get('car_mark_id')))
                            ->optionsLimit(2000)
                            ->searchable()
                            ->placeholder('Visi modeliai'),
                    ])
                    ->columns(2)
                    ->columnSpan(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['car_mark_id'] ?? null,
                                fn (Builder $query, $markId) => $query->whereHas(
                                    'carModel',
                                    fn (Builder $modelQuery) => $modelQuery->where('car_mark_id', $markId),
                                ),
                            )
                            ->when(
                                $data['car_model_id'] ?? null,
                                fn (Builder $query, $modelId) => $query->where('car_model_id', $modelId),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['car_model_id'])) {
                            $model = CarModel::find($data['car_model_id']);
                            if ($model) {
                                return "Modelis: {$model->title}";
                            }
                        }

                        if (!empty($data['car_mark_id'])) {
                            $mark = CarMark::find($data['car_mark_id']);
                            if ($mark) {
                                return "Markė: {$mark->title}";
                            }
                        }

                        return null;
                    }),
                Filter::make('engine')
                    ->label('Variklis')
                    ->form([
                        Select::make('engine_from')
                            ->label('Variklis nuo')
                            ->options(function () {
                                return Engine::orderBy('title')->pluck('title', 'id');
                            })
                            ->optionsLimit(1000)
                            ->searchable()
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Išvalyti "iki" jei jis mažesnis nei "nuo"
                                $set('engine_to', null);
                            })
                            ->placeholder('Nuo '),
                        Select::make('engine_to')
                            ->label('Variklis iki')
                            ->options(function (callable $get) {
                                $fromId = $get('engine_from');
                                
                                return Engine::query()
                                    ->when($fromId, fn ($query) => $query->where('id', '>=', $fromId))
                                    ->orderBy('title')
                                    ->pluck('title', 'id');
                            })
                            ->optionsLimit(1000)
                            ->disabled(fn (callable $get) => blank($get('engine_from')))
                            ->searchable()
                            ->placeholder('Iki '),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        $fromId = $data['engine_from'] ?? null;
                        $toId = $data['engine_to'] ?? null;
                        
                        // Jei "iki" mažesnė nei "nuo", ignoruoti "iki"
                        if ($fromId && $toId && $toId < $fromId) {
                            $toId = null;
                        }
                        
                        return $query
                            ->when(
                                $fromId,
                                fn (Builder $query, $engineId) => $query->where('engine_id', '>=', $engineId),
                            )
                            ->when(
                                $toId,
                                fn (Builder $query, $engineId) => $query->where('engine_id', '<=', $engineId),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['engine_from'] ?? null) {
                            $engine = Engine::find($data['engine_from']);
                            if ($engine) {
                                $indicators['engine_from'] = 'Variklis nuo: ' . $engine->title;
                            }
                        }
                        if ($data['engine_to'] ?? null) {
                            $engine = Engine::find($data['engine_to']);
                            if ($engine) {
                                $indicators['engine_to'] = 'Variklis iki: ' . $engine->title;
                            }
                        }
                        return $indicators;
                    }),
                Filter::make('year')
                    ->label('Metai')
                    ->form([
                        Select::make('year_from')
                            ->label('Metai nuo')
                            ->options(function () {
                                $currentYear = now()->year;
                                $startYear = 1900;
                                $years = [];
                                for ($year = $currentYear + 1; $year >= $startYear; $year--) {
                                    $years[$year] = $year;
                                }
                                return $years;
                            })
                            ->optionsLimit(1000)
                            ->searchable()
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // Išvalyti "iki" jei jis mažesnis nei "nuo"
                                $set('year_to', null);
                            })
                            ->placeholder('Nuo '),
                        Select::make('year_to')
                            ->label('Metai iki')
                            ->options(function (callable $get) {
                                $currentYear = now()->year;
                                $startYear = 1900;
                                $fromYear = $get('year_from');
                                
                                $years = [];
                                $minYear = $fromYear ? max($fromYear, $startYear) : $startYear;
                                
                                for ($year = $currentYear + 1; $year >= $minYear; $year--) {
                                    $years[$year] = $year;
                                }
                                return $years;
                            })
                            ->optionsLimit(1000)
                            ->disabled(fn (callable $get) => blank($get('year_from')))
                            ->searchable()
                            ->placeholder('Iki '),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        $fromYear = $data['year_from'] ?? null;
                        $toYear = $data['year_to'] ?? null;
                        
                        // Jei "iki" mažesnė nei "nuo", ignoruoti "iki"
                        if ($fromYear && $toYear && $toYear < $fromYear) {
                            $toYear = null;
                        }
                        
                        return $query
                            ->when(
                                $fromYear,
                                fn (Builder $query, $year) => $query->where('year', '>=', $year),
                            )
                            ->when(
                                $toYear,
                                fn (Builder $query, $year) => $query->where('year', '<=', $year),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['year_from'] ?? null) {
                            $indicators['year_from'] = 'Metai nuo: ' . $data['year_from'];
                        }
                        if ($data['year_to'] ?? null) {
                            $indicators['year_to'] = 'Metai iki: ' . $data['year_to'];
                        }
                        return $indicators;
                    }),
                SelectFilter::make('body_type_id')
                    ->label('Kėbulo tipas')
                    ->relationship('bodyType', 'title')
                    ->placeholder('Visi kėbulo tipai'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'sm' => 1,
                'lg' => 3,
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Peržiūrėti'),
                EditAction::make()
                    ->label('Redaguoti'),
            ])
            ->toolbarActions([]);
    }
}
