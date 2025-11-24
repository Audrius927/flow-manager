<?php

namespace App\Filament\Resources\Parts\Pages;

use App\Filament\Resources\Parts\PartResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPart extends EditRecord
{
    protected static string $resource = PartResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti detalę';
    }

    public function getTitle(): string
    {
        return 'Redaguoti detalę';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
