<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolesConfig = $this->getRolesConfig();
        $roleIds = [];

        foreach ($rolesConfig as $roleName => $config) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            $permissionNames = array_unique(array_merge($config['view'], $config['edit']));
            $permissions = Permission::query()
                ->whereIn('name', $permissionNames)
                ->get()
                ->keyBy('name');

            $payload = [];

            foreach ($permissionNames as $permissionName) {
                $permission = $permissions->get($permissionName);

                if (!$permission) {
                    $this->command?->warn("⚠️ Nerastas leidimas: {$permissionName}");
                    continue;
                }

                $payload[$permission->id] = [
                    'can_edit' => in_array($permissionName, $config['edit'], true),
                ];
            }

            $role->permissions()->sync($payload);
            $roleIds[$roleName] = $role->id;
        }

        if (empty($roleIds)) {
            return;
        }

        $roleNames = array_keys($roleIds);
        $fallbackRoleId = reset($roleIds);

        User::all()->each(function (User $user, int $index) use ($roleNames, $roleIds, $fallbackRoleId): void {
            $roleName = $user->email === 'admin@gmail.com'
                ? 'Servisas'
                : $roleNames[$index % count($roleNames)];

            $roleId = $roleIds[$roleName] ?? $fallbackRoleId;

            $user->roles()->sync([$roleId]);
        });
    }

    protected function getRolesConfig(): array
    {
        $allFields = array_keys(config('permissions.damage_cases_fields', []));

        return [
            'Servisas' => [
                'view' => $this->mapFields($allFields),
                'edit' => $this->mapFields([
                    'storage_location',
                    'removed_from_storage_at',
                    'returned_to_storage_at',
                    'returned_to_client_at',
                    'repair_company',
                    'planned_repair_start',
                    'planned_repair_end',
                    'finished_at',
                ]),
            ],
            'Žalos draudėjas' => [
                'view' => $this->mapFields([
                    'damage_number',
                    'insurance_company',
                    'product',
                    'order_date',
                    'received_at',
                    'storage_location',
                    'planned_repair_start',
                    'planned_repair_end',
                    'finished_at',
                ]),
                'edit' => $this->mapFields([
                    'insurance_company',
                    'product',
                    'damage_number',
                ]),
            ],
            'Klientas' => [
                'view' => $this->mapFields([
                    'damage_number',
                    'first_name',
                    'last_name',
                    'phone',
                    'order_date',
                    'received_at',
                    'storage_location',
                    'returned_to_client_at',
                    'finished_at',
                ]),
                'edit' => [],
            ],
        ];
    }

    protected function mapFields(array $fields): array
    {
        return array_map(
            fn (string $field) => str_starts_with($field, 'damage_cases.')
                ? $field
                : "damage_cases.{$field}",
            $fields
        );
    }
}

