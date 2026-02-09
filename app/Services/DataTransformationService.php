<?php

namespace App\Services;

use App\Models\ApiConnector;
use App\Models\ApiDataMapping;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataTransformationService
{
    /**
     * Transform external data to internal ERP format
     */
    public function transform(array $externalData, ApiConnector $connector, string $entityType): array
    {
        $mappings = $connector->dataMappings()
            ->where('entity_type', $entityType)
            ->where('is_active', true)
            ->get();

        $transformedData = [];
        $errors = [];

        foreach ($mappings as $mapping) {
            try {
                $externalValue = $this->extractValue($externalData, $mapping->external_field);
                
                // Apply transformations
                $transformedValue = $mapping->transformValue($externalValue);
                
                // Validate the transformed value
                $validationErrors = $mapping->validateValue($transformedValue);
                if (!empty($validationErrors)) {
                    $errors = array_merge($errors, $validationErrors);
                    continue;
                }

                // Store the transformed value
                $transformedData[$mapping->internal_field] = $transformedValue;

            } catch (\Exception $e) {
                $errors[] = "Erreur de transformation pour le champ {$mapping->external_field}: " . $e->getMessage();
                Log::warning('Field transformation failed', [
                    'connector_id' => $connector->id,
                    'mapping_id' => $mapping->id,
                    'external_field' => $mapping->external_field,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if (!empty($errors)) {
            throw new \Exception('Erreurs de transformation: ' . implode(', ', $errors));
        }

        // Add metadata
        $transformedData['entity_type'] = $entityType;
        $transformedData['connector_id'] = $connector->id;
        $transformedData['company_id'] = $connector->company_id;
        $transformedData['sync_timestamp'] = now();

        return $transformedData;
    }

    /**
     * Extract value from nested array using dot notation
     */
    protected function extractValue(array $data, string $field)
    {
        $keys = explode('.', $field);
        $value = $data;

        foreach ($keys as $key) {
            if (is_array($value) && array_key_exists($key, $value)) {
                $value = $value[$key];
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * Transform data for export (reverse transformation)
     */
    public function transformForExport(array $internalData, ApiConnector $connector, string $entityType): array
    {
        $mappings = $connector->dataMappings()
            ->where('entity_type', $entityType)
            ->where('is_active', true)
            ->get();

        $exportData = [];

        foreach ($mappings as $mapping) {
            $internalValue = $internalData[$mapping->internal_field] ?? null;
            
            if ($internalValue !== null) {
                // Apply reverse transformations if needed
                $exportValue = $this->applyReverseTransformation($internalValue, $mapping);
                $exportData[$mapping->external_field] = $exportValue;
            }
        }

        return $exportData;
    }

    /**
     * Apply reverse transformation for export
     */
    protected function applyReverseTransformation($value, ApiDataMapping $mapping)
    {
        // Apply reverse transformations based on field type and rules
        switch ($mapping->field_type) {
            case ApiDataMapping::TYPE_DATE:
                if ($value instanceof Carbon) {
                    return $value->format('Y-m-d');
                }
                break;
                
            case ApiDataMapping::TYPE_DATETIME:
                if ($value instanceof Carbon) {
                    return $value->format('Y-m-d H:i:s');
                }
                break;
                
            case ApiDataMapping::TYPE_CURRENCY:
                return number_format((float) $value, 2, '.', '');
                
            case ApiDataMapping::TYPE_BOOLEAN:
                return $value ? 'true' : 'false';
                
            case ApiDataMapping::TYPE_JSON:
                return is_array($value) ? json_encode($value) : $value;
                
            case ApiDataMapping::TYPE_ARRAY:
                return is_array($value) ? implode(',', $value) : $value;
        }

        return $value;
    }

    /**
     * Validate data structure before transformation
     */
    public function validateDataStructure(array $data, ApiConnector $connector, string $entityType): array
    {
        $requiredMappings = $connector->dataMappings()
            ->where('entity_type', $entityType)
            ->where('is_required', true)
            ->where('is_active', true)
            ->get();

        $errors = [];

        foreach ($requiredMappings as $mapping) {
            $value = $this->extractValue($data, $mapping->external_field);
            
            if ($value === null || $value === '') {
                $errors[] = "Champ requis manquant: {$mapping->external_field}";
            }
        }

        return $errors;
    }

    /**
     * Clean and normalize data
     */
    public function cleanData(array $data): array
    {
        return array_map(function($value) {
            if (is_string($value)) {
                // Trim whitespace
                $value = trim($value);
                
                // Remove null bytes
                $value = str_replace("\0", '', $value);
                
                // Normalize line endings
                $value = str_replace(["\r\n", "\r"], "\n", $value);
            }
            
            return $value;
        }, $data);
    }

    /**
     * Apply data quality rules
     */
    public function applyDataQualityRules(array $data, ApiConnector $connector): array
    {
        $qualityRules = $connector->getConfig('data_quality_rules', []);
        $issues = [];

        foreach ($qualityRules as $rule) {
            $ruleType = $rule['type'] ?? null;
            $field = $rule['field'] ?? null;
            $value = $data[$field] ?? null;

            switch ($ruleType) {
                case 'duplicate_check':
                    if ($this->checkForDuplicate($value, $field, $connector)) {
                        $issues[] = "Doublon détecté pour le champ {$field}: {$value}";
                    }
                    break;

                case 'format_validation':
                    $pattern = $rule['pattern'] ?? null;
                    if ($pattern && !preg_match($pattern, (string) $value)) {
                        $issues[] = "Format invalide pour le champ {$field}: {$value}";
                    }
                    break;

                case 'range_validation':
                    $min = $rule['min'] ?? null;
                    $max = $rule['max'] ?? null;
                    if (is_numeric($value)) {
                        if ($min !== null && $value < $min) {
                            $issues[] = "Valeur trop petite pour le champ {$field}: {$value} (min: {$min})";
                        }
                        if ($max !== null && $value > $max) {
                            $issues[] = "Valeur trop grande pour le champ {$field}: {$value} (max: {$max})";
                        }
                    }
                    break;

                case 'reference_validation':
                    $referenceTable = $rule['reference_table'] ?? null;
                    $referenceField = $rule['reference_field'] ?? 'id';
                    if ($referenceTable && !$this->validateReference($value, $referenceTable, $referenceField)) {
                        $issues[] = "Référence invalide pour le champ {$field}: {$value}";
                    }
                    break;
            }
        }

        return $issues;
    }

    /**
     * Check for duplicate values
     */
    protected function checkForDuplicate($value, string $field, ApiConnector $connector): bool
    {
        // This would check against existing data in the ERP
        // Implementation depends on the specific entity and field
        return false;
    }

    /**
     * Validate reference integrity
     */
    protected function validateReference($value, string $table, string $field): bool
    {
        // This would check if the reference exists in the target table
        // Implementation depends on the specific reference
        return true;
    }

    /**
     * Transform data based on predefined templates
     */
    public function transformWithTemplate(array $data, string $templateName, ApiConnector $connector): array
    {
        $template = $this->getTemplate($templateName);
        
        if (!$template) {
            throw new \Exception("Template '{$templateName}' non trouvé");
        }

        $transformedData = [];

        foreach ($template['mappings'] as $mapping) {
            $sourceField = $mapping['source'];
            $targetField = $mapping['target'];
            $transformation = $mapping['transformation'] ?? null;

            $value = $this->extractValue($data, $sourceField);

            if ($transformation) {
                $value = $this->applyTemplateTransformation($value, $transformation);
            }

            $transformedData[$targetField] = $value;
        }

        return $transformedData;
    }

    /**
     * Apply template-based transformation
     */
    protected function applyTemplateTransformation($value, array $transformation)
    {
        $type = $transformation['type'] ?? null;
        $params = $transformation['params'] ?? [];

        switch ($type) {
            case 'date_format':
                $fromFormat = $params['from'] ?? 'Y-m-d';
                $toFormat = $params['to'] ?? 'Y-m-d H:i:s';
                try {
                    $date = Carbon::createFromFormat($fromFormat, $value);
                    return $date->format($toFormat);
                } catch (\Exception $e) {
                    return $value;
                }

            case 'currency_convert':
                $rate = $params['rate'] ?? 1;
                return is_numeric($value) ? $value * $rate : $value;

            case 'lookup':
                $lookupTable = $params['table'] ?? [];
                return $lookupTable[$value] ?? $value;

            case 'concatenate':
                $fields = $params['fields'] ?? [];
                $separator = $params['separator'] ?? ' ';
                $values = [];
                foreach ($fields as $field) {
                    $fieldValue = $this->extractValue($data, $field);
                    if ($fieldValue) {
                        $values[] = $fieldValue;
                    }
                }
                return implode($separator, $values);

            case 'split':
                $delimiter = $params['delimiter'] ?? ',';
                $index = $params['index'] ?? 0;
                $parts = explode($delimiter, (string) $value);
                return $parts[$index] ?? '';

            default:
                return $value;
        }
    }

    /**
     * Get transformation template
     */
    protected function getTemplate(string $templateName): ?array
    {
        $templates = [
            'sage_accounting' => [
                'mappings' => [
                    ['source' => 'Journal', 'target' => 'journal_code'],
                    ['source' => 'Compte', 'target' => 'account_code'],
                    ['source' => 'Date', 'target' => 'date', 'transformation' => ['type' => 'date_format', 'params' => ['from' => 'd/m/Y']]],
                    ['source' => 'Debit', 'target' => 'debit'],
                    ['source' => 'Credit', 'target' => 'credit'],
                    ['source' => 'Libelle', 'target' => 'description']
                ]
            ],
            'ebp_customers' => [
                'mappings' => [
                    ['source' => 'Code', 'target' => 'code'],
                    ['source' => 'RaisonSociale', 'target' => 'name'],
                    ['source' => 'Adresse', 'target' => 'address'],
                    ['source' => 'Email', 'target' => 'email'],
                    ['source' => 'Telephone', 'target' => 'phone']
                ]
            ],
            'pos_sales' => [
                'mappings' => [
                    ['source' => 'ticket_id', 'target' => 'sale_number'],
                    ['source' => 'datetime', 'target' => 'sale_date', 'transformation' => ['type' => 'date_format', 'params' => ['from' => 'Y-m-d H:i:s']]],
                    ['source' => 'total', 'target' => 'total_amount'],
                    ['source' => 'customer_id', 'target' => 'customer_id'],
                    ['source' => 'cashier', 'target' => 'cashier_name']
                ]
            ]
        ];

        return $templates[$templateName] ?? null;
    }

    /**
     * Generate mapping suggestions based on field names
     */
    public function suggestMappings(array $externalData, string $entityType): array
    {
        $suggestions = [];
        $fieldMappings = $this->getCommonFieldMappings($entityType);

        foreach ($externalData as $externalField => $value) {
            $suggestion = $this->findBestMatch($externalField, $fieldMappings);
            if ($suggestion) {
                $suggestions[] = [
                    'external_field' => $externalField,
                    'suggested_internal_field' => $suggestion['internal_field'],
                    'confidence' => $suggestion['confidence'],
                    'field_type' => $this->detectFieldType($value)
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Get common field mappings for entity types
     */
    protected function getCommonFieldMappings(string $entityType): array
    {
        $mappings = [
            ApiDataMapping::ENTITY_CUSTOMER => [
                'nom' => 'name', 'name' => 'name', 'raison_sociale' => 'name',
                'email' => 'email', 'mail' => 'email',
                'telephone' => 'phone', 'phone' => 'phone', 'tel' => 'phone',
                'adresse' => 'address', 'address' => 'address',
                'code' => 'code', 'code_client' => 'code'
            ],
            ApiDataMapping::ENTITY_SUPPLIER => [
                'nom' => 'name', 'name' => 'name', 'raison_sociale' => 'name',
                'email' => 'email', 'mail' => 'email',
                'telephone' => 'phone', 'phone' => 'phone',
                'adresse' => 'address', 'address' => 'address',
                'code' => 'code', 'code_fournisseur' => 'code'
            ],
            ApiDataMapping::ENTITY_PRODUCT => [
                'nom' => 'name', 'name' => 'name', 'designation' => 'name',
                'reference' => 'reference', 'ref' => 'reference',
                'prix' => 'price', 'price' => 'price', 'prix_unitaire' => 'price',
                'stock' => 'stock_quantity', 'quantite' => 'stock_quantity'
            ],
            ApiDataMapping::ENTITY_ACCOUNTING => [
                'journal' => 'journal_code', 'code_journal' => 'journal_code',
                'compte' => 'account_code', 'code_compte' => 'account_code',
                'date' => 'date', 'date_ecriture' => 'date',
                'debit' => 'debit', 'montant_debit' => 'debit',
                'credit' => 'credit', 'montant_credit' => 'credit',
                'libelle' => 'description', 'description' => 'description'
            ]
        ];

        return $mappings[$entityType] ?? [];
    }

    /**
     * Find best matching internal field
     */
    protected function findBestMatch(string $externalField, array $fieldMappings): ?array
    {
        $externalField = strtolower($externalField);
        
        // Exact match
        if (isset($fieldMappings[$externalField])) {
            return [
                'internal_field' => $fieldMappings[$externalField],
                'confidence' => 1.0
            ];
        }

        // Partial match
        $bestMatch = null;
        $bestScore = 0;

        foreach ($fieldMappings as $external => $internal) {
            $score = $this->calculateSimilarity($externalField, $external);
            if ($score > $bestScore && $score > 0.7) {
                $bestScore = $score;
                $bestMatch = [
                    'internal_field' => $internal,
                    'confidence' => $score
                ];
            }
        }

        return $bestMatch;
    }

    /**
     * Calculate similarity between field names
     */
    protected function calculateSimilarity(string $field1, string $field2): float
    {
        $maxLen = max(strlen($field1), strlen($field2));
        if ($maxLen === 0) return 1.0;
        
        $levenshtein = levenshtein($field1, $field2);
        return 1 - ($levenshtein / $maxLen);
    }

    /**
     * Detect field type from value
     */
    protected function detectFieldType($value): string
    {
        if (is_bool($value)) {
            return ApiDataMapping::TYPE_BOOLEAN;
        }
        
        if (is_int($value)) {
            return ApiDataMapping::TYPE_INTEGER;
        }
        
        if (is_float($value)) {
            return ApiDataMapping::TYPE_FLOAT;
        }
        
        if (is_array($value)) {
            return ApiDataMapping::TYPE_ARRAY;
        }
        
        if (is_string($value)) {
            // Check for date patterns
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return ApiDataMapping::TYPE_DATE;
            }
            
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                return ApiDataMapping::TYPE_DATETIME;
            }
            
            // Check for email
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return ApiDataMapping::TYPE_EMAIL;
            }
            
            // Check for currency
            if (preg_match('/^\d+(\.\d{2})?$/', $value)) {
                return ApiDataMapping::TYPE_CURRENCY;
            }
        }
        
        return ApiDataMapping::TYPE_STRING;
    }
}