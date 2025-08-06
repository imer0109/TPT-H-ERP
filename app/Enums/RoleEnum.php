<?php

namespace App\Enums;

use App\Traits\EnumsValuesTrait;

enum RoleEnum: string
{
    use EnumsValuesTrait;
    case ADMIN = 'Administrateur';
    case USER = 'Utilisateur';
    
}
