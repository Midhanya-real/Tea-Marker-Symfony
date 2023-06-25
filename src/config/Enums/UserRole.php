<?php

namespace App\config\Enums;

enum UserRole: string
{
    case User = "ROLE_USER";
    case Moderator = "ROLE_MODER";
    case Admin = "ROLE_ADMIN";
    case SuperAdmin = "ROLE_SUPER_ADMIN";
}
