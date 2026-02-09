<?php

namespace App\Imports;

use App\Models\StockMovement;
use Illuminate\Support\Collection;

class StockMovementsImport
{
    /**
     * Méthode pour simuler l'import de mouvements de stock
     * 
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        // Cette méthode est un placeholder pour simuler l'import
        // Elle sera appelée par notre service Excel personnalisé
        return;
    }
}