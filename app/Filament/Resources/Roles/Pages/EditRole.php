<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Services\Roles\RoleFieldPermissionService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return array_merge(
            $data,
            app(RoleFieldPermissionService::class)->buildInitialState($this->record)
        );
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(RoleFieldPermissionService::class);
        [$viewIds, $editIds] = $service->extractPermissionSelections($data);
        $data = $service->stripPermissionFields($data);

        $record->update($data);

        $service->syncRolePermissions($record, $viewIds, $editIds);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Peržiūrėti'),
            DeleteAction::make()
                ->label('Ištrinti')
                ->modalHeading('Pašalinti rolę?')
                ->modalSubmitActionLabel('Ištrinti')
                ->modalCancelActionLabel('Atšaukti')
                ->successNotificationTitle('Rolė pašalinta'),
        ];
    }

    public function getHeading(): string
    {
        return 'Redaguoti rolę';
    }

    public function getTitle(): string
    {
        return 'Redaguoti rolę';
    }
}
