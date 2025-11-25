<?php

namespace App\Filament\Resources\UserDamageCases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserDamageCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Vartotojas')
                    ->searchable(['users.name', 'users.email'])
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('El. paštas')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('damageCase.damage_number')
                    ->label('Gedimų atvejis')
                    ->searchable(['damage_cases.damage_number'])
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('damageCase.client_info')
                    ->label('Klientas')
                    ->state(function ($record) {
                        if (!$record->damageCase) {
                            return null;
                        }
                        $client = trim(($record->damageCase->first_name ?? '') . ' ' . ($record->damageCase->last_name ?? ''));
                        return !empty($client) ? $client : ($record->damageCase->phone ?? '-');
                    })
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('damageCase', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Priskirta')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Atnaujinta')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Redaguoti priskyrimą')
                    ->modalSubmitActionLabel('Išsaugoti')
                    ->modalCancelActionLabel('Atšaukti')
                    ->fillForm(function ($record): array {
                        return [
                            'user_ids' => [$record->user_id],
                            'damage_case_id' => $record->damage_case_id,
                        ];
                    })
                    ->mutateRecordDataUsing(function (array $data): array {
                        // Konvertuojame user_ids masyvą atgal į user_id
                        if (isset($data['user_ids']) && is_array($data['user_ids']) && !empty($data['user_ids'])) {
                            $data['user_id'] = $data['user_ids'][0]; // Imame pirmą vartotoją
                            unset($data['user_ids']);
                        }
                        return $data;
                    })
                    ->successNotificationTitle('Priskyrimas sėkmingai atnaujintas'),
                \Filament\Actions\DeleteAction::make()
                    ->modalHeading('Pašalinti priskyrimą')
                    ->modalSubmitActionLabel('Pašalinti')
                    ->modalCancelActionLabel('Atšaukti')
                    ->successNotificationTitle('Priskyrimas sėkmingai pašalintas'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
