<?php

namespace App\Filament\Resources\PartCategories\Pages;

use App\Filament\Resources\PartCategories\PartCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartCategory extends EditRecord
{
    protected static string $resource = PartCategoryResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti detalių kategoriją';
    }

    public function getTitle(): string
    {
        return 'Redaguoti detalių kategoriją';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
