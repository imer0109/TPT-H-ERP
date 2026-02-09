<?php

namespace App\Services;

use App\Models\ApiConnector;
use App\Models\ApiDataMapping;
use App\Models\ApiSyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiConnectorService
{
    /**
     * Test connection for a given connector
     */
    public function testConnection(ApiConnector $connector): array
    {
        try {
            switch ($connector->type) {
                case ApiConnector::TYPE_SAGE:
                    return $this->testSageConnection($connector);
                
                case ApiConnector::TYPE_EBP:
                    return $this->testEbpConnection($connector);
                
                case ApiConnector::TYPE_GOOGLE_SHEETS:
                    return $this->testGoogleSheetsConnection($connector);
                
                case ApiConnector::TYPE_POS:
                    return $this->testPosConnection($connector);
                
                case ApiConnector::TYPE_CUSTOM:
                    return $this->testCustomConnection($connector);
                
                default:
                    return ['success' => false, 'message' => 'Type de connecteur non supporté'];
            }
        } catch (\Exception $e) {
            Log::error('Connector test failed', [
                'connector_id' => $connector->id,
                'error' => $e->getMessage()
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Test SAGE connection
     */
    private function testSageConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $baseUrl = $config['base_url'] ?? null;
        $apiKey = $config['api_key'] ?? null;

        if (!$baseUrl || !$apiKey) {
            return ['success' => false, 'message' => 'Configuration incomplète (URL de base et clé API requises)'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json'
        ])->timeout(30)->get($baseUrl . '/api/ping');

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Connexion SAGE réussie',
                'data' => $response->json()
            ];
        }

        return [
            'success' => false,
            'message' => 'Échec de connexion SAGE: ' . $response->body()
        ];
    }

    /**
     * Test EBP connection
     */
    private function testEbpConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $baseUrl = $config['base_url'] ?? null;
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;

        if (!$baseUrl || !$username || !$password) {
            return ['success' => false, 'message' => 'Configuration incomplète (URL, nom d\'utilisateur et mot de passe requis)'];
        }

        $response = Http::withBasicAuth($username, $password)
            ->timeout(30)
            ->get($baseUrl . '/api/test');

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Connexion EBP réussie',
                'data' => $response->json()
            ];
        }

        return [
            'success' => false,
            'message' => 'Échec de connexion EBP: ' . $response->body()
        ];
    }

    /**
     * Test Google Sheets connection
     */
    private function testGoogleSheetsConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $apiKey = $config['api_key'] ?? null;
        $spreadsheetId = $config['spreadsheet_id'] ?? null;

        if (!$apiKey || !$spreadsheetId) {
            return ['success' => false, 'message' => 'Configuration incomplète (clé API et ID de feuille de calcul requis)'];
        }

        $response = Http::timeout(30)->get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}", [
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Connexion Google Sheets réussie',
                'data' => $response->json()
            ];
        }

        return [
            'success' => false,
            'message' => 'Échec de connexion Google Sheets: ' . $response->body()
        ];
    }

    /**
     * Test POS connection
     */
    private function testPosConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $baseUrl = $config['base_url'] ?? null;
        $token = $config['token'] ?? null;

        if (!$baseUrl || !$token) {
            return ['success' => false, 'message' => 'Configuration incomplète (URL de base et token requis)'];
        }

        $response = Http::withToken($token)
            ->timeout(30)
            ->get($baseUrl . '/api/status');

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Connexion POS réussie',
                'data' => $response->json()
            ];
        }

        return [
            'success' => false,
            'message' => 'Échec de connexion POS: ' . $response->body()
        ];
    }

    /**
     * Test custom connection
     */
    private function testCustomConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $testUrl = $config['test_url'] ?? null;

        if (!$testUrl) {
            return ['success' => false, 'message' => 'URL de test non configurée'];
        }

        $headers = $config['headers'] ?? [];
        $method = strtoupper($config['test_method'] ?? 'GET');

        $response = Http::withHeaders($headers)->timeout(30);

        switch ($method) {
            case 'POST':
                $response = $response->post($testUrl, $config['test_payload'] ?? []);
                break;
            case 'PUT':
                $response = $response->put($testUrl, $config['test_payload'] ?? []);
                break;
            default:
                $response = $response->get($testUrl);
        }

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Connexion personnalisée réussie',
                'data' => $response->json()
            ];
        }

        return [
            'success' => false,
            'message' => 'Échec de connexion personnalisée: ' . $response->body()
        ];
    }

    /**
     * Import connector configuration from JSON
     */
    public function importConfiguration(array $configData, int $companyId): ApiConnector
    {
        // Create the connector
        $connector = ApiConnector::create([
            'id' => Str::uuid(),
            'company_id' => $companyId,
            'name' => $configData['name'],
            'type' => $configData['type'],
            'description' => $configData['description'] ?? null,
            'sync_frequency' => $configData['sync_frequency'] ?? 1440,
            'configuration' => $configData['configuration'] ?? [],
            'mapping_rules' => $configData['mapping_rules'] ?? [],
            'status' => 'inactive',
            'is_active' => false,
            'created_by' => auth()->id()
        ]);

        // Import data mappings if provided
        if (isset($configData['data_mappings']) && is_array($configData['data_mappings'])) {
            foreach ($configData['data_mappings'] as $mappingData) {
                ApiDataMapping::create([
                    'id' => Str::uuid(),
                    'connector_id' => $connector->id,
                    'entity_type' => $mappingData['entity_type'],
                    'external_field' => $mappingData['external_field'],
                    'internal_field' => $mappingData['internal_field'],
                    'field_type' => $mappingData['field_type'] ?? 'string',
                    'transformation_rules' => $mappingData['transformation_rules'] ?? [],
                    'validation_rules' => $mappingData['validation_rules'] ?? [],
                    'is_required' => $mappingData['is_required'] ?? false,
                    'default_value' => $mappingData['default_value'] ?? null,
                    'is_active' => true
                ]);
            }
        }

        return $connector;
    }

    /**
     * Validate connector configuration
     */
    public function validateConfiguration(ApiConnector $connector): array
    {
        $errors = [];
        $config = $connector->configuration;

        switch ($connector->type) {
            case ApiConnector::TYPE_SAGE:
                if (empty($config['base_url'])) {
                    $errors[] = 'URL de base SAGE manquante';
                }
                if (empty($config['api_key'])) {
                    $errors[] = 'Clé API SAGE manquante';
                }
                break;

            case ApiConnector::TYPE_EBP:
                if (empty($config['base_url'])) {
                    $errors[] = 'URL de base EBP manquante';
                }
                if (empty($config['username']) || empty($config['password'])) {
                    $errors[] = 'Identifiants EBP manquants';
                }
                break;

            case ApiConnector::TYPE_GOOGLE_SHEETS:
                if (empty($config['api_key'])) {
                    $errors[] = 'Clé API Google Sheets manquante';
                }
                if (empty($config['spreadsheet_id'])) {
                    $errors[] = 'ID de la feuille de calcul manquant';
                }
                break;

            case ApiConnector::TYPE_POS:
                if (empty($config['base_url'])) {
                    $errors[] = 'URL POS manquante';
                }
                if (empty($config['token'])) {
                    $errors[] = 'Token POS manquant';
                }
                break;

            case ApiConnector::TYPE_CUSTOM:
                if (empty($config['base_url'])) {
                    $errors[] = 'URL de base manquante';
                }
                break;
        }

        return $errors;
    }

    /**
     * Get configuration template for a connector type
     */
    public function getConfigurationTemplate(string $connectorType): array
    {
        switch ($connectorType) {
            case ApiConnector::TYPE_SAGE:
                return [
                    'base_url' => '',
                    'api_key' => '',
                    'version' => 'v1',
                    'timeout' => 30,
                    'verify_ssl' => true
                ];

            case ApiConnector::TYPE_EBP:
                return [
                    'base_url' => '',
                    'username' => '',
                    'password' => '',
                    'database' => '',
                    'timeout' => 30
                ];

            case ApiConnector::TYPE_GOOGLE_SHEETS:
                return [
                    'api_key' => '',
                    'spreadsheet_id' => '',
                    'sheet_name' => 'Sheet1',
                    'has_headers' => true
                ];

            case ApiConnector::TYPE_POS:
                return [
                    'base_url' => '',
                    'token' => '',
                    'store_id' => '',
                    'format' => 'json'
                ];

            case ApiConnector::TYPE_EXCEL:
                return [
                    'file_path' => '',
                    'sheet_name' => 'Sheet1',
                    'has_headers' => true,
                    'start_row' => 1
                ];

            case ApiConnector::TYPE_CUSTOM:
                return [
                    'base_url' => '',
                    'headers' => [],
                    'auth_type' => 'none', // none, bearer, basic, api_key
                    'auth_credentials' => [],
                    'timeout' => 30
                ];

            default:
                return [];
        }
    }

    /**
     * Update connector status
     */
    public function updateStatus(ApiConnector $connector, string $status): void
    {
        $connector->update(['status' => $status]);
        
        Log::info('Connector status updated', [
            'connector_id' => $connector->id,
            'new_status' => $status
        ]);
    }

    /**
     * Get connector statistics
     */
    public function getConnectorStats(ApiConnector $connector): array
    {
        $syncLogs = $connector->syncLogs();

        return [
            'total_syncs' => $syncLogs->count(),
            'successful_syncs' => $syncLogs->successful()->count(),
            'failed_syncs' => $syncLogs->failed()->count(),
            'avg_execution_time' => $syncLogs->avg('execution_time'),
            'last_successful_sync' => $syncLogs->successful()->latest('completed_at')->first()?->completed_at,
            'last_failed_sync' => $syncLogs->failed()->latest('completed_at')->first()?->completed_at,
            'success_rate' => $this->calculateSuccessRate($connector)
        ];
    }

    /**
     * Calculate success rate
     */
    private function calculateSuccessRate(ApiConnector $connector): float
    {
        $totalSyncs = $connector->syncLogs()->count();
        if ($totalSyncs === 0) {
            return 0;
        }

        $successfulSyncs = $connector->syncLogs()->successful()->count();
        return round(($successfulSyncs / $totalSyncs) * 100, 2);
    }
}