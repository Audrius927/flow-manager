<?php

namespace App\Services\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;

class RoleFieldPermissionService
{
    private ?Collection $permissionCache = null;

    /**
     * Gauti visų leidimų sąrašą.
     *
     * @return array<int, array{id:int,label:string}>
     */
    public function getPermissionDefinitions(): array
    {
        return $this->loadPermissions()
            ->map(fn (Permission $permission) => [
                'id' => $permission->id,
                'label' => $permission->label ?? $permission->name,
            ])
            ->values()
            ->all();
    }

    /**
     * Sugeneruoti pradinę formos būseną (visi leidimai=false, o jei nurodyta rolė – užpildyti).
     */
    public function buildInitialState(?Role $role = null): array
    {
        $state = [];

        foreach ($this->getPermissionDefinitions() as $permission) {
            $state[$this->viewFieldName($permission['id'])] = false;
            $state[$this->editFieldName($permission['id'])] = false;
        }

        if ($role) {
            $role->loadMissing('permissions');

            foreach ($role->permissions as $permission) {
                $state[$this->viewFieldName($permission->id)] = true;
                $state[$this->editFieldName($permission->id)] = (bool) ($permission->pivot->can_edit ?? false);
            }
        }

        return $state;
    }

    /**
     * Iš formos būsenos ištraukti pasirinktas view/edit teises.
     *
     * @return array{0: array<int>, 1: array<int>}
     */
    public function extractPermissionSelections(array $state): array
    {
        $viewIds = [];
        $editIds = [];

        foreach ($this->getPermissionDefinitions() as $permission) {
            $viewKey = $this->viewFieldName($permission['id']);
            $editKey = $this->editFieldName($permission['id']);

            $canView = !empty($state[$viewKey]);
            $canEdit = !empty($state[$editKey]);

            if ($canEdit) {
                $canView = true;
            }

            if ($canView) {
                $viewIds[] = $permission['id'];
            }

            if ($canEdit) {
                $editIds[] = $permission['id'];
            }
        }

        return [
            array_values(array_unique($viewIds)),
            array_values(array_unique($editIds)),
        ];
    }

    /**
     * Pašalinti leidimų laukus iš formos duomenų prieš kuriant/atnaujinant rolę.
     */
    public function stripPermissionFields(array $data): array
    {
        foreach ($this->getPermissionDefinitions() as $permission) {
            unset(
                $data[$this->viewFieldName($permission['id'])],
                $data[$this->editFieldName($permission['id'])]
            );
        }

        return $data;
    }

    /**
     * Sinchronizuoti rolės ir leidimų pivot pagal view/edit sąrašus.
     */
    public function syncRolePermissions(Role $role, array $viewIds, array $editIds): void
    {
        $viewIds = collect($viewIds)->map(fn ($id) => (int) $id)->unique();
        $editIds = collect($editIds)->map(fn ($id) => (int) $id)->unique();

        $payload = $viewIds
            ->mapWithKeys(function ($permissionId) use ($editIds) {
                return [
                    $permissionId => [
                        'can_edit' => $editIds->contains($permissionId),
                    ],
                ];
            })
            ->all();

        $role->permissions()->sync($payload);
    }

    private function loadPermissions(): Collection
    {
        if ($this->permissionCache === null) {
            $this->permissionCache = Permission::query()
                ->orderBy('label')
                ->get(['id', 'name', 'label']);
        }

        return $this->permissionCache;
    }

    private function viewFieldName(int $permissionId): string
    {
        return "permission_{$permissionId}_view";
    }

    private function editFieldName(int $permissionId): string
    {
        return "permission_{$permissionId}_edit";
    }
}

