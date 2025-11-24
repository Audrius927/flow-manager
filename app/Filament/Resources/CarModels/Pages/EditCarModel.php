<?php

namespace App\Filament\Resources\CarModels\Pages;

use App\Filament\Resources\CarModels\CarModelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCarModel extends EditRecord
{
    protected static string $resource = CarModelResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti modelį';
    }

    public function getTitle(): string
    {
        return 'Redaguoti modelį';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
