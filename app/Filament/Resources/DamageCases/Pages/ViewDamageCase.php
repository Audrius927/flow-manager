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

        $permissions = app(DamageCaseFieldPermissionResolver::class);

        return $permissions->canViewAny($user, ...$permissions->getConfiguredFields());
    }
}
