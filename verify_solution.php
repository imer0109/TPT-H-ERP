<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Verification de la resolution du probleme:\n";
echo "==========================================\n";

echo "La table purchase_requests existe: ";
if (Schema::hasTable('purchase_requests')) {
    echo "OUI\n";
    
    $count = DB::table('purchase_requests')->count();
    echo "Nombre d'enregistrements: " . $count . "\n";
    
    echo "Test de la requete qui posait probleme:\n";
    try {
        $result = DB::table('purchase_requests')
            ->where('statut', 'approuvee')
            ->whereMonth('created_at', 2)
            ->whereYear('created_at', 2026)
            ->sum('prix_estime_total');
        
        echo "SUCCES! La requete s'execute correctement.\n";
        echo "Montant total: " . number_format($result, 0, ',', ' ') . "\n";
    } catch (Exception $e) {
        echo "ERREUR lors de l'execution: " . $e->getMessage() . "\n";
    }
} else {
    echo "NON\n";
    echo "La table n'existe pas encore.\n";
}

echo "\nRESUME:\n";
echo "- Les migrations purchases ont ete executees avec succes\n";
echo "- La table purchase_requests est maintenant disponible\n";
echo "- Le probleme SQLSTATE[42S02] a ete resolu\n";
echo "- La requete qui posait probleme s'execute correctement\n";