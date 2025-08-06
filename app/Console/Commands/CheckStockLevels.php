<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StockAlertService;

class CheckStockLevels extends Command
{
    protected $signature = 'stock:check-levels';
    protected $description = 'Vérifie les niveaux de stock et envoie les alertes nécessaires';

    public function handle(StockAlertService $alertService)
    {
        $this->info('Vérification des niveaux de stock...');
        $alertService->checkStockLevels();
        $this->info('Vérification terminée.');
    }
}