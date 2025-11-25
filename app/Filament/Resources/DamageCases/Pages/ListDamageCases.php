<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Enums\SystemRole;
use App\Filament\Resources\DamageCases\DamageCaseResource;
use App\Services\DamageCases\DamageCaseFieldPermissionResolver;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDamageCases extends ListRecords
{
    protected static string $resource = DamageCaseResource::class;

    protected function getHeaderActions(): array
    {
        $isAdmin = auth()->user()?->system_role === SystemRole::Admin;

        return [
            CreateAction::make()
                ->label('Sukurti užsakymą')
                ->visible($isAdmin),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        $permissions = app(DamageCaseFieldPermissionResolver::class);

        $query = parent::getTableQuery();

        if (! $user) {
            return $query->where(fn (Builder $builder) => $builder->whereRaw('1 = 0'));
        }

        if ($user->system_role === SystemRole::Admin) {
            return $query;
        }

        if ($user->roles->isEmpty() || ! $permissions->canViewAny($user, ...$permissions->getConfiguredFields())) {
            return $query->where(fn (Builder $builder) => $builder->whereRaw('1 = 0'));
        }

        return $query->whereHas('users', function (Builder $q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    public function getHeading(): string
    {
        return 'Užsakymų valdymas';
    }

    public function getTitle(): string
    {
        return 'Užsakymų valdymas';
    }
}
