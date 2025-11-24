<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Naudotojo informacija')
                    ->description('Pagrindiniai duomenys ir prieigos nustatymai')
                    ->schema([
                TextInput::make('name')
                            ->label('Vardas ir pavardė')
                            ->maxLength(255)
                            ->required()
                            ->columnSpan(1),
                TextInput::make('email')
                            ->label('El. paštas')
                    ->email()
                            ->maxLength(255)
                            ->unique(table: User::class, column: 'email', ignorable: fn (?User $record) => $record)
                            ->required()
                            ->columnSpan(1),
                        Select::make('roles')
                            ->label('Rolės')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Galite pasirinkti kelias roles vienam naudotojui.')
                            ->columnSpanFull(),
                TextInput::make('password')
                            ->label('Slaptažodis')
                    ->password()
                            ->revealable()
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText('Bent 8 simboliai. Pakeiskite tik jei norite atnaujinti.')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn (string $state): ?string => filled($state) ? $state : null)
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
