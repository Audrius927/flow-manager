<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDamageCase extends CreateRecord
{
    protected static string $resource = DamageCaseResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Naujas užsakymas';
    }

    public function getTitle(): string
    {
        return 'Naujas užsakymas';
    }
}
