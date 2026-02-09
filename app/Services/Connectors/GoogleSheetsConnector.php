<?php

namespace App\Services\Connectors;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;
use App\Services\DataTransformationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsConnector extends BaseConnector
{
    protected $dataTransformationService;

    public function __construct(DataTransformationService $dataTransformationService)
    {
        $this->dataTransformationService = $dataTransformationService;
    }

    /**
     * Test Google Sheets connection
     */
    public function testConnection(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $apiKey = $config['api_key'] ?? null;
        $spreadsheetId = $config['spreadsheet_id'] ?? null;

        if (!$apiKey || !$spreadsheetId) {
            return [
                'success' => false,
                'message' => 'Configuration incomplète: clé API et ID de feuille de calcul requis'
            ];
        }

        try {
            $response = Http::timeout(30)->get(
                "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}", 
                ['key' => $apiKey]
            );

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Connexion Google Sheets réussie',
                    'data' => [
                        'title' => $data['properties']['title'] ?? 'Sans titre',
                        'sheets_count' => count($data['sheets'] ?? []),
                        'sheets' => collect($data['sheets'] ?? [])->pluck('properties.title')->toArray()
                    ]
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur Google Sheets: ' . $response->status() . ' - ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion Google Sheets: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sync data from Google Sheets
     */
    public function syncData(ApiConnector $connector, ApiSyncLog $syncLog): array
    {
        $config = $connector->configuration;
        $apiKey = $config['api_key'];
        $spreadsheetId = $config['spreadsheet_id'];
        $sheetName = $config['sheet_name'] ?? 'Sheet1';
        $hasHeaders = $config['has_headers'] ?? true;
        
        $processed = 0;
        $successful = 0;
        $failed = 0;
        $errors = [];

        try {
            // Get range of data
            $range = $config['range'] ?? $sheetName;
            
            $response = Http::timeout(120)->get(
                "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}",
                [
                    'key' => $apiKey,
                    'majorDimension' => 'ROWS',
                    'valueRenderOption' => 'FORMATTED_VALUE',
                    'dateTimeRenderOption' => 'FORMATTED_STRING'
                ]
            );

            if (!$response->successful()) {
                throw new \Exception('Google Sheets API Error: ' . $response->status() . ' - ' . $response->body());
            }

            $responseData = $response->json();
            $values = $responseData['values'] ?? [];
            
            if (empty($values)) {
                return [
                    'status' => ApiSyncLog::STATUS_SUCCESS,
                    'records_processed' => 0,
                    'records_successful' => 0,
                    'records_failed' => 0,
                    'response_data' => ['message' => 'Aucune donnée trouvée']
                ];
            }

            // Extract headers if present
            $headers = null;
            if ($hasHeaders) {
                $headers = array_shift($values);
            }

            $processed = count($values);
            $entityType = $config['entity_type'] ?? $this->detectEntityType($headers, $values[0] ?? []);

            Log::info('Google Sheets sync started', [
                'connector_id' => $connector->id,
                'rows_count' => $processed,
                'entity_type' => $entityType
            ]);

            foreach ($values as $index => $row) {
                try {
                    // Convert row to associative array if headers are present
                    $rowData = $headers ? $this->combineHeadersWithRow($headers, $row) : $row;
                    
                    // Skip empty rows
                    if ($this->isEmptyRow($rowData)) {
                        continue;
                    }

                    $transformedData = $this->transformRowData($rowData, $connector, $entityType);
                    $this->saveRowData($transformedData, $connector, $entityType);
                    $successful++;

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'row_index' => $index + 1,
                        'row_data' => $rowData ?? $row,
                        'error' => $e->getMessage()
                    ];
                    
                    Log::warning('Google Sheets row sync failed', [
                        'connector_id' => $connector->id,
                        'row_index' => $index + 1,
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
                'response_data' => [
                    'total_rows' => $processed,
                    'sheet_name' => $sheetName,
                    'has_headers' => $hasHeaders
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Google Sheets sync failed', [
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
     * Combine headers with row data
     */
    protected function combineHeadersWithRow(array $headers, array $row): array
    {
        $data = [];
        $headerCount = count($headers);
        
        for ($i = 0; $i < $headerCount; $i++) {
            $header = trim($headers[$i]);
            $value = $row[$i] ?? '';
            
            if (!empty($header)) {
                $data[$header] = $value;
            }
        }
        
        return $data;
    }

    /**
     * Check if row is empty
     */
    protected function isEmptyRow($rowData): bool
    {
        if (is_array($rowData)) {
            return empty(array_filter($rowData, function($value) {
                return !empty(trim($value));
            }));
        }
        
        return empty($rowData);
    }

    /**
     * Detect entity type from headers and data
     */
    protected function detectEntityType(?array $headers, array $sampleRow): string
    {
        $headerStr = implode(' ', $headers ?? []);
        $rowStr = implode(' ', $sampleRow);
        $combinedStr = strtolower($headerStr . ' ' . $rowStr);

        // Look for keywords to determine entity type
        if (preg_match('/\b(client|customer|nom|name|email)\b/', $combinedStr)) {
            return 'customer';
        }
        
        if (preg_match('/\b(fournisseur|supplier|vendor)\b/', $combinedStr)) {
            return 'supplier';
        }
        
        if (preg_match('/\b(produit|product|article|reference|prix|price)\b/', $combinedStr)) {
            return 'product';
        }
        
        if (preg_match('/\b(compte|account|journal|debit|credit)\b/', $combinedStr)) {
            return 'accounting';
        }
        
        if (preg_match('/\b(vente|sale|montant|amount)\b/', $combinedStr)) {
            return 'sale';
        }
        
        if (preg_match('/\b(employe|employee|salaire|salary)\b/', $combinedStr)) {
            return 'employee';
        }

        return 'generic';
    }

    /**
     * Transform row data using connector mappings
     */
    protected function transformRowData(array $rowData, ApiConnector $connector, string $entityType): array
    {
        return $this->dataTransformationService->transform($rowData, $connector, $entityType);
    }

    /**
     * Save row data to appropriate ERP entity
     */
    protected function saveRowData(array $data, ApiConnector $connector, string $entityType): void
    {
        switch ($entityType) {
            case 'customer':
                $this->saveCustomer($data, $connector);
                break;
            case 'supplier':
                $this->saveSupplier($data, $connector);
                break;
            case 'product':
                $this->saveProduct($data, $connector);
                break;
            case 'accounting':
                $this->saveAccountingEntry($data, $connector);
                break;
            case 'sale':
                $this->saveSale($data, $connector);
                break;
            case 'employee':
                $this->saveEmployee($data, $connector);
                break;
            default:
                Log::info('Generic data saved from Google Sheets', [
                    'connector_id' => $connector->id,
                    'entity_type' => $entityType,
                    'data' => $data
                ]);
        }
    }

    /**
     * Save customer data
     */
    protected function saveCustomer(array $data, ApiConnector $connector): void
    {
        \App\Models\Client::updateOrCreate([
            'company_id' => $connector->company_id,
            'email' => $data['email'] ?? null
        ], [
            'nom' => $data['name'] ?? $data['nom'] ?? '',
            'prenom' => $data['first_name'] ?? $data['prenom'] ?? '',
            'telephone' => $data['phone'] ?? $data['telephone'] ?? '',
            'adresse' => $data['address'] ?? $data['adresse'] ?? '',
            'code_postal' => $data['postal_code'] ?? $data['code_postal'] ?? '',
            'ville' => $data['city'] ?? $data['ville'] ?? '',
            'pays' => $data['country'] ?? $data['pays'] ?? 'France',
            'sync_source' => 'google_sheets',
            'external_id' => $data['external_id'] ?? null
        ]);
    }

    /**
     * Save supplier data
     */
    protected function saveSupplier(array $data, ApiConnector $connector): void
    {
        \App\Models\Fournisseur::updateOrCreate([
            'company_id' => $connector->company_id,
            'email' => $data['email'] ?? null
        ], [
            'name' => $data['name'] ?? $data['nom'] ?? '',
            'phone' => $data['phone'] ?? $data['telephone'] ?? '',
            'address' => $data['address'] ?? $data['adresse'] ?? '',
            'city' => $data['city'] ?? $data['ville'] ?? '',
            'country' => $data['country'] ?? $data['pays'] ?? 'France',
            'sync_source' => 'google_sheets',
            'external_id' => $data['external_id'] ?? null
        ]);
    }

    /**
     * Save product data
     */
    protected function saveProduct(array $data, ApiConnector $connector): void
    {
        \App\Models\Product::updateOrCreate([
            'company_id' => $connector->company_id,
            'reference' => $data['reference'] ?? $data['sku'] ?? ''
        ], [
            'name' => $data['name'] ?? $data['nom'] ?? '',
            'description' => $data['description'] ?? '',
            'price' => $data['price'] ?? $data['prix'] ?? 0,
            'cost_price' => $data['cost_price'] ?? $data['prix_achat'] ?? 0,
            'stock_quantity' => $data['stock'] ?? $data['quantite'] ?? 0,
            'unit' => $data['unit'] ?? $data['unite'] ?? 'piece',
            'sync_source' => 'google_sheets',
            'external_id' => $data['external_id'] ?? null
        ]);
    }

    /**
     * Save accounting entry
     */
    protected function saveAccountingEntry(array $data, ApiConnector $connector): void
    {
        // Similar to SAGE connector implementation
        $company = $connector->company;
        $journal = $this->getOrCreateJournal($data['journal_code'], $company);
        $account = $this->getOrCreateAccount($data['account_code'], $company);

        \App\Models\AccountingEntry::create([
            'company_id' => $company->id,
            'journal_id' => $journal->id,
            'account_id' => $account->id,
            'date' => $data['date'],
            'piece_number' => $data['piece_number'],
            'description' => $data['description'],
            'debit' => $data['debit'] ?? 0,
            'credit' => $data['credit'] ?? 0,
            'status' => 'validée',
            'sync_source' => 'google_sheets'
        ]);
    }

    /**
     * Save sale data
     */
    protected function saveSale(array $data, ApiConnector $connector): void
    {
        // Implementation depends on sale model structure
        Log::info('Sale data from Google Sheets', [
            'connector_id' => $connector->id,
            'data' => $data
        ]);
    }

    /**
     * Save employee data
     */
    protected function saveEmployee(array $data, ApiConnector $connector): void
    {
        \App\Models\Employee::updateOrCreate([
            'company_id' => $connector->company_id,
            'email' => $data['email'] ?? null
        ], [
            'nom' => $data['last_name'] ?? $data['nom'] ?? '',
            'prenom' => $data['first_name'] ?? $data['prenom'] ?? '',
            'telephone' => $data['phone'] ?? $data['telephone'] ?? '',
            'adresse' => $data['address'] ?? $data['adresse'] ?? '',
            'poste' => $data['position'] ?? $data['poste'] ?? '',
            'salaire' => $data['salary'] ?? $data['salaire'] ?? 0,
            'sync_source' => 'google_sheets',
            'external_id' => $data['external_id'] ?? null
        ]);
    }

    /**
     * Export data to Google Sheets
     */
    public function exportToSheets(ApiConnector $connector, array $data): array
    {
        $config = $connector->configuration;
        $apiKey = $config['api_key'];
        $spreadsheetId = $config['spreadsheet_id'];
        $sheetName = $config['export_sheet_name'] ?? $config['sheet_name'] ?? 'Export';

        try {
            // Prepare data for sheets format
            $values = [];
            if (!empty($data)) {
                // Add headers
                $headers = array_keys($data[0]);
                $values[] = $headers;
                
                // Add data rows
                foreach ($data as $row) {
                    $values[] = array_values($row);
                }
            }

            $response = Http::timeout(60)->put(
                "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$sheetName}",
                [
                    'range' => $sheetName,
                    'majorDimension' => 'ROWS',
                    'values' => $values
                ],
                [
                    'key' => $apiKey
                ]
            );

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Export vers Google Sheets réussi',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur export Google Sheets: ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur export Google Sheets: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get or create journal (helper method)
     */
    protected function getOrCreateJournal(string $journalCode, $company): \App\Models\AccountingJournal
    {
        return \App\Models\AccountingJournal::firstOrCreate([
            'company_id' => $company->id,
            'code' => $journalCode
        ], [
            'name' => 'Journal ' . $journalCode,
            'type' => 'opérations_diverses',
            'is_active' => true
        ]);
    }

    /**
     * Get or create account (helper method)
     */
    protected function getOrCreateAccount(string $accountCode, $company): \App\Models\ChartOfAccount
    {
        return \App\Models\ChartOfAccount::firstOrCreate([
            'company_id' => $company->id,
            'code' => $accountCode
        ], [
            'name' => 'Compte ' . $accountCode,
            'type' => 'actif',
            'level' => 'compte',
            'is_active' => true
        ]);
    }

    /**
     * Get default configuration template
     */
    public static function getDefaultConfiguration(): array
    {
        return [
            'api_key' => '',
            'spreadsheet_id' => '',
            'sheet_name' => 'Sheet1',
            'has_headers' => true,
            'range' => '',
            'entity_type' => 'generic',
            'export_sheet_name' => 'Export',
            'timeout' => 60
        ];
    }

    /**
     * Get default data mappings
     */
    public static function getDefaultMappings(): array
    {
        return [
            [
                'entity_type' => 'customer',
                'external_field' => 'Nom',
                'internal_field' => 'name',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'customer',
                'external_field' => 'Email',
                'internal_field' => 'email',
                'field_type' => 'email',
                'is_required' => false
            ],
            [
                'entity_type' => 'customer',
                'external_field' => 'Téléphone',
                'internal_field' => 'phone',
                'field_type' => 'phone',
                'is_required' => false
            ],
            [
                'entity_type' => 'product',
                'external_field' => 'Référence',
                'internal_field' => 'reference',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'product',
                'external_field' => 'Nom',
                'internal_field' => 'name',
                'field_type' => 'string',
                'is_required' => true
            ],
            [
                'entity_type' => 'product',
                'external_field' => 'Prix',
                'internal_field' => 'price',
                'field_type' => 'currency',
                'is_required' => false
            ]
        ];
    }

    /**
     * Get required configuration fields
     */
    protected function getRequiredConfigFields(): array
    {
        return ['api_key', 'spreadsheet_id'];
    }
}