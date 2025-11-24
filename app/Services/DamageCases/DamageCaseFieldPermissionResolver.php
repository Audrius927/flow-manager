<?php

namespace App\Services\DamageCases;

use App\Enums\SystemRole;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DamageCaseFieldPermissionResolver
{
    public function getConfiguredFields(): array
    {
        return array_keys(config('permissions.damage_cases_fields', []));
    }

    public function canViewField(?User $user, string $field): bool
    {
        return $this->isAdmin($user)
            ? true
            : ($this->resolveFor($user)[$field]['can_view'] ?? false);
    }

    public function canEditField(?User $user, string $field): bool
    {
        return $this->isAdmin($user)
            ? true
            : ($this->resolveFor($user)[$field]['can_edit'] ?? false);
    }

    public function canViewAny(?User $user, string ...$fields): bool
    {
        foreach ($fields as $field) {
            if ($this->canViewField($user, $field)) {
                return true;
            }
        }

        return false;
    }

    public function canEditAny(?User $user, string ...$fields): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        if (empty($fields)) {
            $fields = $this->getConfiguredFields();
        }

        foreach ($fields as $field) {
            if ($this->canEditField($user, $field)) {
                return true;
            }
        }

        return false;
    }

    protected function resolveFor(User $user): array
    {
        $user->loadMissing('roles.permissions');

        $map = [];

        /** @var Collection $roles */
        $roles = $user->getRelation('roles');

        foreach ($roles as $role) {
            foreach ($role->permissions as $permission) {
                if (! Str::startsWith($permission->name, 'damage_cases.')) {
                    continue;
                }

                $field = (string) Str::after($permission->name, 'damage_cases.');

                $map[$field] ??= [
                    'can_view' => false,
                    'can_edit' => false,
                ];

                $map[$field]['can_view'] = true;
                $map[$field]['can_edit'] = $map[$field]['can_edit'] || (bool) $permission->pivot?->can_edit;
            }
        }

        return $map;
    }

    protected function isAdmin(?User $user): bool
    {
        if (! $user || (! $user->system_role instanceof SystemRole)) {
            return false;
        }

        return $user->system_role === SystemRole::Admin;
    }
}

