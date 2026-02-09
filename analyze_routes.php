<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Analyse des routes de l'application\n";
echo str_repeat("=", 50) . "\n";

// Obtenir toutes les routes
$routeCollection = Route::getRoutes();

// Filtrer les routes importantes
$importantRoutes = [
    'dashboard',
    'services',
    'companies',
    'employees',
    'users',
    'products',
    'categories',
    'suppliers',
    'clients',
    'inventory',
    'stock',
    'accounting',
    'reports'
];

echo "📊 Routes importantes trouvées :\n";

foreach ($routeCollection as $route) {
    $uri = $route->uri();
    $name = $route->getName();
    
    foreach ($importantRoutes as $check) {
        if (stripos($uri, $check) !== false || ($name && stripos($name, $check) !== false)) {
            $methods = implode(',', $route->methods());
            echo "  🛣️  {$methods} {$uri}";
            if ($name) {
                echo " ({$name})";
            }
            echo "\n";
            break;
        }
    }
}

echo "\n📋 Total des routes: " . count($routeCollection) . "\n";

// Vérifier les erreurs potentielles
echo "\n🔍 Vérification des erreurs potentielles...\n";

try {
    // Vérifier la base de données
    DB::connection()->getPdo();
    echo "✅ Connexion à la base de données: OK\n";
} catch (\Exception $e) {
    echo "❌ Connexion à la base de données: ERREUR - " . $e->getMessage() . "\n";
}

// Vérifier les tables critiques
$criticalTables = ['users', 'roles', 'permissions', 'companies', 'services'];
foreach ($criticalTables as $table) {
    try {
        $exists = DB::getSchemaBuilder()->hasTable($table);
        echo ($exists ? "✅" : "❌") . " Table {$table}: " . ($exists ? "OK" : "MANQUANTE") . "\n";
    } catch (\Exception $e) {
        echo "❌ Table {$table}: ERREUR - " . $e->getMessage() . "\n";
    }
}

echo "\n💡 Pour tester les pages, assurez-vous que votre serveur est lancé avec 'php artisan serve'\n";
echo "💡 Visitez http://localhost:8000 et essayez les différentes routes\n";
echo "💡 Si vous voyez des pages blanches, cela pourrait être dû à des erreurs PHP non capturées\n";

echo "\n✅ Analyse terminée\n";