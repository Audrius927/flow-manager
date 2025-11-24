<?php

namespace App\Filament\Resources\PartStorages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PartStoragesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('part.part_number')
                    ->label('Detalės numeris')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('part.title')
                    ->label('Detalės pavadinimas')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                \Filament\Tables\Columns\TextColumn::make('storage_location')
                    ->label('Sandėlio vieta')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('quantity')
                    ->label('Kiekis')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('condition')
                    ->label('Būklė')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'success',
                        'used' => 'info',
                        'damaged' => 'danger',
                        'repaired' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Nauja',
                        'used' => 'Naudota',
                        'damaged' => 'Sugedusi',
                        'repaired' => 'Suremontuota',
                        default => $state,
                    })
                    ->sortable()
                    ->toggleable(),
                \Filament\Tables\Columns\TextColumn::make('received_at')
                    ->label('Gavimo data')
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
