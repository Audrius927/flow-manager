<?php

namespace App\Filament\Resources\CarModels\Pages;

use App\Filament\Resources\CarModels\CarModelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCarModel extends ViewRecord
{
    protected static string $resource = CarModelResource::class;

    public function getHeading(): string
    {
        return $this->record->title ?? 'Modelis';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
