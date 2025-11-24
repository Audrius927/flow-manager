<?php

namespace App\Filament\Resources\Engines\Pages;

use App\Filament\Resources\Engines\EngineResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEngine extends EditRecord
{
    protected static string $resource = EngineResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti variklį';
    }

    public function getTitle(): string
    {
        return 'Redaguoti variklį';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
