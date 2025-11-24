<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartStorage extends EditRecord
{
    protected static string $resource = PartStorageResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti detalių sandėliavimo įrašą';
    }

    public function getTitle(): string
    {
        return 'Redaguoti detalių sandėliavimo įrašą';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
