<?php

namespace App\Filament\Resources\CarMarks\Pages;

use App\Filament\Resources\CarMarks\CarMarkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCarMark extends ViewRecord
{
    protected static string $resource = CarMarkResource::class;

    public function getHeading(): string
    {
        return $this->record->title ?? 'Markė';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
