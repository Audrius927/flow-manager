<?php

namespace App\Enums;

enum SystemRole: string
{
    case Admin = 'admin';
    case User = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

