<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CostCenter;
use App\Models\Company;
use App\Models\User;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::limit(5)->get(); // Prendre quelques utilisateurs pour les managers
        
        foreach ($companies as $company) {
            // Centre de coûts principal
            $mainCenter = CostCenter::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'code' => 'CC-001'
                ],
                [
                    'name' => 'Direction Générale',
                    'description' => 'Centre de coûts de la direction générale',
                    'is_active' => true,
                    'manager_id' => $users->first()?->id,
                    'budget_amount' => 100000.00,
                    'budget_year' => date('Y')
                ]
            );

            // Centres de coûts par département
            $departments = [
                [
                    'code' => 'CC-002',
                    'name' => 'Développement',
                    'description' => 'Centre de coûts pour le développement logiciel',
                    'budget_amount' => 50000.00
                ],
                [
                    'code' => 'CC-003',
                    'name' => 'Commercial',
                    'description' => 'Centre de coûts pour les ventes et le marketing',
                    'budget_amount' => 30000.00
                ],
                [
                    'code' => 'CC-004',
                    'name' => 'Ressources Humaines',
                    'description' => 'Centre de coûts pour les RH',
                    'budget_amount' => 25000.00
                ],
                [
                    'code' => 'CC-005',
                    'name' => 'Finances',
                    'description' => 'Centre de coûts pour la comptabilité et la finance',
                    'budget_amount' => 20000.00
                ],
                [
                    'code' => 'CC-006',
                    'name' => 'Support Technique',
                    'description' => 'Centre de coûts pour le support et la maintenance',
                    'budget_amount' => 15000.00
                ]
            ];

            foreach ($departments as $dept) {
                CostCenter::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'code' => $dept['code']
                    ],
                    [
                        'name' => $dept['name'],
                        'description' => $dept['description'],
                        'parent_id' => $mainCenter->id,
                        'is_active' => true,
                        'manager_id' => $users->random()?->id,
                        'budget_amount' => $dept['budget_amount'],
                        'budget_year' => date('Y')
                    ]
                );
            }
        }
    }
}
