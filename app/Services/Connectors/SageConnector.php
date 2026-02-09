<?php

namespace App\Services\Connectors;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;
use App\Services\DataTransformationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SageConnector extends BaseConnector
{
    protected $dataTransformationService;

    public function __construct(DataTransformationService $dataTransformationService)
    {
        $this->dataTransformationService = $dataTransformationService;
    }

    /**
     * Test SAGE connection
     */
    public function testConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $baseUrl = rtrim($config['base_url'] ?? '', '/');
        $apiKey = $config['api_key'] ?? null;

        if (!$baseUrl || !$apiKey) {
            return [
                'success' => false,
                'message' => 'Configuration incomplète: URL de base et clé API requises'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->timeout(30)->get($baseUrl . '/api/status');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Connexion SAGE réussie',
                    'data' => [
                        'version' => $data['version'] ?? 'Non spécifié',
                        'database' => $data['database'] ?? 'Non spécifié',
                        'server_time' => $data['server_time'] ?? now()->toISOString()
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur SAGE: ' . $response->status() . ' - ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion SAGE: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sync accounting data from SAGE
     */
    public function syncAccountingData(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $baseUrl = rtrim($config['base_url'], '/');
        $apiKey = $config['api_key'];
        
        $processed = 0;
        $successful = 0;
        $failed = 0;
        $errors = [];

        try {
            // Get accounting entries from SAGE
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json'
            ])->timeout(120)->get($baseUrl . '/api/accounting/entries', [
                'since' => $connector->last_sync_at?->toISOString(),
                'limit' => $config['batch_size'] ?? 100,
                'format' => 'detailed'
            ]);

            if (!$response->successful()) {
                throw new \Exception('SAGE API Error: ' . $response->status() . ' - ' . $response->body());
            }

            $responseData = $response->json();
            $entries = $responseData['data'] ?? [];
            $processed = count($entries);

            Log::info('SAGE sync started', [
                'connector_id' => $connector->id,
                'entries_count' => $processed
            ]);

            foreach ($entries as $entry) {
                try {
                    $transformedEntry = $this->transformAccountingEntry($entry, $connector);
                    $this->saveAccountingEntry($transformedEntry, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'entry_id' => $entry['id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                    
                    Log::warning('SAGE entry sync failed', [
                        'connector_id' => $connector->id,
                        'entry' => $entry,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Sync chart of accounts if configured
            if ($config['sync_chart_of_accounts'] ?? false) {
                $this->syncChartOfAccounts($connector, $baseUrl, $apiKey);
            }

            $status = $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : 
                     ($successful > 0 ? ApiSyncLog::STATUS_PARTIAL : ApiSyncLog::STATUS_FAILED);

            return [
                'status' => $status,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed,
                'error_details' => $errors,
                'response_data' => [
                    'total_entries' => $processed,
                    'has_more' => $responseData['has_more'] ?? false
                ]
            ];

        } catch (\Exception $e) {
            Log::error('SAGE sync failed', [
                'connector_id' => $connector->id,
                'error' => $e->getMessage()
            ]);

            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed + 1,
                'error_message' => $e->getMessage(),
                'error_details' => $errors
            ];
        }
    }

    /**
     * Sync chart of accounts from SAGE
     */
    protected function syncChartOfAccounts(ApiConnector $connector, string $baseUrl, string $apiKey): void
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json'
            ])->timeout(60)->get($baseUrl . '/api/accounting/chart-of-accounts');

            if ($response->successful()) {
                $accounts = $response->json('data', []);
                
                foreach ($accounts as $accountData) {
                    $this->createOrUpdateAccount($accountData, $connector);
                }
            }

        } catch (\Exception $e) {
            Log::warning('SAGE chart of accounts sync failed', [
                'connector_id' => $connector->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Transform SAGE accounting entry to ERP format
     */
    protected function transformAccountingEntry(array $sageEntry, ApiConnector $connector): array
    {
        return $this->dataTransformationService->transform(
            $sageEntry, 
            $connector, 
            'accounting'
        );
    }

    /**
     * Save accounting entry to ERP
     */
    protected function saveAccountingEntry(array $entryData, ApiConnector $connector): void
    {
        // Get required models
        $company = $connector->company;
        $journal = $this->getOrCreateJournal($entryData['journal_code'], $company);
        $account = $this->getOrCreateAccount($entryData['account_code'], $company);

        // Create accounting entry
        \App\Models\AccountingEntry::create([
            'company_id' => $company->id,
            'journal_id' => $journal->id,
            'account_id' => $account->id,
            'date' => $entryData['date'],
            'piece_number' => $entryData['piece_number'],
            'description' => $entryData['description'],
            'reference' => $entryData['reference'] ?? null,
            'debit' => $entryData['debit'] ?? 0,
            'credit' => $entryData['credit'] ?? 0,
            'currency' => $entryData['currency'] ?? 'EUR',
            'exchange_rate' => $entryData['exchange_rate'] ?? 1,
            'cost_center_id' => $this->getCostCenterId($entryData['cost_center'] ?? null, $company),
            'project_id' => $this->getProjectId($entryData['project'] ?? null, $company),
            'status' => 'validée',
            'external_id' => $entryData['external_id'] ?? null,
            'sync_source' => 'sage',
            'created_by' => $connector->created_by
        ]);
    }

    /**
     * Create or update account from SAGE data
     */
    protected function createOrUpdateAccount(array $accountData, ApiConnector $connector): void
    {
        $company = $connector->company;
        
        $account = \App\Models\ChartOfAccount::updateOrCreate([
            'company_id' => $company->id,
            'code' => $accountData['code']
        ], [
            'name' => $accountData['name'],
            'type' => $this->mapAccountType($accountData['type']),
            'level' => $this->determineAccountLevel($accountData['code']),
            'parent_id' => $this->getParentAccountId($accountData['code'], $company),
            'is_active' => $accountData['is_active'] ?? true,
            'external_id' => $accountData['id'] ?? null
        ]);
    }

    /**
     * Get or create journal
     */
    protected function getOrCreateJournal(string $journalCode, $company): \App\Models\AccountingJournal
    {
        return \App\Models\AccountingJournal::firstOrCreate([
            'company_id' => $company->id,
            'code' => $journalCode
        ], [
            'name' => 'Journal ' . $journalCode,
            'type' => $this->mapJournalType($journalCode),
            'is_active' => true
        ]);
    }

    /**
     * Get or create account
     */
    protected function getOrCreateAccount(string $accountCode, $company): \App\Models\ChartOfAccount
    {
        return \App\Models\ChartOfAccount::firstOrCreate([
            'company_id' => $company->id,
            'code' => $accountCode
        ], [
            'name' => 'Compte ' . $accountCode,
            'type' => $this->mapAccountTypeFromCode($accountCode),
            'level' => $this->determineAccountLevel($accountCode),
            'is_active' => true
        ]);
    }

    /**
     * Map SAGE account type to ERP account type
     */
    protected function mapAccountType(string $sageType): string
    {
        $mapping = [
            'ASSET' => 'actif',
            'LIABILITY' => 'passif',
            'EQUITY' => 'capitaux_propres',
            'REVENUE' => 'produit',
            'EXPENSE' => 'charge'
        ];

        return $mapping[$sageType] ?? 'actif';
    }

    /**
     * Map account type from code
     */
    protected function mapAccountTypeFromCode(string $code): string
    {
        $firstDigit = substr($code, 0, 1);
        
        switch ($firstDigit) {
            case '1': return 'passif';
            case '2': return 'actif';
            case '3': return 'actif';
            case '4': return 'passif';
            case '5': return 'actif';
            case '6': return 'charge';
            case '7': return 'produit';
            default: return 'actif';
        }
    }

    /**
     * Map journal type from code
     */
    protected function mapJournalType(string $code): string
    {
        $code = strtoupper($code);
        
        if (in_array($code, ['VTE', 'VEN', 'SALES'])) return 'vente';
        if (in_array($code, ['ACH', 'PURCHASE'])) return 'achat';
        if (in_array($code, ['BAN', 'BANK'])) return 'banque';
        if (in_array($code, ['CAI', 'CASH'])) return 'caisse';
        
        return 'opérations_diverses';
    }

    /**
     * Determine account level from code
     */
    protected function determineAccountLevel(string $code): string
    {
        $length = strlen($code);
        
        if ($length <= 1) return 'classe';
        if ($length <= 2) return 'sous_classe';
        if ($length <= 5) return 'compte';
        
        return 'sous_compte';
    }

    /**
     * Get parent account ID
     */
    protected function getParentAccountId(string $code, $company): ?int
    {
        if (strlen($code) <= 1) return null;
        
        $parentCode = substr($code, 0, -1);
        $parent = \App\Models\ChartOfAccount::where('company_id', $company->id)
            ->where('code', $parentCode)
            ->first();
            
        return $parent?->id;
    }

    /**
     * Get cost center ID
     */
    protected function getCostCenterId(?string $costCenterCode, $company): ?int
    {
        if (!$costCenterCode) return null;
        
        $costCenter = \App\Models\CostCenter::where('company_id', $company->id)
            ->where('code', $costCenterCode)
            ->first();
            
        return $costCenter?->id;
    }

    /**
     * Get project ID
     */
    protected function getProjectId(?string $projectCode, $company): ?int
    {
        if (!$projectCode) return null;
        
        $project = \App\Models\Project::where('company_id', $company->id)
            ->where('code', $projectCode)
            ->first();
            
        return $project?->id;
    }

    /**
     * Export data to SAGE
     */
    public function exportToSage(ApiConnector $connector, array $data): array
    {
        $config = $connector->configuration;
        $baseUrl = rtrim($config['base_url'], '/');
        $apiKey = $config['api_key'];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->timeout(60)->post($baseUrl . '/api/accounting/entries', [
                'entries' => $data
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Export vers SAGE réussi',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur export SAGE: ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur export SAGE: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get default configuration template
     */
    public static function getDefaultConfiguration(): array
    {
        return [
            'base_url' => '',
            'api_key' => '',
            'version' => 'v1',
            'timeout' => 60,
            'batch_size' => 100,
            'sync_chart_of_accounts' => true,
            'verify_ssl' => true,
            'compression' => false
        ];
    }

    /**
     * Get default data mappings
     */
    public static function getDefaultMappings(): array
    {
        return [
            [
                'entity_type' => 'accounting',
                'external_field' => 'PieceRef',
                'internal_field' => 'piece_number',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'PieceDate',
                'internal_field' => 'date',
                'field_type' => 'date',
                'is_required' => true,
                'transformation_rules' => [
                    ['type' => 'format_date', 'params' => ['from_format' => 'Ymd', 'to_format' => 'Y-m-d']]
                ]
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'JournalCode',
                'internal_field' => 'journal_code',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'CompteNum',
                'internal_field' => 'account_code',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'EcritureLib',
                'internal_field' => 'description',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'Debit',
                'internal_field' => 'debit',
                'field_type' => 'currency',
                'is_required' => false,
                'default_value' => '0'
            ],
            [
                'entity_type' => 'accounting',
                'external_field' => 'Credit',
                'internal_field' => 'credit',
                'field_type' => 'currency',
                'is_required' => false,
                'default_value' => '0'
            ]
        ];
    }
}