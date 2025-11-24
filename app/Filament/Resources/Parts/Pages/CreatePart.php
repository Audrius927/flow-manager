<?php

namespace App\Filament\Resources\Parts\Pages;

use App\Filament\Resources\Parts\PartResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePart extends CreateRecord
{
    protected static string $resource = PartResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti detalę';
    }

    public function getTitle(): string
    {
        return 'Sukurti detalę';
    }
}
