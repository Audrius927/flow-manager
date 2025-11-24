<?php

namespace App\Filament\Resources\PartStorages\Pages;

use App\Filament\Resources\PartStorages\PartStorageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartStorage extends CreateRecord
{
    protected static string $resource = PartStorageResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti detalių sandėliavimo įrašą';
    }

    public function getTitle(): string
    {
        return 'Sukurti detalių sandėliavimo įrašą';
    }
}
