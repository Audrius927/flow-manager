<?php

namespace App\Filament\Resources\FuelTypes\Pages;

use App\Filament\Resources\FuelTypes\FuelTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFuelType extends EditRecord
{
    protected static string $resource = FuelTypeResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti kuro tipą';
    }

    public function getTitle(): string
    {
        return 'Redaguoti kuro tipą';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
