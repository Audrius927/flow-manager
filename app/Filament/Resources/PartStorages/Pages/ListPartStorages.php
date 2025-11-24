<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartStorages extends ListRecords
{
    protected static string $resource = PartStorageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Naujas įrašas')
                ->modalHeading('Pridėti automobilio detalę')
                ->modalSubmitActionLabel('Išsaugoti')
                ->modalCancelActionLabel('Atšaukti')
                ->createAnother(false)
                ->successNotificationTitle('Įrašas sėkmingai sukurtas'),
        ];
    }

    public function getHeading(): string
    {
        return 'Automobilių detalės';
    }

    public function getTitle(): string
    {
        return 'Automobilių detalės';
    }
}
