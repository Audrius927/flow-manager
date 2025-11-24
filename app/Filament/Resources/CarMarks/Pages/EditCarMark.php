<?php

namespace App\Filament\Resources\CarMarks\Pages;

use App\Filament\Resources\CarMarks\CarMarkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCarMark extends EditRecord
{
    protected static string $resource = CarMarkResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti markę';
    }

    public function getTitle(): string
    {
        return 'Redaguoti markę';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
