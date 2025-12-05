<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartStorage extends ViewRecord
{
    protected static string $resource = PartStorageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Užkrauti images relationship
        $this->record->load('images');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
