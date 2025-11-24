<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Peržiūrėti'),
            DeleteAction::make()
                ->label('Ištrinti')
                ->modalHeading('Pašalinti naudotoją?')
                ->modalSubmitActionLabel('Ištrinti')
                ->modalCancelActionLabel('Atšaukti')
                ->successNotificationTitle('Naudotojas pašalintas'),
        ];
    }

    public function getHeading(): string
    {
        return 'Redaguoti naudotoją';
    }

    public function getTitle(): string
    {
        return 'Redaguoti naudotoją';
    }
}
