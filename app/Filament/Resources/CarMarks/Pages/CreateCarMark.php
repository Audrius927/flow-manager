<?php

namespace App\Filament\Resources\CarMarks\Pages;

use App\Filament\Resources\CarMarks\CarMarkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarMark extends CreateRecord
{
    protected static string $resource = CarMarkResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti markę';
    }

    public function getTitle(): string
    {
        return 'Sukurti markę';
    }
}
