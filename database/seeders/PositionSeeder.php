<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'title' => 'Directeur Général',
                'description' => 'Responsable de la direction générale de l\'entreprise',
                'parent_position_id' => null,
                'is_management' => true,
            ],
            [
                'title' => 'Directeur des Ressources Humaines',
                'description' => 'Responsable de la gestion des ressources humaines',
                'parent_position_id' => null,
                'is_management' => true,
            ],
            [
                'title' => 'Directeur Financier',
                'description' => 'Responsable de la gestion financière et comptable',
                'parent_position_id' => null,
                'is_management' => true,
            ],
            [
                'title' => 'Chef de Département Commercial',
                'description' => 'Responsable du département commercial et des ventes',
                'parent_position_id' => null,
                'is_management' => true,
            ],
            [
                'title' => 'Responsable Achats',
                'description' => 'Responsable de la gestion des achats et approvisionnements',
                'parent_position_id' => null,
                'is_management' => true,
            ],
            [
                'title' => 'Gestionnaire Stock',
                'description' => 'Responsable de la gestion des stocks et inventaires',
                'parent_position_id' => null,
                'is_management' => false,
            ],
            [
                'title' => 'Comptable',
                'description' => 'Responsable des opérations comptables',
                'parent_position_id' => null,
                'is_management' => false,
            ],
            [
                'title' => 'Assistant RH',
                'description' => 'Assistant dans les tâches de ressources humaines',
                'parent_position_id' => null,
                'is_management' => false,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}