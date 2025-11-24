<?php

namespace App\Filament\Resources\PartCategories\Pages;

use App\Filament\Resources\PartCategories\PartCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartCategory extends CreateRecord
{
    protected static string $resource = PartCategoryResource::class;

    protected static bool $canCreateAnother = false;

    public function getHeading(): string
    {
        return 'Sukurti detalių kategoriją';
    }

    public function getTitle(): string
    {
        return 'Sukurti detalių kategoriją';
    }
}
