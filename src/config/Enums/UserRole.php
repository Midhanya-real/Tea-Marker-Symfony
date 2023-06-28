<?php

namespace App\config\Enums;

enum UserRole: string
{
    case Moderator = "ROLE_MODER";

    case User = "ROLE_USER";

    case Admin = "ROLE_ADMIN";
    case SuperAdmin = "ROLE_SUPER_ADMIN";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
