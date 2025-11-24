<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Naujas naudotojas')
                ->modalHeading('Sukurti naudotoją')
                ->modalSubmitActionLabel('Išsaugoti')
                ->modalCancelActionLabel('Atšaukti')
                ->createAnother(false)
                ->successNotificationTitle('Naudotojas sėkmingai sukurtas'),
        ];
    }

    public function getHeading(): string
    {
        return 'Naudotojai';
    }

    public function getTitle(): string
    {
        return 'Naudotojai';
    }
}
