<?php

namespace App\Enums;

enum UsersRole: string
{
    case Admin = 'admin';
    case User = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
