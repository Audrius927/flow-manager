<?php

namespace App\Filament\Resources\PartStorages\Tables;

use App\Models\CarMark;
use App\Models\CarModel;
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
                    ->label('Detalės numeris')
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
                    ->label('Kategorija')
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
                    ->label('Kategorija')
                    ->form([
                        SelectTree::make('part_category_id')
                            ->label('Kategorija')
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
                SelectFilter::make('engine_id')
                    ->label('Variklis')
                    ->relationship('engine', 'title')
                    ->placeholder('Visi varikliai'),
                Filter::make('year')
                    ->label('Metai')
                    ->form([
                        Select::make('year')
                            ->label('Metai')
                            ->options(function (): array {
                                $years = range(1900, now()->year + 1);

                                return collect($years)
                                    ->sortDesc()
                                    ->mapWithKeys(fn (int $year) => [$year => $year])
                                    ->all();
                            })
                            ->optionsLimit(2000)
                            ->searchable()
                            ->placeholder('Visi metai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['year'] ?? null,
                            fn (Builder $query, $year) => $query->where('year', $year),
                        );
                    })
                    ->indicateUsing(fn (array $data): ?string => !empty($data['year']) ? "Metai: {$data['year']}" : null),
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Pašalinti pasirinktus'),
                ]),
            ]);
    }
}
