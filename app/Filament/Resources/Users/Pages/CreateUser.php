<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Naujas naudotojas';
    }

    public function getTitle(): string
    {
        return 'Naujas naudotojas';
    }
}
