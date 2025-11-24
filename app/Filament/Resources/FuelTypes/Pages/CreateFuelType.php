<?php

namespace App\Filament\Resources\FuelTypes\Pages;

use App\Filament\Resources\FuelTypes\FuelTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFuelType extends CreateRecord
{
    protected static string $resource = FuelTypeResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti kuro tipą';
    }

    public function getTitle(): string
    {
        return 'Sukurti kuro tipą';
    }
}
