<?php

namespace App\Filament\Resources\BodyTypes\Pages;

use App\Filament\Resources\BodyTypes\BodyTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBodyType extends CreateRecord
{
    protected static string $resource = BodyTypeResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti kėbulo tipą';
    }

    public function getTitle(): string
    {
        return 'Sukurti kėbulo tipą';
    }
}
