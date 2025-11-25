<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Pavadinimas')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('permissions_label')
                    ->label('Leidimai')
                    ->badge()
                    ->color('info')
                    ->state(function ($record) {
                        if ($record->permissions->isEmpty()) {
                            return null;
                        }

                        return $record->permissions
                            ->map(fn ($permission) => $permission->label ?? $permission->name)
                            ->implode(', ');
                    })
                    ->separator(', ')
                    ->placeholder('Nėra'),
                TextColumn::make('users_count')
                    ->label('Naudotojų skaičius')
                    ->counts('users')
                    ->badge()
                    ->color('success'),
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
            ->recordActions([
                ViewAction::make()
                    ->label('Peržiūrėti'),
                EditAction::make()
                    ->label('Redaguoti'),
            ])
            ->toolbarActions([]);
    }
}
