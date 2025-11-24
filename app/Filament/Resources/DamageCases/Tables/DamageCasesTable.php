<?php

namespace App\Filament\Resources\DamageCases\Tables;

use App\Enums\SystemRole;
use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Services\DamageCases\DamageCaseFieldPermissionResolver;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class DamageCasesTable
{
    public static function configure(Table $table): Table
    {
        $user = auth()->user();
        $permissions = app(DamageCaseFieldPermissionResolver::class);
        $isAdmin = $user?->system_role === SystemRole::Admin;

        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('damage_number')
                    ->label('Žalos nr.')
                    ->badge()
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('Žalos nr. nukopijuotas')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'damage_number')),
                \Filament\Tables\Columns\TextColumn::make('insurance_company')
                    ->label('Draudimo kompanija')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'insurance_company')),
                \Filament\Tables\Columns\TextColumn::make('product')
                    ->label('Produktas')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'product')),
                \Filament\Tables\Columns\TextColumn::make('carMark.title')
                    ->label('Markė')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'car_mark_id')),
                \Filament\Tables\Columns\TextColumn::make('carModel.title')
                    ->label('Modelis')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'car_model_id')),
                \Filament\Tables\Columns\TextColumn::make('license_plate')
                    ->label('Valst nr.')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'license_plate')),
                \Filament\Tables\Columns\TextColumn::make('first_name')
                    ->label('Vardas')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'first_name')),
                \Filament\Tables\Columns\TextColumn::make('last_name')
                    ->label('Pavardė')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'last_name')),
                \Filament\Tables\Columns\TextColumn::make('phone')
                    ->label('Tel nr.')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'phone')),
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
                    ->sortable()
                    ->visible($permissions->canViewAny($user, 'first_name', 'last_name', 'phone')),
                \Filament\Tables\Columns\TextColumn::make('order_date')
                    ->label('Užsakymo data')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'order_date')),
                \Filament\Tables\Columns\TextColumn::make('received_at')
                    ->label('Perėmimo data / laikas')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'received_at')),
                \Filament\Tables\Columns\TextColumn::make('received_location')
                    ->label('Perėmimo vieta (adresas)')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'received_location')),
                \Filament\Tables\Columns\TextColumn::make('storage_location')
                    ->label('Saugojimo vieta')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'storage_location')),
                \Filament\Tables\Columns\TextColumn::make('removed_from_storage_at')
                    ->label('Išvežtas iš saugojimo vietos (Data)')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'removed_from_storage_at')),
                \Filament\Tables\Columns\TextColumn::make('returned_to_storage_at')
                    ->label('Grąžintas į saugojimo vietą (Data)')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'returned_to_storage_at')),
                \Filament\Tables\Columns\TextColumn::make('returned_to_client_at')
                    ->label('Grąžintas klientui (Data)')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'returned_to_client_at')),
                \Filament\Tables\Columns\TextColumn::make('repair_company')
                    ->label('Remonto įmonė')
                    ->searchable()
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'repair_company')),
                \Filament\Tables\Columns\TextColumn::make('planned_repair_start')
                    ->label('Planuojama remonto pradžia (Data)')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'planned_repair_start')),
                \Filament\Tables\Columns\TextColumn::make('planned_repair_end')
                    ->label('Planuojama remonto pabaiga (Data)')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'planned_repair_end')),
                \Filament\Tables\Columns\TextColumn::make('finished_at')
                    ->label('Baigta')
                    ->date('Y-m-d')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'finished_at')),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Sukurta')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'created_at')),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atnaujinta')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->visible($permissions->canViewField($user, 'updated_at')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Peržiūra')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => DamageCaseResource::getUrl('view', ['record' => $record]))
                    ->visible(function ($record) use ($isAdmin, $permissions, $user) {
                        if ($isAdmin) {
                            return true;
                        }
                        if (!$permissions->canViewAny($user, ...$permissions->getConfiguredFields())) {
                            return false;
                        }
                        // Patikrinti ar įrašas yra priskirtas vartotojui
                        return $record->users()->where('users.id', $user->id)->exists();
                    }),
                EditAction::make()
                    ->visible(function ($record) use ($isAdmin, $permissions, $user) {
                        if ($isAdmin) {
                            return true;
                        }
                        if (!$permissions->canEditAny($user)) {
                            return false;
                        }
                        // Patikrinti ar įrašas yra priskirtas vartotojui
                        return $record->users()->where('users.id', $user->id)->exists();
                    }),
            ])
            ->toolbarActions([
            ])
            ->defaultSort('order_date', 'desc');
    }
}
