<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pagrindinė informacija')
                    ->description('Rolės pavadinimas ir paskirtis')
                    ->components([
                        TextEntry::make('name')
                            ->label('Pavadinimas')
                            ->badge()
                            ->color('primary'),
                    ]),
                Section::make('Laukų leidimai')
                    ->description('Kokius laukus ši rolė gali matyti ar redaguoti')
                    ->components([
                        TextEntry::make('permissions_overview')
                            ->label('Leidimai')
                            ->state(function ($record): array {
                                if (!$record || $record->permissions->isEmpty()) {
                                    return [];
                                }

                                return $record->permissions
                                    ->map(function ($permission) {
                                        $label = $permission->label ?? $permission->name;
                                        $canEdit = (bool) ($permission->pivot->can_edit ?? false);
                                        return $label . ' — ' . ($canEdit ? 'Matyti ir redaguoti' : 'Tik matyti');
                                    })
                                    ->all();
                            })
                            ->placeholder('Leidimai nepriskirti')
                            ->listWithLineBreaks(),
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
