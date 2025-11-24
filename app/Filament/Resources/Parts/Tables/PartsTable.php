<?php

namespace App\Filament\Resources\Parts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('part_number')
                    ->label('Detalės numeris')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->label('Pavadinimas')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('partCategory.title')
                    ->label('Kategorija')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('manufacturer')
                    ->label('Gamintojas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('price')
                    ->label('Kaina')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Kiekis sandėlyje')
                    ->state(fn ($record) => $record->storages()->sum('quantity') ?: 0)
                    ->badge()
                    ->color('success')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Sukūrimo data')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
