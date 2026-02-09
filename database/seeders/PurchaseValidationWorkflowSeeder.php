<?php

namespace Database\Seeders;

use App\Models\ValidationWorkflow;
use App\Models\Company;
use Illuminate\Database\Seeder;

class PurchaseValidationWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = Company::all();
        
        // For each company, create default purchase validation workflows
        foreach ($companies as $company) {
            // Check if workflows already exist for this company
            $existingWorkflows = ValidationWorkflow::where('module', 'purchases')
                ->where('entity_type', 'App\Models\PurchaseRequest')
                ->where('company_id', $company->id)
                ->count();
                
            // If no workflows exist, create default ones
            if ($existingWorkflows == 0) {
                $this->createDefaultPurchaseWorkflows($company->id);
            }
        }
    }
    
    /**
     * Create default purchase validation workflows for a company
     */
    private function createDefaultPurchaseWorkflows($companyId)
    {
        $workflows = [
            [
                'name' => 'Achat Faible Montant',
                'description' => 'Validation pour les achats inférieurs à 50 000 XAF',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'prix_estime_total',
                        'operator' => 'less_than_or_equal',
                        'value' => 50000
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Service',
                        'description' => 'Validation par le chef de service demandeur',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Achat Moyen Montant',
                'description' => 'Validation pour les achats entre 50 000 XAF et 100 000 XAF',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'prix_estime_total',
                        'operator' => 'greater_than',
                        'value' => 50000
                    ],
                    [
                        'field' => 'prix_estime_total',
                        'operator' => 'less_than_or_equal',
                        'value' => 100000
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Service',
                        'description' => 'Validation par le chef de service demandeur',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ],
                    [
                        'name' => 'Validation Responsable Financier',
                        'description' => 'Validation par le responsable financier',
                        'role' => 'responsable_financier',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Achat Élevé Montant',
                'description' => 'Validation pour les achats supérieurs à 100 000 XAF',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'prix_estime_total',
                        'operator' => 'greater_than',
                        'value' => 100000
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Service',
                        'description' => 'Validation par le chef de service demandeur',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ],
                    [
                        'name' => 'Validation Responsable Financier',
                        'description' => 'Validation par le responsable financier',
                        'role' => 'responsable_financier',
                        'timeout_hours' => 48
                    ],
                    [
                        'name' => 'Validation Directeur Général',
                        'description' => 'Validation par le Directeur Général',
                        'role' => 'directeur_general',
                        'timeout_hours' => 72
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Achat Équipement',
                'description' => 'Validation pour les achats d\'équipement quel que soit le montant',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'nature_achat',
                        'operator' => 'equals',
                        'value' => 'Bien'
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Service',
                        'description' => 'Validation par le chef de service demandeur',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ],
                    [
                        'name' => 'Validation DRH',
                        'description' => 'Validation par la Direction des Ressources Humaines',
                        'role' => 'drh',
                        'timeout_hours' => 48
                    ],
                    [
                        'name' => 'Validation Responsable Financier',
                        'description' => 'Validation par le responsable financier',
                        'role' => 'responsable_financier',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Achat Service',
                'description' => 'Validation pour les achats de services quel que soit le montant',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'nature_achat',
                        'operator' => 'equals',
                        'value' => 'Service'
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Service',
                        'description' => 'Validation par le chef de service demandeur',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ],
                    [
                        'name' => 'Validation Responsable Financier',
                        'description' => 'Validation par le responsable financier',
                        'role' => 'responsable_financier',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($workflows as $workflow) {
            ValidationWorkflow::create($workflow);
        }
    }
}