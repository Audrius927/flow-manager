<?php

namespace App\Filament\Resources\Parts\Pages;

use App\Filament\Resources\Parts\PartResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPart extends ViewRecord
{
    protected static string $resource = PartResource::class;

    public function getHeading(): string
    {
        return $this->record->title ?? 'Detalė';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
