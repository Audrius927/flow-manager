<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Redaguoti'),
        ];
    }

    public function getHeading(): string
    {
        return $this->record?->name ?? 'Naudotojas';
    }

    public function getTitle(): string
    {
        return 'Naudotojo informacija';
    }
}
