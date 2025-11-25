<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Vardas ir pavardė')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('El. paštas')
                    ->copyable()
                    ->copyMessage('El. pašto adresas nukopijuotas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Rolės')
                    ->badge()
                    ->color('info')
                    ->separator(', ')
                    ->placeholder('Nėra')
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Sukurta')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atnaujinta')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Rolė')
                    ->relationship('roles', 'name')
                    ->placeholder('Visos rolės'),
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
