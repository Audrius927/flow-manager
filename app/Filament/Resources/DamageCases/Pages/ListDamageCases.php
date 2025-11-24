<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Filament\Resources\DamageCases\DamageCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDamageCases extends ListRecords
{
    protected static string $resource = DamageCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getHeading(): string
    {
        return 'Užsakymų valdymas';
    }

    public function getTitle(): string
    {
        return 'Užsakymų valdymas';
    }
}
