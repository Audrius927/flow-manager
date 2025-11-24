<?php

namespace App\Filament\Resources\DamageCases\Pages;

use App\Enums\SystemRole;
use App\Filament\Resources\DamageCases\DamageCaseResource;
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

        $query = parent::getTableQuery();

        // Jei vartotojas nėra admin, rodyti tik jam priskirtus damage cases
        if ($user && $user->system_role !== SystemRole::Admin) {
            $query->whereHas('users', function (Builder $q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        return $query;
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
