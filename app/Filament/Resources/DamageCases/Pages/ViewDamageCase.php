<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Enums\SystemRole;
use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Services\DamageCases\DamageCaseFieldPermissionResolver;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDamageCase extends ViewRecord
{
    protected static string $resource = DamageCaseResource::class;

    public function getHeading(): string
    {
        $number = $this->record->damage_number ?? 'Užsakymas';
        $client = trim(($this->record->first_name ?? '') . ' ' . ($this->record->last_name ?? ''));
        if (!empty($client)) {
            return "{$number} - {$client}";
        }
        return $number;
    }

    protected function getHeaderActions(): array
    {
        $permissions = app(DamageCaseFieldPermissionResolver::class);
        $user = auth()->user();

        return [
            EditAction::make()
                ->visible(
                    ($user?->system_role === SystemRole::Admin) ||
                    $permissions->canEditAny($user)
                ),
        ];
    }

    public static function canAccess(array $parameters = []): bool
    {
        $user = auth()->user();

        if ($user?->system_role === SystemRole::Admin) {
            return true;
        }

        if (!$user) {
            return false;
        }

        $permissions = app(DamageCaseFieldPermissionResolver::class);

        if (!$permissions->canViewAny($user, ...$permissions->getConfiguredFields())) {
            return false;
        }

        // Patikrinti ar įrašas yra priskirtas vartotojui
        if (isset($parameters['record'])) {
            $record = $parameters['record'];
            if ($record instanceof \App\Models\DamageCase) {
                return $record->users()->where('users.id', $user->id)->exists();
            }
        }

        return true;
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $user = auth()->user();

        // Jei ne admin, patikrinti ar įrašas yra priskirtas vartotojui
        if ($user && $user->system_role !== SystemRole::Admin) {
            if (!$this->record->users()->where('users.id', $user->id)->exists()) {
                abort(403, 'Neturite prieigos prie šio įrašo.');
            }
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
