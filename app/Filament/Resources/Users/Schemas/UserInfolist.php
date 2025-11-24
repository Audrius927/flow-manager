<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Naudotojo pagrindiniai duomenys')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Vardas ir pavardė'),
                        TextEntry::make('email')
                            ->label('El. paštas')
                            ->copyable()
                            ->copyMessage('El. pašto adresas nukopijuotas')
                            ->columnSpan(1),
                    ]),
                Section::make('Prieigos informacija')
                    ->description('Rolės ir teisės')
                    ->components([
                        TextEntry::make('roles.name')
                            ->label('Priskirtos rolės')
                            ->badge()
                            ->color('info')
                            ->placeholder('Rolės nepriskirtos'),
                    ]),
                Section::make('Sistemos duomenys')
                    ->description('Įrašo sukurimo ir atnaujinimo datos')
                    ->columns(2)
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Sukurta')
                            ->dateTime('Y-m-d H:i:s')
                            ->placeholder('Nenurodyta'),
                        TextEntry::make('updated_at')
                            ->label('Atnaujinta')
                            ->dateTime('Y-m-d H:i:s')
                            ->placeholder('Nenurodyta'),
                    ]),
            ]);
    }
}
