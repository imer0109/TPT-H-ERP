<?php

namespace App\Console\Commands;

use App\Models\ApiConnector;
use App\Services\ApiSyncService;
use Illuminate\Console\Command;

class SyncApiConnectors extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'api:sync {--connector= : Specific connector ID to sync}';

    /**
     * The console command description.
     */
    protected $description = 'Synchronize data from API connectors';

    protected $syncService;

    public function __construct(ApiSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connectorId = $this->option('connector');

        if ($connectorId) {
            $this->syncSpecificConnector($connectorId);
        } else {
            $this->syncAllReadyConnectors();
        }

        return 0;
    }

    /**
     * Sync a specific connector
     */
    protected function syncSpecificConnector(string $connectorId): void
    {
        $connector = ApiConnector::find($connectorId);

        if (!$connector) {
            $this->error("Connector with ID {$connectorId} not found.");
            return;
        }

        $this->info("Syncing connector: {$connector->name}");

        try {
            $syncLog = $this->syncService->triggerSync($connector, 'scheduled');
            
            $this->info("Sync completed with status: {$syncLog->status}");
            
            if ($syncLog->hasErrors()) {
                $this->warn("Sync completed with errors. Check logs for details.");
            }

        } catch (\Exception $e) {
            $this->error("Sync failed: " . $e->getMessage());
        }
    }

    /**
     * Sync all connectors ready for synchronization
     */
    protected function syncAllReadyConnectors(): void
    {
        $connectors = $this->syncService->getConnectorsReadyForSync();

        if ($connectors->isEmpty()) {
            $this->info('No connectors ready for synchronization.');
            return;
        }

        $this->info("Found {$connectors->count()} connectors ready for sync.");

        foreach ($connectors as $connector) {
            $this->info("Syncing: {$connector->name} ({$connector->type})");

            try {
                $syncLog = $this->syncService->triggerSync($connector, 'scheduled');
                
                $statusColor = $syncLog->status === 'success' ? 'info' : 
                             ($syncLog->status === 'partial' ? 'comment' : 'error');
                
                $this->line("  Status: <{$statusColor}>{$syncLog->status}</{$statusColor}>");
                
                if ($syncLog->records_processed > 0) {
                    $this->line("  Processed: {$syncLog->records_processed} records");
                    $this->line("  Successful: {$syncLog->records_successful}");
                    
                    if ($syncLog->records_failed > 0) {
                        $this->line("  Failed: <error>{$syncLog->records_failed}</error>");
                    }
                }

            } catch (\Exception $e) {
                $this->error("  Error: " . $e->getMessage());
            }

            $this->newLine();
        }

        $this->info('Synchronization completed.');
    }
}