<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'module',
        'entity_type',
        'company_id',
        'conditions',
        'steps',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'conditions' => 'json',
        'steps' => 'json',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the company that owns this workflow
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created this workflow
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this workflow
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all validation requests for this workflow
     */
    public function validationRequests()
    {
        return $this->hasMany(ValidationRequest::class, 'workflow_id');
    }

    /**
     * Check if conditions are met for an entity
     */
    public function conditionsAreMet($entity)
    {
        if (empty($this->conditions)) {
            return true;
        }

        foreach ($this->conditions as $condition) {
            if (!$this->evaluateCondition($condition, $entity)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition
     */
    protected function evaluateCondition($condition, $entity)
    {
        $field = $condition['field'];
        $operator = $condition['operator'];
        $value = $condition['value'];

        $entityValue = data_get($entity, $field);

        switch ($operator) {
            case 'equals':
                return $entityValue == $value;
            case 'not_equals':
                return $entityValue != $value;
            case 'greater_than':
                return $entityValue > $value;
            case 'greater_than_or_equal':
                return $entityValue >= $value;
            case 'less_than':
                return $entityValue < $value;
            case 'less_than_or_equal':
                return $entityValue <= $value;
            case 'contains':
                return str_contains(strtolower($entityValue), strtolower($value));
            case 'in':
                return in_array($entityValue, (array) $value);
            case 'not_in':
                return !in_array($entityValue, (array) $value);
            default:
                return false;
        }
    }

    /**
     * Get next validator for a specific step
     */
    public function getNextValidator($currentStep = 0)
    {
        $steps = $this->steps;
        
        if (isset($steps[$currentStep + 1])) {
            return $steps[$currentStep + 1];
        }

        return null;
    }

    /**
     * Check if workflow is complete
     */
    public function isComplete($currentStep)
    {
        return $currentStep >= count($this->steps) - 1;
    }

    /**
     * Create validation request for an entity
     */
    public function createValidationRequest($entity, $requestedBy, $reason = null)
    {
        return ValidationRequest::create([
            'workflow_id' => $this->id,
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'company_id' => $this->company_id,
            'requested_by' => $requestedBy,
            'current_step' => 0,
            'status' => 'pending',
            'reason' => $reason,
            'data_snapshot' => $entity->toArray()
        ]);
    }

    /**
     * Scope for active workflows
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific module
     */
    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope for specific entity type
     */
    public function scopeForEntityType($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope for specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get default validation workflows for common scenarios
     */
    public static function createDefaultWorkflows($companyId)
    {
        $workflows = [
            [
                'name' => 'Décaissement Important',
                'description' => 'Validation pour les décaissements supérieurs à 100 000 XAF',
                'module' => 'accounting',
                'entity_type' => 'App\Models\AccountingEntry',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'amount',
                        'operator' => 'greater_than',
                        'value' => 100000
                    ],
                    [
                        'field' => 'type',
                        'operator' => 'equals',
                        'value' => 'debit'
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef',
                        'description' => 'Validation par le chef de service',
                        'role' => 'chef_service',
                        'timeout_hours' => 24
                    ],
                    [
                        'name' => 'Validation DG',
                        'description' => 'Validation par le Directeur Général',
                        'role' => 'directeur_general',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Achat Équipement',
                'description' => 'Validation pour les achats d\'équipement',
                'module' => 'purchases',
                'entity_type' => 'App\Models\PurchaseRequest',
                'company_id' => $companyId,
                'conditions' => [
                    [
                        'field' => 'category',
                        'operator' => 'equals',
                        'value' => 'equipment'
                    ]
                ],
                'steps' => [
                    [
                        'name' => 'Validation DRH',
                        'description' => 'Validation par la Direction des Ressources Humaines',
                        'role' => 'drh',
                        'timeout_hours' => 48
                    ],
                    [
                        'name' => 'Validation DAFC',
                        'description' => 'Validation par le Directeur Administratif et Financier',
                        'role' => 'dafc',
                        'timeout_hours' => 72
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Sortie Stock Important',
                'description' => 'Validation pour les sorties de stock importantes',
                'module' => 'inventory',
                'entity_type' => 'App\Models\StockMovement',
                'company_id' => $companyId,
                'conditions' => [
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
                ],
                'steps' => [
                    [
                        'name' => 'Validation Chef de Stock',
                        'description' => 'Validation par le Chef de Stock',
                        'role' => 'chef_stock',
                        'timeout_hours' => 12
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Création de Société',
                'description' => 'Validation pour la création de nouvelles sociétés',
                'module' => 'companies',
                'entity_type' => 'App\Models\Company',
                'company_id' => $companyId,
                'conditions' => [],
                'steps' => [
                    [
                        'name' => 'Validation DG',
                        'description' => 'Validation par le Directeur Général',
                        'role' => 'directeur_general',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Création d\'Agence',
                'description' => 'Validation pour la création de nouvelles agences',
                'module' => 'agencies',
                'entity_type' => 'App\Models\Agency',
                'company_id' => $companyId,
                'conditions' => [],
                'steps' => [
                    [
                        'name' => 'Validation DG',
                        'description' => 'Validation par le Directeur Général',
                        'role' => 'directeur_general',
                        'timeout_hours' => 48
                    ]
                ],
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($workflows as $workflow) {
            self::create($workflow);
        }
    }
}