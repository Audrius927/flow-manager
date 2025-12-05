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
                    ->description(fn ($record) => $record->user?->roles->pluck('name')->join(', '))
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('El. paštas')
                    ->copyable()
                    ->copyMessage('El. paštas nukopijuotas')
                    ->toggleable(),
                TextColumn::make('damageCase.damage_number')
                    ->label('Žalos nr.')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('damageCase', function ($q) use ($search) {
                            $q->where('damage_number', 'like', "%{$search}%");
                        });
                    })
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),
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
