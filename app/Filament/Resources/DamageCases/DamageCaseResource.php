<?php

namespace App\Filament\Resources\DamageCases;

use App\Enums\SystemRole;
use App\Filament\Concerns\RestrictsSystemRole;
use App\Filament\Resources\DamageCases\Pages\CreateDamageCase;
use App\Filament\Resources\DamageCases\Pages\EditDamageCase;
use App\Filament\Resources\DamageCases\Pages\ListDamageCases;
use App\Filament\Resources\DamageCases\Pages\ViewDamageCase;
use App\Filament\Resources\DamageCases\Schemas\DamageCaseForm;
use App\Filament\Resources\DamageCases\Schemas\DamageCaseInfolist;
use App\Filament\Resources\DamageCases\Tables\DamageCasesTable;
use App\Models\DamageCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DamageCaseResource extends Resource
{
    use RestrictsSystemRole;

    protected static function allowedSystemRoles(): array
    {
        return [
            SystemRole::Admin,
            SystemRole::User,
        ];
    }
    protected static ?string $model = DamageCase::class;

    protected static ?string $recordTitleAttribute = 'damage_number';

    protected static ?string $modelLabel = 'Užsakymas';

    protected static ?string $pluralModelLabel = 'Užsakymų valdymas';

    protected static ?string $navigationLabel = 'Užsakymų valdymas';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return null; // Pagrindinė grupė arba atskira
    }

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shield-exclamation';

    public static function form(Schema $schema): Schema
    {
        return DamageCaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DamageCaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DamageCasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDamageCases::route('/'),
            'create' => CreateDamageCase::route('/create'),
            'view' => ViewDamageCase::route('/{record}'),
            'edit' => EditDamageCase::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if ($user?->system_role === SystemRole::Admin) {
            return true;
        }

        $permissions = app(\App\Services\DamageCases\DamageCaseFieldPermissionResolver::class);

        return $permissions->canViewAny($user, ...$permissions->getConfiguredFields());
    }

    public static function canView($record): bool
    {
        $user = auth()->user();

        if ($user?->system_role === SystemRole::Admin) {
            return true;
        }

        if (!$user) {
            return false;
        }

        $permissions = app(\App\Services\DamageCases\DamageCaseFieldPermissionResolver::class);

        if (!$permissions->canViewAny($user, ...$permissions->getConfiguredFields())) {
            return false;
        }

        // Patikrinti ar įrašas yra priskirtas vartotojui
        return $record->users()->where('users.id', $user->id)->exists();
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if ($user?->system_role === SystemRole::Admin) {
            return true;
        }

        if (!$user) {
            return false;
        }

        $permissions = app(\App\Services\DamageCases\DamageCaseFieldPermissionResolver::class);

        if (!$permissions->canEditAny($user)) {
            return false;
        }

        // Patikrinti ar įrašas yra priskirtas vartotojui
        return $record->users()->where('users.id', $user->id)->exists();
    }

    public static function canDelete($record): bool
    {
        // Tik admin gali trinti
        return auth()->user()?->system_role === SystemRole::Admin;
    }
}
