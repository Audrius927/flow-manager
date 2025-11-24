<?php

namespace App\Filament\Concerns;

use App\Enums\SystemRole;
use Illuminate\Support\Facades\Auth;

trait RestrictsSystemRole
{
    /**
     * @return array<SystemRole>
     */
    protected static function allowedSystemRoles(): array
    {
        return [
            SystemRole::Admin,
        ];
    }

    protected static function userHasSystemRoleAccess(): bool
    {
        $user = Auth::user();

        if (! $user || ! $user->system_role instanceof SystemRole) {
            return false;
        }

        return in_array($user->system_role, static::allowedSystemRoles(), true);
    }

    public static function canAccess(): bool
    {
        return static::userHasSystemRoleAccess();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::userHasSystemRoleAccess();
    }
}

