<?php

namespace App\Filament\Resources\Engines\Pages;

use App\Filament\Resources\Engines\EngineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEngines extends ListRecords
{
    protected static string $resource = EngineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
