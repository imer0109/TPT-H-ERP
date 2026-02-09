<?php
/**
 * Test du dashboard achats
 */

require_once 'vendor/autoload.php';

// Initialisation de l'application Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\PurchaseDashboardController;
use Illuminate\Http\Request;

echo "=== TEST DU DASHBOARD ACHATS ===\n\n";

try {
    $controller = new PurchaseDashboardController();
    $request = new Request();
    
    $response = $controller->index($request);
    
    echo "✅ Contrôleur exécuté avec succès\n";
    echo "Type de réponse : " . get_class($response) . "\n";
    
    if ($response instanceof \Illuminate\View\View) {
        echo "✅ Vue Blade rendue correctement\n";
        echo "Nom de la vue : " . $response->getName() . "\n";
        echo "Données disponibles : " . implode(', ', array_keys($response->getData())) . "\n";
    } else {
        echo "❌ La réponse n'est pas une vue Blade\n";
        echo "Type de réponse : " . gettype($response) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Ligne : " . $e->getLine() . "\n";
    echo "Fichier : " . $e->getFile() . "\n";
}

echo "\n=== TEST TERMINÉ ===\n";