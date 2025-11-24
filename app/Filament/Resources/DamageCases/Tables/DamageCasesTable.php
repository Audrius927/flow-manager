<?php

namespace App\Filament\Resources\DamageCases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class DamageCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('damage_number')
                    ->label('Žalos nr.')
                    ->badge()
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('Žalos nr. nukopijuotas')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('insurance_company')
                    ->label('Draudimo kompanija')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('product')
                    ->label('Produktas')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('carMark.title')
                    ->label('Markė')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('carModel.title')
                    ->label('Modelis')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('license_plate')
                    ->label('Valst nr.')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('first_name')
                    ->label('Vardas')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('last_name')
                    ->label('Pavardė')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('phone')
                    ->label('Tel nr.')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('client_full_name')
                    ->label('Klientas')
                    ->state(function ($record) {
                        $name = trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? ''));
                        return !empty($name) ? $name : ($record->phone ?? '-');
                    })
                    ->searchable(query: function ($query, $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('order_date')
                    ->label('Užsakymo data')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('received_at')
                    ->label('Perėmimo data / laikas')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('received_location')
                    ->label('Perėmimo vieta (adresas)')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('storage_location')
                    ->label('Saugojimo vieta')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('removed_from_storage_at')
                    ->label('Išvežtas iš saugojimo vietos (Data)')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('returned_to_storage_at')
                    ->label('Grąžintas į saugojimo vietą (Data)')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('returned_to_client_at')
                    ->label('Grąžintas klientui (Data)')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('repair_company')
                    ->label('Remonto įmonė')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('planned_repair_start')
                    ->label('Planuojama remonto pradžia (Data)')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('planned_repair_end')
                    ->label('Planuojama remonto pabaiga (Data)')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('finished_at')
                    ->label('Baigta')
                    ->date('Y-m-d')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Sukurta')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atnaujinta')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_date', 'desc');
    }
}
