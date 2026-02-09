<?php

namespace App\Services\Connectors;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;
use App\Services\DataTransformationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PosConnector extends BaseConnector
{
    protected $dataTransformationService;

    public function __construct(DataTransformationService $dataTransformationService)
    {
        $this->dataTransformationService = $dataTransformationService;
    }

    /**
     * Test POS connection
     */
    public function testConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $baseUrl = rtrim($config['base_url'] ?? '', '/');
        $token = $config['token'] ?? null;

        if (!$baseUrl || !$token) {
            return [
                'success' => false,
                'message' => 'Configuration incomplète: URL de base et token requis'
            ];
        }

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get($baseUrl . '/api/status');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Connexion POS réussie',
                    'data' => [
                        'store_name' => $data['store_name'] ?? 'Non spécifié',
                        'version' => $data['version'] ?? 'Non spécifié',
                        'status' => $data['status'] ?? 'online'
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur POS: ' . $response->status() . ' - ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion POS: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sync sales data from POS
     */
    public function syncSalesData(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $baseUrl = rtrim($config['base_url'], '/');
        $token = $config['token'];
        
        $processed = 0;
        $successful = 0;
        $failed = 0;
        $errors = [];

        try {
            $params = [
                'since' => $connector->last_sync_at?->toISOString(),
                'limit' => $config['batch_size'] ?? 100
            ];

            if (!empty($config['store_id'])) {
                $params['store_id'] = $config['store_id'];
            }

            $response = Http::withToken($token)
                ->timeout(120)
                ->get($baseUrl . '/api/sales', $params);

            if (!$response->successful()) {
                throw new \Exception('POS API Error: ' . $response->status() . ' - ' . $response->body());
            }

            $responseData = $response->json();
            $sales = $responseData['data'] ?? [];
            $processed = count($sales);

            foreach ($sales as $sale) {
                try {
                    $transformedSale = $this->transformSaleData($sale, $connector);
                    $this->saveSaleData($transformedSale, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'sale_id' => $sale['id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            $status = $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : 
                     ($successful > 0 ? ApiSyncLog::STATUS_PARTIAL : ApiSyncLog::STATUS_FAILED);

            return [
                'status' => $status,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed,
                'error_details' => $errors
            ];

        } catch (\Exception $e) {
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
     * Transform POS sale data
     */
    protected function transformSaleData(array $saleData, ApiConnector $connector): array
    {
        return $this->dataTransformationService->transform($saleData, $connector, 'sale');
    }

    /**
     * Save sale data to ERP
     */
    protected function saveSaleData(array $data, ApiConnector $connector): void
    {
        // Create sale record and generate accounting entries
        $saleId = $this->createSaleRecord($data, $connector);
        $this->generateSaleAccountingEntries($data, $connector, $saleId);
    }

    /**
     * Create sale record
     */
    protected function createSaleRecord(array $data, ApiConnector $connector): int
    {
        // This would create a sale record in your sales table
        // Implementation depends on your sales model structure
        Log::info('POS sale recorded', [
            'connector_id' => $connector->id,
            'sale_data' => $data
        ]);
        
        return 1; // Return the created sale ID
    }

    /**
     * Generate accounting entries for sale
     */
    protected function generateSaleAccountingEntries(array $data, ApiConnector $connector, int $saleId): void
    {
        $company = $connector->company;
        
        // Create journal entry for the sale
        $salesJournal = \App\Models\AccountingJournal::firstOrCreate([
            'company_id' => $company->id,
            'code' => 'VTE'
        ], [
            'name' => 'Journal des Ventes',
            'type' => 'vente',
            'is_active' => true
        ]);

        // Debit customer account
        $customerAccount = \App\Models\ChartOfAccount::firstOrCreate([
            'company_id' => $company->id,
            'code' => '411000'
        ], [
            'name' => 'Clients',
            'type' => 'actif',
            'level' => 'compte',
            'is_active' => true
        ]);

        // Credit sales account
        $salesAccount = \App\Models\ChartOfAccount::firstOrCreate([
            'company_id' => $company->id,
            'code' => '701000'
        ], [
            'name' => 'Ventes de marchandises',
            'type' => 'produit',
            'level' => 'compte',
            'is_active' => true
        ]);

        $pieceNumber = 'VTE-' . $data['sale_number'] ?? time();
        $amount = $data['total_amount'] ?? 0;

        // Debit entry
        \App\Models\AccountingEntry::create([
            'company_id' => $company->id,
            'journal_id' => $salesJournal->id,
            'account_id' => $customerAccount->id,
            'date' => $data['sale_date'] ?? now(),
            'piece_number' => $pieceNumber,
            'description' => 'Vente POS #' . ($data['sale_number'] ?? $saleId),
            'debit' => $amount,
            'credit' => 0,
            'status' => 'validée',
            'sync_source' => 'pos'
        ]);

        // Credit entry
        \App\Models\AccountingEntry::create([
            'company_id' => $company->id,
            'journal_id' => $salesJournal->id,
            'account_id' => $salesAccount->id,
            'date' => $data['sale_date'] ?? now(),
            'piece_number' => $pieceNumber,
            'description' => 'Vente POS #' . ($data['sale_number'] ?? $saleId),
            'debit' => 0,
            'credit' => $amount,
            'status' => 'validée',
            'sync_source' => 'pos'
        ]);
    }

    /**
     * Get default configuration template
     */
    public static function getDefaultConfiguration(): array
    {
        return [
            'base_url' => '',
            'token' => '',
            'store_id' => '',
            'timeout' => 60,
            'batch_size' => 100,
            'sync_products' => true,
            'sync_customers' => true
        ];
    }

    /**
     * Get default data mappings
     */
    public static function getDefaultMappings(): array
    {
        return [
            [
                'entity_type' => 'sale',
                'external_field' => 'ticket_id',
                'internal_field' => 'sale_number',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'sale',
                'external_field' => 'datetime',
                'internal_field' => 'sale_date',
                'field_type' => 'datetime',
                'is_required' => true
            ],
            [
                'entity_type' => 'sale',
                'external_field' => 'total',
                'internal_field' => 'total_amount',
                'field_type' => 'currency',
                'is_required' => true
            ],
            [
                'entity_type' => 'sale',
                'external_field' => 'customer_id',
                'internal_field' => 'customer_id',
                'field_type' => 'string',
                'is_required' => false
            ]
        ];
    }

    /**
     * Get required configuration fields
     */
    protected function getRequiredConfigFields(): array
    {
        return ['base_url', 'token'];
    }
}