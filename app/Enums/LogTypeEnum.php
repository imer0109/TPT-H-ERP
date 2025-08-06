<?php

namespace App\Enums;

use App\Traits\EnumsValuesTrait;

enum LogTypeEnum: string
{
    use EnumsValuesTrait;

    case CREATE = 'Création';

    case READ = 'Lecture';
    
    case UPDATE = 'Mise à jour';

    case DELETE = 'Suppression';

    case PUBLISHED = 'Publication';

    case DISCONNECTED = 'Déconnexion';

    case CONNECTED = 'Connexion';

    case UNAUTHORIZED = 'Action non autorisée';

    case OTHER = 'Autre';
}
