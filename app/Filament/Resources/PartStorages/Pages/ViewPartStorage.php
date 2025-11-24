<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartStorage extends ViewRecord
{
    protected static string $resource = PartStorageResource::class;

    public function getHeading(): string
    {
        return $this->record->storage_location ?? 'Detalių sandėliavimas';
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
