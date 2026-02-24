<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(10)->get();
        
        $departments = [
            [
                'name' => 'Direction Générale',
                'description' => 'Direction et supervision générale de l\'entreprise',
                'head_id' => $users->first()?->id
            ],
            [
                'name' => 'Développement',
                'description' => 'Développement logiciel et applications',
                'head_id' => $users->skip(1)->first()?->id
            ],
            [
                'name' => 'Commercial',
                'description' => 'Ventes, marketing et développement commercial',
                'head_id' => $users->skip(2)->first()?->id
            ],
            [
                'name' => 'Ressources Humaines',
                'description' => 'Gestion des ressources humaines et recrutement',
                'head_id' => $users->skip(3)->first()?->id
            ],
            [
                'name' => 'Finances et Comptabilité',
                'description' => 'Gestion financière et comptabilité',
                'head_id' => $users->skip(4)->first()?->id
            ],
            [
                'name' => 'Support Technique',
                'description' => 'Support technique et maintenance',
                'head_id' => $users->skip(5)->first()?->id
            ],
            [
                'name' => 'Marketing',
                'description' => 'Marketing et communication',
                'head_id' => $users->skip(6)->first()?->id
            ],
            [
                'name' => 'Qualité',
                'description' => 'Assurance qualité et contrôle qualité',
                'head_id' => $users->skip(7)->first()?->id
            ]
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['name' => $dept['name']],
                $dept
            );
        }
    }
}
