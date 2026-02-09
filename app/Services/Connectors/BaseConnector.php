<?php

namespace App\Services\Connectors;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;

abstract class BaseConnector
{
    /**
     * Test connection to the external system
     */
    abstract public function testConnection(ApiConnector $connector): array;

    /**
     * Get default configuration template
     */
    abstract public static function getDefaultConfiguration(): array;

    /**
     * Get default data mappings
     */
    abstract public static function getDefaultMappings(): array;

    /**
     * Validate connector configuration
     */
    public function validateConfiguration(ApiConnector $connector): array
    {
        $errors = [];
        $config = $connector->configuration;
        $required = $this->getRequiredConfigFields();

        foreach ($required as $field) {
            if (empty($config[$field])) {
                $errors[] = "Champ requis manquant: {$field}";
            }
        }

        return $errors;
    }

    /**
     * Get required configuration fields
     */
    protected function getRequiredConfigFields(): array
    {
        return ['base_url'];
    }

    /**
     * Log sync activity
     */
    protected function logActivity(string $level, string $message, array $context = []): void
    {
        \Illuminate\Support\Facades\Log::log($level, $message, $context);
    }

    /**
     * Handle API rate limiting
     */
    protected function handleRateLimit(\Exception $e, int $retryCount = 0): bool
    {
        if (str_contains($e->getMessage(), 'rate limit') && $retryCount < 3) {
            $delay = pow(2, $retryCount) * 1000; // Exponential backoff
            usleep($delay * 1000); // Convert to microseconds
            return true;
        }
        
        return false;
    }

    /**
     * Sanitize data for logging
     */
    protected function sanitizeForLog(array $data): array
    {
        $sensitiveFields = ['password', 'api_key', 'token', 'secret', 'key'];
        
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), $sensitiveFields)) {
                $data[$key] = '***MASKED***';
            } elseif (is_array($value)) {
                $data[$key] = $this->sanitizeForLog($value);
            }
        }
        
        return $data;
    }

    /**
     * Format error message for user display
     */
    protected function formatErrorMessage(\Exception $e): string
    {
        $message = $e->getMessage();
        
        // Remove sensitive information from error messages
        $message = preg_replace('/api[_-]?key[^&\s]*/i', 'api_key=***', $message);
        $message = preg_replace('/token[^&\s]*/i', 'token=***', $message);
        $message = preg_replace('/password[^&\s]*/i', 'password=***', $message);
        
        return $message;
    }

    /**
     * Check if response indicates success
     */
    protected function isSuccessfulResponse($response): bool
    {
        if (method_exists($response, 'successful')) {
            return $response->successful();
        }
        
        if (method_exists($response, 'getStatusCode')) {
            return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
        }
        
        return true;
    }

    /**
     * Extract data from response
     */
    protected function extractResponseData($response, string $dataKey = 'data')
    {
        if (method_exists($response, 'json')) {
            return $response->json($dataKey, []);
        }
        
        if (method_exists($response, 'toArray')) {
            $data = $response->toArray();
            return $data[$dataKey] ?? $data;
        }
        
        return $response;
    }

    /**
     * Build headers for API requests
     */
    protected function buildHeaders(ApiConnector $connector): array
    {
        $config = $connector->configuration;
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'TPT-H-ERP/1.0'
        ];

        // Add authentication headers based on type
        $authType = $config['auth_type'] ?? 'api_key';
        
        switch ($authType) {
            case 'bearer':
                $headers['Authorization'] = 'Bearer ' . ($config['token'] ?? $config['api_key'] ?? '');
                break;
                
            case 'api_key':
                $headers['X-API-Key'] = $config['api_key'] ?? '';
                break;
                
            case 'basic':
                // Basic auth will be handled by HTTP client
                break;
        }

        // Add custom headers
        if (!empty($config['custom_headers'])) {
            $headers = array_merge($headers, $config['custom_headers']);
        }

        return $headers;
    }

    /**
     * Build query parameters
     */
    protected function buildQueryParams(array $params = [], ApiConnector $connector = null): array
    {
        $queryParams = $params;
        
        if ($connector) {
            $config = $connector->configuration;
            
            // Add common parameters
            if (!empty($config['default_params'])) {
                $queryParams = array_merge($queryParams, $config['default_params']);
            }
            
            // Add pagination if supported
            if (!empty($config['pagination'])) {
                $queryParams['limit'] = $config['pagination']['limit'] ?? 100;
            }
        }

        return $queryParams;
    }

    /**
     * Handle pagination for API responses
     */
    protected function handlePagination($response, callable $processPage, ApiConnector $connector): array
    {
        $allResults = [];
        $currentPage = 1;
        $totalProcessed = 0;
        $config = $connector->configuration;
        $maxPages = $config['max_pages'] ?? 10;

        do {
            $pageData = $this->extractResponseData($response);
            $results = $processPage($pageData);
            $allResults = array_merge($allResults, $results);
            $totalProcessed += count($results);

            // Check if there are more pages
            $hasMore = $this->hasMorePages($response, $config);
            $currentPage++;

            if ($hasMore && $currentPage <= $maxPages) {
                $nextPageUrl = $this->getNextPageUrl($response, $config);
                if ($nextPageUrl) {
                    $response = $this->fetchNextPage($nextPageUrl, $connector);
                } else {
                    break;
                }
            } else {
                break;
            }
        } while ($hasMore && $currentPage <= $maxPages);

        return [
            'data' => $allResults,
            'total_processed' => $totalProcessed,
            'pages_processed' => $currentPage - 1
        ];
    }

    /**
     * Check if response has more pages
     */
    protected function hasMorePages($response, array $config): bool
    {
        $responseData = $this->extractResponseData($response);
        
        // Check various pagination indicators
        if (isset($responseData['has_more'])) {
            return $responseData['has_more'];
        }
        
        if (isset($responseData['next_page_url'])) {
            return !empty($responseData['next_page_url']);
        }
        
        if (isset($responseData['pagination']['has_next'])) {
            return $responseData['pagination']['has_next'];
        }

        return false;
    }

    /**
     * Get URL for next page
     */
    protected function getNextPageUrl($response, array $config): ?string
    {
        $responseData = $this->extractResponseData($response);
        
        return $responseData['next_page_url'] ?? 
               $responseData['pagination']['next_url'] ?? 
               null;
    }

    /**
     * Fetch next page of data
     */
    protected function fetchNextPage(string $url, ApiConnector $connector)
    {
        $headers = $this->buildHeaders($connector);
        
        return \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->timeout($connector->configuration['timeout'] ?? 60)
            ->get($url);
    }

    /**
     * Retry logic for failed requests
     */
    protected function withRetry(callable $operation, int $maxRetries = 3): mixed
    {
        $attempt = 0;
        
        do {
            try {
                return $operation();
            } catch (\Exception $e) {
                $attempt++;
                
                if ($attempt >= $maxRetries) {
                    throw $e;
                }
                
                // Handle rate limiting
                if ($this->handleRateLimit($e, $attempt)) {
                    continue;
                }
                
                // For other errors, wait before retry
                sleep($attempt);
            }
        } while ($attempt < $maxRetries);
        
        throw new \Exception('Max retries exceeded');
    }

    /**
     * Transform data using connector's mappings
     */
    protected function transformData(array $data, ApiConnector $connector, string $entityType): array
    {
        $transformationService = app(\App\Services\DataTransformationService::class);
        return $transformationService->transform($data, $connector, $entityType);
    }

    /**
     * Validate transformed data
     */
    protected function validateTransformedData(array $data, ApiConnector $connector, string $entityType): array
    {
        $transformationService = app(\App\Services\DataTransformationService::class);
        return $transformationService->validateDataStructure($data, $connector, $entityType);
    }

    /**
     * Get connector-specific timeout
     */
    protected function getTimeout(ApiConnector $connector): int
    {
        return $connector->configuration['timeout'] ?? 60;
    }

    /**
     * Get connector-specific batch size
     */
    protected function getBatchSize(ApiConnector $connector): int
    {
        return $connector->configuration['batch_size'] ?? 100;
    }

    /**
     * Check if SSL verification is enabled
     */
    protected function shouldVerifySsl(ApiConnector $connector): bool
    {
        return $connector->configuration['verify_ssl'] ?? true;
    }
}