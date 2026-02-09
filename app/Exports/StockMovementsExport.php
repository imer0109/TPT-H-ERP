<?php

namespace App\Exports;

use App\Models\StockMovement;

class StockMovementsExport
{
    /**
     * Méthode pour simuler l'export de mouvements de stock
     * 
     * @return array
     */
    public function collection()
    {
        // Cette méthode est un placeholder pour simuler l'export
        // Elle sera appelée par notre service Excel personnalisé
        return [];
    }
}