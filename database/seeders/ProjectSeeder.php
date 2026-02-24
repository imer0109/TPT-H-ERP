<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Company;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::limit(10)->get();
        
        foreach ($companies as $company) {
            // Projets de développement
            $projects = [
                [
                    'code' => 'PROJ-001',
                    'name' => 'ERP TPT-H - Phase 1',
                    'description' => 'Développement de la première phase de l\'ERP TPT-H',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+6 months')),
                    'budget_amount' => 150000.00,
                    'status' => 'actif'
                ],
                [
                    'code' => 'PROJ-002',
                    'name' => 'Application Mobile TPT-H',
                    'description' => 'Développement de l\'application mobile pour les clients',
                    'start_date' => date('Y-m-d', strtotime('-1 month')),
                    'end_date' => date('Y-m-d', strtotime('+4 months')),
                    'budget_amount' => 75000.00,
                    'status' => 'actif'
                ],
                [
                    'code' => 'PROJ-003',
                    'name' => 'Système de Gestion des Ressources',
                    'description' => 'Système de gestion des ressources humaines et matérielles',
                    'start_date' => date('Y-m-d', strtotime('-2 months')),
                    'end_date' => date('Y-m-d', strtotime('+3 months')),
                    'budget_amount' => 50000.00,
                    'status' => 'actif'
                ],
                [
                    'code' => 'PROJ-004',
                    'name' => 'Migration vers le Cloud',
                    'description' => 'Migration de l\'infrastructure vers le cloud',
                    'start_date' => date('Y-m-d', strtotime('-3 months')),
                    'end_date' => date('Y-m-d', strtotime('+2 months')),
                    'budget_amount' => 100000.00,
                    'status' => 'actif'
                ],
                [
                    'code' => 'PROJ-005',
                    'name' => 'Système de Reporting Avancé',
                    'description' => 'Développement de nouveaux rapports et tableaux de bord',
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime('+5 months')),
                    'budget_amount' => 30000.00,
                    'status' => 'actif'
                ]
            ];

            foreach ($projects as $projectData) {
                Project::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'code' => $projectData['code']
                    ],
                    [
                        'name' => $projectData['name'],
                        'description' => $projectData['description'],
                        'start_date' => $projectData['start_date'],
                        'end_date' => $projectData['end_date'],
                        'budget_amount' => $projectData['budget_amount'],
                        'status' => $projectData['status'],
                        'project_manager_id' => $users->random()?->id
                    ]
                );
            }
        }
    }
}