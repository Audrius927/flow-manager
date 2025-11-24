<?php

namespace App\Filament\Resources\Engines\Pages;

use App\Filament\Resources\Engines\EngineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEngine extends CreateRecord
{
    protected static string $resource = EngineResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti variklį';
    }

    public function getTitle(): string
    {
        return 'Sukurti variklį';
    }
}
