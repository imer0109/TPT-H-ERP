<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiDataMapping extends Model
{
    protected $fillable = [
        'id',
        'connector_id',
        'entity_type',
        'external_field',
        'internal_field',
        'field_type',
        'transformation_rules',
        'validation_rules',
        'is_required',
        'default_value',
        'is_active'
    ];

    protected $casts = [
        'id' => 'string',
        'transformation_rules' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Entity types that can be synchronized
    const ENTITY_ACCOUNTING = 'accounting';
    const ENTITY_CUSTOMER = 'customer';
    const ENTITY_SUPPLIER = 'supplier';
    const ENTITY_PRODUCT = 'product';
    const ENTITY_EMPLOYEE = 'employee';
    const ENTITY_SALE = 'sale';
    const ENTITY_PURCHASE = 'purchase';
    const ENTITY_PAYMENT = 'payment';
    const ENTITY_PAYROLL = 'payroll';
    const ENTITY_STOCK = 'stock';
    const ENTITY_CASH = 'cash';

    // Field types for validation and transformation
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';
    const TYPE_CURRENCY = 'currency';
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_JSON = 'json';
    const TYPE_ARRAY = 'array';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the connector that owns this mapping
     */
    public function connector(): BelongsTo
    {
        return $this->belongsTo(ApiConnector::class, 'connector_id');
    }

    /**
     * Get available entity types
     */
    public static function getEntityTypes(): array
    {
        return [
            self::ENTITY_ACCOUNTING => 'Comptabilité',
            self::ENTITY_CUSTOMER => 'Clients',
            self::ENTITY_SUPPLIER => 'Fournisseurs',
            self::ENTITY_PRODUCT => 'Produits',
            self::ENTITY_EMPLOYEE => 'Employés',
            self::ENTITY_SALE => 'Ventes',
            self::ENTITY_PURCHASE => 'Achats',
            self::ENTITY_PAYMENT => 'Paiements',
            self::ENTITY_PAYROLL => 'Paie',
            self::ENTITY_STOCK => 'Stock',
            self::ENTITY_CASH => 'Caisse'
        ];
    }

    /**
     * Get available field types
     */
    public static function getFieldTypes(): array
    {
        return [
            self::TYPE_STRING => 'Texte',
            self::TYPE_INTEGER => 'Nombre entier',
            self::TYPE_FLOAT => 'Nombre décimal',
            self::TYPE_BOOLEAN => 'Booléen (Vrai/Faux)',
            self::TYPE_DATE => 'Date',
            self::TYPE_DATETIME => 'Date et heure',
            self::TYPE_EMAIL => 'Email',
            self::TYPE_PHONE => 'Téléphone',
            self::TYPE_CURRENCY => 'Montant',
            self::TYPE_PERCENTAGE => 'Pourcentage',
            self::TYPE_JSON => 'JSON',
            self::TYPE_ARRAY => 'Tableau'
        ];
    }

    /**
     * Apply transformation rules to a value
     */
    public function transformValue($value)
    {
        if (empty($this->transformation_rules)) {
            return $this->castValue($value);
        }

        foreach ($this->transformation_rules as $rule) {
            $value = $this->applyTransformationRule($value, $rule);
        }

        return $this->castValue($value);
    }

    /**
     * Apply a single transformation rule
     */
    protected function applyTransformationRule($value, array $rule)
    {
        $type = $rule['type'] ?? null;
        $params = $rule['params'] ?? [];

        switch ($type) {
            case 'trim':
                return is_string($value) ? trim($value) : $value;
            
            case 'upper':
                return is_string($value) ? strtoupper($value) : $value;
            
            case 'lower':
                return is_string($value) ? strtolower($value) : $value;
            
            case 'replace':
                if (is_string($value) && isset($params['search'], $params['replace'])) {
                    return str_replace($params['search'], $params['replace'], $value);
                }
                return $value;
            
            case 'regex_replace':
                if (is_string($value) && isset($params['pattern'], $params['replacement'])) {
                    return preg_replace($params['pattern'], $params['replacement'], $value);
                }
                return $value;
            
            case 'format_date':
                if (isset($params['from_format'], $params['to_format'])) {
                    try {
                        $date = \DateTime::createFromFormat($params['from_format'], $value);
                        return $date ? $date->format($params['to_format']) : $value;
                    } catch (\Exception $e) {
                        return $value;
                    }
                }
                return $value;
            
            case 'multiply':
                if (is_numeric($value) && isset($params['factor'])) {
                    return $value * $params['factor'];
                }
                return $value;
            
            case 'divide':
                if (is_numeric($value) && isset($params['divisor']) && $params['divisor'] != 0) {
                    return $value / $params['divisor'];
                }
                return $value;
            
            case 'round':
                if (is_numeric($value)) {
                    $decimals = $params['decimals'] ?? 2;
                    return round($value, $decimals);
                }
                return $value;
            
            case 'prefix':
                if (isset($params['prefix'])) {
                    return $params['prefix'] . $value;
                }
                return $value;
            
            case 'suffix':
                if (isset($params['suffix'])) {
                    return $value . $params['suffix'];
                }
                return $value;
            
            case 'default_if_empty':
                if (empty($value) && isset($params['default'])) {
                    return $params['default'];
                }
                return $value;
            
            default:
                return $value;
        }
    }

    /**
     * Cast value to the appropriate type
     */
    protected function castValue($value)
    {
        if ($value === null || $value === '') {
            return $this->default_value;
        }

        switch ($this->field_type) {
            case self::TYPE_INTEGER:
                return (int) $value;
            
            case self::TYPE_FLOAT:
            case self::TYPE_CURRENCY:
            case self::TYPE_PERCENTAGE:
                return (float) $value;
            
            case self::TYPE_BOOLEAN:
                return in_array(strtolower($value), ['true', '1', 'yes', 'oui', 'vrai']);
            
            case self::TYPE_DATE:
                try {
                    return \Carbon\Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    return $value;
                }
            
            case self::TYPE_DATETIME:
                try {
                    return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return $value;
                }
            
            case self::TYPE_JSON:
                return is_string($value) ? json_decode($value, true) : $value;
            
            case self::TYPE_ARRAY:
                return is_string($value) ? explode(',', $value) : (array) $value;
            
            case self::TYPE_STRING:
            default:
                return (string) $value;
        }
    }

    /**
     * Validate the value according to validation rules
     */
    public function validateValue($value): array
    {
        $errors = [];

        // Check if required field is empty
        if ($this->is_required && empty($value)) {
            $errors[] = "Le champ {$this->external_field} est requis";
        }

        // Apply validation rules
        if (!empty($this->validation_rules)) {
            foreach ($this->validation_rules as $rule) {
                $error = $this->applyValidationRule($value, $rule);
                if ($error) {
                    $errors[] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * Apply a single validation rule
     */
    protected function applyValidationRule($value, array $rule): ?string
    {
        $type = $rule['type'] ?? null;
        $params = $rule['params'] ?? [];
        $message = $rule['message'] ?? null;

        switch ($type) {
            case 'min_length':
                if (is_string($value) && isset($params['length']) && strlen($value) < $params['length']) {
                    return $message ?? "Le champ doit contenir au moins {$params['length']} caractères";
                }
                break;
            
            case 'max_length':
                if (is_string($value) && isset($params['length']) && strlen($value) > $params['length']) {
                    return $message ?? "Le champ ne peut pas dépasser {$params['length']} caractères";
                }
                break;
            
            case 'min_value':
                if (is_numeric($value) && isset($params['value']) && $value < $params['value']) {
                    return $message ?? "La valeur doit être supérieure ou égale à {$params['value']}";
                }
                break;
            
            case 'max_value':
                if (is_numeric($value) && isset($params['value']) && $value > $params['value']) {
                    return $message ?? "La valeur doit être inférieure ou égale à {$params['value']}";
                }
                break;
            
            case 'regex':
                if (is_string($value) && isset($params['pattern']) && !preg_match($params['pattern'], $value)) {
                    return $message ?? "Le format du champ n'est pas valide";
                }
                break;
            
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return $message ?? "L'adresse email n'est pas valide";
                }
                break;
            
            case 'date':
                try {
                    \Carbon\Carbon::parse($value);
                } catch (\Exception $e) {
                    return $message ?? "La date n'est pas dans un format valide";
                }
                break;
        }

        return null;
    }

    /**
     * Get entity type label
     */
    public function getEntityTypeLabel(): string
    {
        return self::getEntityTypes()[$this->entity_type] ?? $this->entity_type;
    }

    /**
     * Get field type label
     */
    public function getFieldTypeLabel(): string
    {
        return self::getFieldTypes()[$this->field_type] ?? $this->field_type;
    }

    /**
     * Scope for active mappings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific entity type
     */
    public function scopeForEntity($query, string $entityType)
    {
        return $query->where('entity_type', $entityType);
    }
}