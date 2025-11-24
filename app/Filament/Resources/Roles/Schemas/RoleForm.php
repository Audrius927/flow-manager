<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Role;
use App\Services\Roles\RoleFieldPermissionService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $permissionFieldsets = [];
        $definitions = app(RoleFieldPermissionService::class)->getPermissionDefinitions();

        foreach ($definitions as $definition) {
            $permissionFieldsets[] = \Filament\Schemas\Components\Fieldset::make($definition['label'])
                ->schema([
                    Checkbox::make("permission_{$definition['id']}_view")
                        ->label('Matyti'),
                    Checkbox::make("permission_{$definition['id']}_edit")
                        ->label('Redaguoti')
                        ->live()
                        ->afterStateUpdated(function (callable $set, bool $state) use ($definition): void {
                            if ($state) {
                                $set("permission_{$definition['id']}_view", true);
                            }
                        }),
                ])
                ->columns(2)
                ->extraAttributes([
                    'class' => 'rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 shadow-sm',
                ]);
        }

        return $schema
            ->components([
                Section::make('Rolės informacija')
                    ->description('Pagrindiniai rolės nustatymai ir leidimai')
                    ->schema([
                        TextInput::make('name')
                            ->label('Rolės pavadinimas')
                            ->maxLength(100)
                            ->required()
                            ->unique(table: Role::class, column: 'name', ignorable: fn (?Role $record) => $record),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Laukų prieiga')
                    ->description('Pažymėkite, kuriuos laukus rolė gali matyti arba redaguoti.')
                    ->schema(!empty($permissionFieldsets) ? $permissionFieldsets : [
                        TextInput::make('permission-placeholder')
                            ->label('Leidimai')
                            ->disabled()
                            ->placeholder('Leidimai dar nesukonfigūruoti'),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 3,
                        'xl' => 4,
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
