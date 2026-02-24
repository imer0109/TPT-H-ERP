<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ValidationWorkflow;
use App\Models\Company;
use App\Models\User;

class ValidationWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::limit(5)->get();
        
        foreach ($companies as $company) {
            $workflows = [
                [
                    'name' => 'Décaissement Important',
                    'description' => 'Validation pour les décaissements supérieurs à 10000 EUR',
                    'module' => 'accounting',
                    'entity_type' => 'App\Models\AccountingEntry',
                    'company_id' => $company->id,
                    'conditions' => json_encode([
                        [
                            'field' => 'amount',
                            'operator' => 'greater_than',
                            'value' => 10000
                        ],
                        [
                            'field' => 'type',
                            'operator' => 'equals',
                            'value' => 'debit'
                        ]
                    ]),
                    'steps' => json_encode([
                        [
                            'name' => 'Validation Chef',
                            'description' => 'Validation par le chef de service',
                            'role' => 'manager',
                            'timeout_hours' => 24
                        ],
                        [
                            'name' => 'Validation DG',
                            'description' => 'Validation par le Directeur Général',
                            'role' => 'administrateur',
                            'timeout_hours' => 48
                        ]
                    ]),
                    'is_active' => true,
                    'created_by' => $users->random()->id
                ],
                [
                    'name' => 'Achat Équipement',
                    'description' => 'Validation pour les achats d\'équipement',
                    'module' => 'purchases',
                    'entity_type' => 'App\Models\PurchaseRequest',
                    'company_id' => $company->id,
                    'conditions' => json_encode([
                        [
                            'field' => 'category',
                            'operator' => 'equals',
                            'value' => 'equipment'
                        ]
                    ]),
                    'steps' => json_encode([
                        [
                            'name' => 'Validation DRH',
                            'description' => 'Validation par la Direction des Ressources Humaines',
                            'role' => 'hr',
                            'timeout_hours' => 48
                        ],
                        [
                            'name' => 'Validation DAFC',
                            'description' => 'Validation par le Directeur Administratif et Financier',
                            'role' => 'manager',
                            'timeout_hours' => 72
                        ]
                    ]),
                    'is_active' => true,
                    'created_by' => $users->random()->id
                ],
                [
                    'name' => 'Sortie Stock Important',
                    'description' => 'Validation pour les sorties de stock importantes',
                    'module' => 'inventory',
                    'entity_type' => 'App\Models\StockMovement',
                    'company_id' => $company->id,
                    'conditions' => json_encode([
                        [
                            'field' => 'type',
                            'operator' => 'equals',
                            'value' => 'out'
                        ],
                        [
                            'field' => 'quantity',
                            'operator' => 'greater_than',
                            'value' => 100
                        ]
                    ]),
                    'steps' => json_encode([
                        [
                            'name' => 'Validation Chef de Stock',
                            'description' => 'Validation par le Chef de Stock',
                            'role' => 'manager',
                            'timeout_hours' => 12
                        ]
                    ]),
                    'is_active' => true,
                    'created_by' => $users->random()->id
                ],
                [
                    'name' => 'Création de Client',
                    'description' => 'Validation pour la création de nouveaux clients',
                    'module' => 'clients',
                    'entity_type' => 'App\Models\Client',
                    'company_id' => $company->id,
                    'conditions' => json_encode([]),
                    'steps' => json_encode([
                        [
                            'name' => 'Validation Commercial',
                            'description' => 'Validation par le Responsable Commercial',
                            'role' => 'commercial',
                            'timeout_hours' => 24
                        ]
                    ]),
                    'is_active' => true,
                    'created_by' => $users->random()->id
                ],
                [
                    'name' => 'Création de Fournisseur',
                    'description' => 'Validation pour la création de nouveaux fournisseurs',
                    'module' => 'suppliers',
                    'entity_type' => 'App\Models\Fournisseur',
                    'company_id' => $company->id,
                    'conditions' => json_encode([]),
                    'steps' => json_encode([
                        [
                            'name' => 'Validation Achats',
                            'description' => 'Validation par le Responsable des Achats',
                            'role' => 'purchases',
                            'timeout_hours' => 48
                        ]
                    ]),
                    'is_active' => true,
                    'created_by' => $users->random()->id
                ]
            ];

            foreach ($workflows as $workflowData) {
                ValidationWorkflow::firstOrCreate(
                    [
                        'name' => $workflowData['name'],
                        'company_id' => $company->id
                    ],
                    $workflowData
                );
            }
        }
    }
}
