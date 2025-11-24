<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDamageCase extends EditRecord
{
    protected static string $resource = DamageCaseResource::class;

    public function getHeading(): string
    {
        return 'Redaguoti užsakymą';
    }

    public function getTitle(): string
    {
        return 'Redaguoti užsakymą';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
