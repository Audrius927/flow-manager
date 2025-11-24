<?php

namespace App\Filament\Resources\CarMarks\Pages;

use App\Filament\Resources\CarMarks\CarMarkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarMarks extends ListRecords
{
    protected static string $resource = CarMarkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
