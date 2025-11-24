<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Filament\Resources\PartStorages\PartStorageResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\UserDamageCases\UserDamageCaseResource;
use Filament\Widgets\Widget;

class QuickLinks extends Widget
{
    protected string $view = 'filament.widgets.quick-links';

    protected int|string|array $columnSpan = 'full';

    public function getCards(): array
    {
        return [
            [
                'title' => 'Užsakymų valdymas',
                'url' => DamageCaseResource::getUrl(),
                'icon' => 'heroicon-o-clipboard-document-check',
            ],
            [
                'title' => 'Vartotojų priskyrimai',
                'url' => UserDamageCaseResource::getUrl(),
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'title' => 'Automobilių detalės',
                'url' => PartStorageResource::getUrl(),
                'icon' => 'heroicon-o-cube',
            ],
            [
                'title' => 'Rolės ir naudotojai',
                'url' => RoleResource::getUrl(),
                'icon' => 'heroicon-o-shield-check',
            ],
        ];
    }
}
