<?php

namespace App\Enums;

use App\Traits\EnumsValuesTrait;

enum CategoryEnum: string
{
    use EnumsValuesTrait;
    case APPLICATION_WEB = 'Application Web';
    case APPLICATION_MOBILE = 'Application Mobile';
    case MATERIEL_INFORMATIQUE = 'Matériel Informatique';
    case RESEAUX_ET_TELECOMMUNICATIONS = 'Réseaux et Télécommunications';
    case DATA_SCIENCE_ET_BIG_DATA = 'Data Science et Big Data';
    case GENIE_ELECTRIQUE_ET_ELECTRONIQUE = 'Génie Électrique et Électronique';
    case COMMUNICATION_ET_MARKETING_NUMERIQUE = 'Communication et Marketing Numérique';
    case CONCEPTION_GRAPHIQUE_ET_MULTIMEDIA = 'Conception Graphique et Multimédia';
}
