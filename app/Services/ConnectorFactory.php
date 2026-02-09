<?php

namespace App\Services;

use App\Models\ApiConnector;
use App\Services\Connectors\SageConnector;
use App\Services\Connectors\GoogleSheetsConnector;
use App\Services\Connectors\PosConnector;
use App\Services\Connectors\BaseConnector;

class ConnectorFactory
{
    /**
     * Create connector instance based on type
     */
    public static function create(string $connectorType): BaseConnector
    {
        switch ($connectorType) {
            case ApiConnector::TYPE_SAGE:
                return app(SageConnector::class);
                
            case ApiConnector::TYPE_GOOGLE_SHEETS:
                return app(GoogleSheetsConnector::class);
                
            case ApiConnector::TYPE_POS:
                return app(PosConnector::class);
                
            case ApiConnector::TYPE_EBP:
                // EBP connector would be implemented similarly
                throw new \Exception('EBP connector not yet implemented');
                
            case ApiConnector::TYPE_EXCEL:
                // Excel connector would be implemented similarly  
                throw new \Exception('Excel connector not yet implemented');
                
            case ApiConnector::TYPE_CUSTOM:
                // Custom connector with configurable endpoints
                throw new \Exception('Custom connector not yet implemented');
                
            default:
                throw new \Exception("Connector type '{$connectorType}' not supported");
        }
    }

    /**
     * Get available connector types with their default configurations
     */
    public static function getAvailableConnectors(): array
    {
        return [
            ApiConnector::TYPE_SAGE => [
                'name' => 'SAGE (Comptabilité/Paie)',
                'description' => 'Connecteur pour les logiciels SAGE',
                'config_template' => SageConnector::getDefaultConfiguration(),
                'default_mappings' => SageConnector::getDefaultMappings(),
                'supported_entities' => ['accounting', 'customer', 'supplier']
            ],
            ApiConnector::TYPE_GOOGLE_SHEETS => [
                'name' => 'Google Sheets',
                'description' => 'Connecteur pour Google Sheets',
                'config_template' => GoogleSheetsConnector::getDefaultConfiguration(),
                'default_mappings' => GoogleSheetsConnector::getDefaultMappings(),
                'supported_entities' => ['customer', 'supplier', 'product', 'accounting', 'employee']
            ],
            ApiConnector::TYPE_POS => [
                'name' => 'Point de Vente (POS)',
                'description' => 'Connecteur pour terminaux de point de vente',
                'config_template' => PosConnector::getDefaultConfiguration(),
                'default_mappings' => PosConnector::getDefaultMappings(),
                'supported_entities' => ['sale', 'product', 'customer']
            ]
        ];
    }

    /**
     * Test connection for a given connector
     */
    public static function testConnection(ApiConnector $connector): array
    {
        try {
            $connectorInstance = self::create($connector->type);
            return $connectorInstance->testConnection($connector);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du test de connexion: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate connector configuration
     */
    public static function validateConfiguration(ApiConnector $connector): array
    {
        try {
            $connectorInstance = self::create($connector->type);
            return $connectorInstance->validateConfiguration($connector);
        } catch (\Exception $e) {
            return ['Erreur lors de la validation: ' . $e->getMessage()];
        }
    }
}