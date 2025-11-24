<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nauja rolė')
                ->modalHeading('Kurti rolę')
                ->modalSubmitActionLabel('Išsaugoti')
                ->modalCancelActionLabel('Atšaukti')
                ->createAnother(false)
                ->successNotificationTitle('Rolė sėkmingai sukurta'),
        ];
    }

    public function getHeading(): string
    {
        return 'Rolės';
    }

    public function getTitle(): string
    {
        return 'Rolės';
    }
}
