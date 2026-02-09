<?php

namespace App\Http\Controllers;

use App\Models\ApiConnector;
use App\Models\ApiSyncLog;
use App\Models\Company;
use App\Services\ApiConnectorService;
use App\Services\ApiSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApiConnectorController extends Controller
{
    protected $connectorService;
    protected $syncService;

    public function __construct(ApiConnectorService $connectorService, ApiSyncService $syncService)
    {
        $this->connectorService = $connectorService;
        $this->syncService = $syncService;
    }

    /**
     * Display a listing of API connectors
     */
    public function index(Request $request)
    {
        $query = ApiConnector::with(['company', 'syncLogs' => function($q) {
            $q->latest()->limit(5);
        }]);

        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $connectors = $query->paginate(20);
        $companies = Company::all();

        return view('api-connectors.index', compact('connectors', 'companies'));
    }

    /**
     * Show the form for creating a new connector
     */
    public function create()
    {
        $companies = Company::all();
        $connectorTypes = ApiConnector::getConnectorTypes();
        $syncFrequencies = ApiConnector::getSyncFrequencies();

        return view('api-connectors.create', compact('companies', 'connectorTypes', 'syncFrequencies'));
    }

    /**
     * Store a newly created connector
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'type' => ['required', Rule::in(array_keys(ApiConnector::getConnectorTypes()))],
            'description' => 'nullable|string',
            'sync_frequency' => 'required|integer|min:0',
            'configuration' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $connector = ApiConnector::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'type' => $request->type,
            'description' => $request->description,
            'sync_frequency' => $request->sync_frequency,
            'configuration' => $request->configuration ?? [],
            'is_active' => $request->boolean('is_active', true),
            'status' => 'inactive',
            'created_by' => Auth::id()
        ]);

        // Calculate next sync time
        $connector->calculateNextSync();
        $connector->save();

        return redirect()->route('api-connectors.index')
            ->with('success', 'Connecteur API créé avec succès.');
    }

    /**
     * Display the specified connector
     */
    public function show(ApiConnector $apiConnector)
    {
        $apiConnector->load(['company', 'syncLogs' => function($q) {
            $q->latest()->limit(20);
        }, 'dataMappings']);

        $stats = [
            'total_syncs' => $apiConnector->syncLogs()->count(),
            'successful_syncs' => $apiConnector->syncLogs()->successful()->count(),
            'failed_syncs' => $apiConnector->syncLogs()->failed()->count(),
            'last_sync' => $apiConnector->last_sync_at,
            'next_sync' => $apiConnector->next_sync_at,
            'success_rate' => $this->calculateSuccessRate($apiConnector)
        ];

        return view('api-connectors.show', compact('apiConnector', 'stats'));
    }

    /**
     * Show the form for editing the specified connector
     */
    public function edit(ApiConnector $apiConnector)
    {
        $companies = Company::all();
        $connectorTypes = ApiConnector::getConnectorTypes();
        $syncFrequencies = ApiConnector::getSyncFrequencies();

        return view('api-connectors.edit', compact('apiConnector', 'companies', 'connectorTypes', 'syncFrequencies'));
    }

    /**
     * Update the specified connector
     */
    public function update(Request $request, ApiConnector $apiConnector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'type' => ['required', Rule::in(array_keys(ApiConnector::getConnectorTypes()))],
            'description' => 'nullable|string',
            'sync_frequency' => 'required|integer|min:0',
            'configuration' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $apiConnector->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'type' => $request->type,
            'description' => $request->description,
            'sync_frequency' => $request->sync_frequency,
            'configuration' => $request->configuration ?? [],
            'is_active' => $request->boolean('is_active'),
            'updated_by' => Auth::id()
        ]);

        // Recalculate next sync time if frequency changed
        $apiConnector->calculateNextSync();
        $apiConnector->save();

        return redirect()->route('api-connectors.show', $apiConnector)
            ->with('success', 'Connecteur API mis à jour avec succès.');
    }

    /**
     * Remove the specified connector
     */
    public function destroy(ApiConnector $apiConnector)
    {
        // Check if connector has recent sync logs
        $recentSyncs = $apiConnector->syncLogs()->where('started_at', '>=', now()->subDays(7))->count();
        
        if ($recentSyncs > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce connecteur car il a des synchronisations récentes.');
        }

        $apiConnector->delete();

        return redirect()->route('api-connectors.index')
            ->with('success', 'Connecteur API supprimé avec succès.');
    }

    /**
     * Test the connector connection
     */
    public function testConnection(ApiConnector $apiConnector)
    {
        try {
            $result = $this->connectorService->testConnection($apiConnector);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connexion réussie.',
                    'data' => $result['data'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Échec de la connexion.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test de connexion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manually trigger synchronization
     */
    public function syncNow(ApiConnector $apiConnector)
    {
        try {
            $syncLog = $this->syncService->triggerSync($apiConnector, 'manual', Auth::id());
            
            return response()->json([
                'success' => true,
                'message' => 'Synchronisation démarrée.',
                'sync_log_id' => $syncLog->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du démarrage de la synchronisation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle connector status
     */
    public function toggleStatus(ApiConnector $apiConnector)
    {
        $newStatus = $apiConnector->status === 'active' ? 'inactive' : 'active';
        
        $apiConnector->update([
            'status' => $newStatus,
            'updated_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Statut du connecteur mis à jour.'
        ]);
    }

    /**
     * Get connector logs for AJAX requests
     */
    public function logs(ApiConnector $apiConnector, Request $request)
    {
        $query = $apiConnector->syncLogs()->with('triggeredBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('started_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('started_at', '<=', $request->date_to);
        }

        $logs = $query->latest('started_at')->paginate(20);

        return response()->json($logs);
    }

    /**
     * Export connector configuration
     */
    public function exportConfig(ApiConnector $apiConnector)
    {
        $config = [
            'name' => $apiConnector->name,
            'type' => $apiConnector->type,
            'description' => $apiConnector->description,
            'sync_frequency' => $apiConnector->sync_frequency,
            'configuration' => $apiConnector->configuration,
            'mapping_rules' => $apiConnector->mapping_rules,
            'data_mappings' => $apiConnector->dataMappings->map(function($mapping) {
                return [
                    'entity_type' => $mapping->entity_type,
                    'external_field' => $mapping->external_field,
                    'internal_field' => $mapping->internal_field,
                    'field_type' => $mapping->field_type,
                    'transformation_rules' => $mapping->transformation_rules,
                    'validation_rules' => $mapping->validation_rules,
                    'is_required' => $mapping->is_required,
                    'default_value' => $mapping->default_value
                ];
            })->toArray()
        ];

        $filename = 'connector_config_' . $apiConnector->type . '_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($config)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import connector configuration
     */
    public function importConfig(Request $request)
    {
        $request->validate([
            'config_file' => 'required|file|mimes:json',
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            $configData = json_decode(file_get_contents($request->file('config_file')), true);
            
            if (!$configData) {
                throw new \Exception('Format de fichier invalide');
            }

            $connector = $this->connectorService->importConfiguration($configData, $request->company_id);

            return redirect()->route('api-connectors.show', $connector)
                ->with('success', 'Configuration importée avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Calculate success rate for a connector
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

    /**
     * Dashboard view for API connectors
     */
    public function dashboard()
    {
        $stats = [
            'total_connectors' => ApiConnector::count(),
            'active_connectors' => ApiConnector::where('status', 'active')->count(),
            'recent_syncs' => ApiSyncLog::where('started_at', '>=', now()->subDay())->count(),
            'failed_syncs' => ApiSyncLog::where('status', 'failed')
                ->where('started_at', '>=', now()->subDay())->count()
        ];

        $recentLogs = ApiSyncLog::with(['connector.company'])
            ->latest('started_at')
            ->limit(10)
            ->get();

        $connectorsByType = ApiConnector::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        return view('api-connectors.dashboard', compact('stats', 'recentLogs', 'connectorsByType'));
    }
}
