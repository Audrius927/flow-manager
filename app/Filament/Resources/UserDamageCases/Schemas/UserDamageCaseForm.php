<?php

namespace App\Filament\Resources\UserDamageCases\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserDamageCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_ids')
                    ->label('Vartotojai')
                    ->multiple()
                    ->options(function () {
                        return \App\Models\User::with('roles')
                            ->get()
                            ->mapWithKeys(function ($user) {
                                $roles = $user->roles->pluck('name')->join(', ');
                                $label = $user->name;
                                if ($user->email) {
                                    $label .= ' (' . $user->email . ')';
                                }
                                if ($roles) {
                                    $label .= ' - ' . $roles;
                                }
                                return [$user->id => $label];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText(function ($livewire) {
                        // Jei redaguojame, rodome kitą tekstą
                        if (isset($livewire->mountedTableActionRecord) || isset($livewire->mountedTableAction)) {
                            return 'Pasirinkite vartotoją';
                        }
                        return 'Pasirinkite vieną ar daugiau vartotojų';
                    })
                    ->maxItems(function ($livewire) {
                        // Jei redaguojame, leidžiame tik vieną vartotoją
                        if (isset($livewire->mountedTableActionRecord) || isset($livewire->mountedTableAction)) {
                            return 1;
                        }
                        return null;
                    }),
                Select::make('damage_case_id')
                    ->label('Gedimų atvejis')
                    ->relationship('damageCase', 'damage_number')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $label = $record->damage_number ?? 'Gedimų atvejis #' . $record->id;
                        $client = trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? ''));
                        if (!empty($client)) {
                            $label .= ' - ' . $client;
                        }
                        return $label;
                    })
                    ->searchable(['damage_number', 'first_name', 'last_name'])
                    ->preload()
                    ->required()
                    ->rules([
                        function ($get, $set, $record) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $userIds = $get('user_ids');
                                if (!$userIds || !is_array($userIds) || empty($userIds)) {
                                    return;
                                }
                                
                                $existing = \App\Models\UserDamageCase::where('damage_case_id', $value)
                                    ->whereIn('user_id', $userIds)
                                    ->pluck('user_id')
                                    ->toArray();
                                
                                if (!empty($existing)) {
                                    $users = \App\Models\User::whereIn('id', $existing)->pluck('name')->join(', ');
                                    $fail('Šie vartotojai jau priskirti šiam gedimų atvejui: ' . $users);
                                }
                            };
                        },
                    ]),
            ])
            ->columns(2);
    }
}
