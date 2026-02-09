<?php

namespace App\Services;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;
use App\Models\ApiDataMapping;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiSyncService
{
    protected $dataTransformationService;

    public function __construct(DataTransformationService $dataTransformationService)
    {
        $this->dataTransformationService = $dataTransformationService;
    }

    /**
     * Trigger synchronization for a connector
     */
    public function triggerSync(ApiConnector $connector, string $type = 'scheduled', ?int $triggeredBy = null): ApiSyncLog
    {
        // Create sync log
        $syncLog = ApiSyncLog::create([
            'connector_id' => $connector->id,
            'type' => $type,
            'direction' => 'inbound', // Default direction, will be updated based on operation
            'status' => ApiSyncLog::STATUS_RUNNING,
            'started_at' => now(),
            'triggered_by' => $triggeredBy
        ]);

        try {
            // Update connector status
            $connector->update(['status' => ApiConnector::STATUS_SYNCING]);

            // Perform the sync based on connector type
            $result = $this->performSync($connector, $syncLog);

            // Update sync log with results
            $syncLog->update([
                'status' => $result['status'],
                'completed_at' => now(),
                'records_processed' => $result['records_processed'] ?? 0,
                'records_successful' => $result['records_successful'] ?? 0,
                'records_failed' => $result['records_failed'] ?? 0,
                'error_message' => $result['error_message'] ?? null,
                'error_details' => $result['error_details'] ?? null,
                'response_data' => $result['response_data'] ?? null,
                'execution_time' => now()->diffInSeconds($syncLog->started_at)
            ]);

            // Update connector status and last sync time
            $connector->update([
                'status' => $result['status'] === ApiSyncLog::STATUS_SUCCESS ? 
                    ApiConnector::STATUS_ACTIVE : ApiConnector::STATUS_ERROR,
                'last_sync_at' => now()
            ]);

            $connector->calculateNextSync();
            $connector->save();

        } catch (\Exception $e) {
            Log::error('Sync failed', [
                'connector_id' => $connector->id,
                'sync_log_id' => $syncLog->id,
                'error' => $e->getMessage()
            ]);

            $syncLog->update([
                'status' => ApiSyncLog::STATUS_FAILED,
                'completed_at' => now(),
                'error_message' => $e->getMessage(),
                'execution_time' => now()->diffInSeconds($syncLog->started_at)
            ]);

            $connector->update(['status' => ApiConnector::STATUS_ERROR]);
        }

        return $syncLog;
    }

    /**
     * Perform the actual synchronization
     */
    protected function performSync(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        switch ($connector->type) {
            case ApiConnector::TYPE_SAGE:
                return $this->syncSage($connector, $syncLog);
            
            case ApiConnector::TYPE_EBP:
                return $this->syncEbp($connector, $syncLog);
            
            case ApiConnector::TYPE_GOOGLE_SHEETS:
                return $this->syncGoogleSheets($connector, $syncLog);
            
            case ApiConnector::TYPE_POS:
                return $this->syncPos($connector, $syncLog);
            
            case ApiConnector::TYPE_EXCEL:
                return $this->syncExcel($connector, $syncLog);
            
            case ApiConnector::TYPE_CUSTOM:
                return $this->syncCustom($connector, $syncLog);
            
            default:
                throw new \Exception('Type de connecteur non supporté: ' . $connector->type);
        }
    }

    /**
     * Sync SAGE data
     */
    protected function syncSage(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $baseUrl = $config['base_url'];
        $apiKey = $config['api_key'];

        $processed = 0;
        $successful = 0;
        $failed = 0;
        $errors = [];

        try {
            // Get data from SAGE
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json'
            ])->timeout(60)->get($baseUrl . '/api/accounting/entries', [
                'since' => $connector->last_sync_at?->toISOString()
            ]);

            if (!$response->successful()) {
                throw new \Exception('SAGE API error: ' . $response->body());
            }

            $data = $response->json('data', []);
            $processed = count($data);

            // Process each record
            foreach ($data as $record) {
                try {
                    $transformedData = $this->dataTransformationService->transform(
                        $record, 
                        $connector, 
                        ApiDataMapping::ENTITY_ACCOUNTING
                    );

                    // Save to ERP (accounting entries)
                    $this->saveAccountingEntry($transformedData, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'record' => $record,
                        'error' => $e->getMessage()
                    ];
                    Log::warning('SAGE record sync failed', [
                        'connector_id' => $connector->id,
                        'record' => $record,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $status = $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : 
                     ($successful > 0 ? ApiSyncLog::STATUS_PARTIAL : ApiSyncLog::STATUS_FAILED);

            return [
                'status' => $status,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed,
                'error_details' => $errors,
                'response_data' => ['total_records' => $processed]
            ];

        } catch (\Exception $e) {
            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed,
                'error_message' => $e->getMessage(),
                'error_details' => $errors
            ];
        }
    }

    /**
     * Sync EBP data
     */
    protected function syncEbp(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $processed = 0;
        $successful = 0;
        $failed = 0;

        try {
            $response = Http::withBasicAuth($config['username'], $config['password'])
                ->timeout(60)
                ->get($config['base_url'] . '/api/data/export');

            if (!$response->successful()) {
                throw new \Exception('EBP API error: ' . $response->body());
            }

            $data = $response->json('data', []);
            $processed = count($data);

            foreach ($data as $record) {
                try {
                    $transformedData = $this->dataTransformationService->transform(
                        $record, 
                        $connector, 
                        $this->detectEntityType($record)
                    );

                    $this->saveRecord($transformedData, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    Log::warning('EBP record sync failed', [
                        'connector_id' => $connector->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return [
                'status' => $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : ApiSyncLog::STATUS_PARTIAL,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed
            ];

        } catch (\Exception $e) {
            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sync Google Sheets data
     */
    protected function syncGoogleSheets(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $processed = 0;
        $successful = 0;
        $failed = 0;

        try {
            $response = Http::timeout(60)->get(
                "https://sheets.googleapis.com/v4/spreadsheets/{$config['spreadsheet_id']}/values/{$config['sheet_name']}", 
                ['key' => $config['api_key']]
            );

            if (!$response->successful()) {
                throw new \Exception('Google Sheets API error: ' . $response->body());
            }

            $values = $response->json('values', []);
            $headers = $config['has_headers'] ? array_shift($values) : null;
            $processed = count($values);

            foreach ($values as $row) {
                try {
                    $record = $headers ? array_combine($headers, $row) : $row;
                    
                    $transformedData = $this->dataTransformationService->transform(
                        $record, 
                        $connector, 
                        $this->detectEntityType($record)
                    );

                    $this->saveRecord($transformedData, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    Log::warning('Google Sheets record sync failed', [
                        'connector_id' => $connector->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return [
                'status' => $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : ApiSyncLog::STATUS_PARTIAL,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed
            ];

        } catch (\Exception $e) {
            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sync POS data
     */
    protected function syncPos(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $processed = 0;
        $successful = 0;
        $failed = 0;

        try {
            $response = Http::withToken($config['token'])
                ->timeout(60)
                ->get($config['base_url'] . '/api/sales', [
                    'store_id' => $config['store_id'],
                    'since' => $connector->last_sync_at?->toISOString()
                ]);

            if (!$response->successful()) {
                throw new \Exception('POS API error: ' . $response->body());
            }

            $sales = $response->json('data', []);
            $processed = count($sales);

            foreach ($sales as $sale) {
                try {
                    $transformedData = $this->dataTransformationService->transform(
                        $sale, 
                        $connector, 
                        ApiDataMapping::ENTITY_SALE
                    );

                    $this->saveSale($transformedData, $connector);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    Log::warning('POS sale sync failed', [
                        'connector_id' => $connector->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return [
                'status' => $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : ApiSyncLog::STATUS_PARTIAL,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed
            ];

        } catch (\Exception $e) {
            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Sync Excel data (placeholder)
     */
    protected function syncExcel(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        // This would typically involve reading from a file path
        // Implementation would depend on file access method (FTP, local, etc.)
        return [
            'status' => ApiSyncLog::STATUS_SUCCESS,
            'records_processed' => 0,
            'records_successful' => 0,
            'records_failed' => 0
        ];
    }

    /**
     * Sync custom connector data
     */
    protected function syncCustom(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $processed = 0;
        $successful = 0;
        $failed = 0;

        try {
            $headers = $config['headers'] ?? [];
            $endpoint = $config['base_url'] . ($config['endpoint'] ?? '/api/data');

            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->get($endpoint);

            if (!$response->successful()) {
                throw new \Exception('Custom API error: ' . $response->body());
            }

            $data = $response->json();
            $processed = is_array($data) ? count($data) : 1;

            // Process based on configuration
            if (is_array($data)) {
                foreach ($data as $record) {
                    try {
                        $transformedData = $this->dataTransformationService->transform(
                            $record, 
                            $connector, 
                            $this->detectEntityType($record)
                        );

                        $this->saveRecord($transformedData, $connector);
                        $successful++;

                    } catch (\Exception $e) {
                        $failed++;
                    }
                }
            }

            return [
                'status' => $failed === 0 ? ApiSyncLog::STATUS_SUCCESS : ApiSyncLog::STATUS_PARTIAL,
                'records_processed' => $processed,
                'records_successful' => $successful,
                'records_failed' => $failed
            ];

        } catch (\Exception $e) {
            return [
                'status' => ApiSyncLog::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Save accounting entry to the ERP
     */
    protected function saveAccountingEntry(array $data, ApiConnector $connector): void
    {
        // Use the existing accounting integration service
        $accountingService = app(\App\Services\AccountingIntegrationService::class);
        
        // This would create accounting entries based on transformed data
        // Implementation depends on the specific accounting entry structure
    }

    /**
     * Save sale to the ERP
     */
    protected function saveSale(array $data, ApiConnector $connector): void
    {
        // Implementation for saving sales data
        // This would create sales records in the ERP
    }

    /**
     * Save generic record to the ERP
     */
    protected function saveRecord(array $data, ApiConnector $connector): void
    {
        // Generic save method based on entity type
        $entityType = $data['entity_type'] ?? 'generic';
        
        switch ($entityType) {
            case ApiDataMapping::ENTITY_CUSTOMER:
                $this->saveCustomer($data, $connector);
                break;
            case ApiDataMapping::ENTITY_SUPPLIER:
                $this->saveSupplier($data, $connector);
                break;
            case ApiDataMapping::ENTITY_PRODUCT:
                $this->saveProduct($data, $connector);
                break;
            // Add more entity types as needed
        }
    }

    /**
     * Save customer data
     */
    protected function saveCustomer(array $data, ApiConnector $connector): void
    {
        // Implementation for saving customer data
    }

    /**
     * Save supplier data
     */
    protected function saveSupplier(array $data, ApiConnector $connector): void
    {
        // Implementation for saving supplier data
    }

    /**
     * Save product data
     */
    protected function saveProduct(array $data, ApiConnector $connector): void
    {
        // Implementation for saving product data
    }

    /**
     * Detect entity type from record structure
     */
    protected function detectEntityType(array $record): string
    {
        // Simple heuristics to detect entity type
        if (isset($record['customer_name']) || isset($record['client_name'])) {
            return ApiDataMapping::ENTITY_CUSTOMER;
        }
        if (isset($record['supplier_name']) || isset($record['fournisseur'])) {
            return ApiDataMapping::ENTITY_SUPPLIER;
        }
        if (isset($record['product_name']) || isset($record['produit'])) {
            return ApiDataMapping::ENTITY_PRODUCT;
        }
        if (isset($record['debit']) || isset($record['credit']) || isset($record['account'])) {
            return ApiDataMapping::ENTITY_ACCOUNTING;
        }
        if (isset($record['sale_amount']) || isset($record['montant_vente'])) {
            return ApiDataMapping::ENTITY_SALE;
        }
        
        return 'generic';
    }

    /**
     * Get all connectors ready for sync
     */
    public function getConnectorsReadyForSync(): \Illuminate\Database\Eloquent\Collection
    {
        return ApiConnector::readyForSync()->get();
    }

    /**
     * Process scheduled synchronizations
     */
    public function processScheduledSyncs(): array
    {
        $connectors = $this->getConnectorsReadyForSync();
        $results = [];

        foreach ($connectors as $connector) {
            try {
                $syncLog = $this->triggerSync($connector, 'scheduled');
                $results[] = [
                    'connector_id' => $connector->id,
                    'connector_name' => $connector->name,
                    'sync_log_id' => $syncLog->id,
                    'status' => 'started'
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'connector_id' => $connector->id,
                    'connector_name' => $connector->name,
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }
}