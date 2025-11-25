<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
                            ->label('Rolė')
                            ->relationship('roles', 'name')
                            ->multiple(false)
                            ->preload()
                            ->searchable()
                            ->helperText('Pasirinkite vieną rolę.')
                            ->columnSpanFull(),
                        Toggle::make('change_password')
                            ->label('Keisti slaptažodį?')
                            ->helperText('Įjunkite, jei norite nustatyti naują slaptažodį.')
                            ->columnSpan(1)
                            ->default(fn (string $operation): bool => $operation === 'create')
                            ->hidden(fn (string $operation): bool => $operation === 'create')
                            ->afterStateUpdated(function (callable $set, bool $state): void {
                                if (! $state) {
                                    $set('password', null);
                                }
                            })
                            ->live(),
                        TextInput::make('password')
                            ->label('Slaptažodis')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText('Bent 8 simboliai. Pakeiskite tik jei norite atnaujinti.')
                            ->required(fn (callable $get, string $operation): bool => $operation === 'create' || $get('change_password'))
                            ->disabled(fn (callable $get, string $operation): bool => $operation !== 'create' && ! $get('change_password'))
                            ->dehydrateStateUsing(fn (string $state): ?string => filled($state) ? $state : null)
                            ->dehydrated(fn ($state, callable $get, string $operation): bool => filled($state) && ($operation === 'create' || $get('change_password')))
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
