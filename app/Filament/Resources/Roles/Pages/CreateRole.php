<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Services\Roles\RoleFieldPermissionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected static bool $canCreateAnother = false;

    protected function getDefaultFormData(): array
    {
        return array_merge(
            parent::getDefaultFormData(),
            app(RoleFieldPermissionService::class)->buildInitialState()
        );
    }

    protected function handleRecordCreation(array $data): Model
    {
        $service = app(RoleFieldPermissionService::class);
        [$viewIds, $editIds] = $service->extractPermissionSelections($data);
        $data = $service->stripPermissionFields($data);

        /** @var \App\Models\Role $role */
        $role = static::getModel()::create($data);

        $service->syncRolePermissions($role, $viewIds, $editIds);

        return $role;
    }

    public function getHeading(): string
    {
        return 'Nauja rolė';
    }

    public function getTitle(): string
    {
        return 'Nauja rolė';
    }
}
