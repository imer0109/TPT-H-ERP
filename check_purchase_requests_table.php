<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Vérification de la table purchase_requests...\n";

// Vérifier si la table existe
if (Schema::hasTable('purchase_requests')) {
    echo "✅ Table purchase_requests existe\n";
    
    // Compter les enregistrements
    $count = DB::table('purchase_requests')->count();
    echo "📊 Nombre d'enregistrements: {$count}\n";
    
    // Afficher la structure de la table
    echo "\n📋 Structure de la table:\n";
    $columns = DB::select('DESCRIBE purchase_requests');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    // Tester une requête simple
    echo "\n🧪 Test de requête...\n";
    try {
        $result = DB::table('purchase_requests')
            ->select(DB::raw('COUNT(*) as total'))
            ->first();
        echo "✅ Requête réussie: {$result->total} enregistrements\n";
    } catch (Exception $e) {
        echo "❌ Erreur: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ Table purchase_requests manquante\n";
    
    // Vérifier toutes les tables
    echo "\n📋 Tables dans la base de données:\n";
    $tables = DB::select('SHOW TABLES');
    $tableNames = array_map(function($table) {
        return array_values((array)$table)[0];
    }, $tables);
    
    if (in_array('purchase_requests', $tableNames)) {
        echo "  ✅ purchase_requests trouvée dans SHOW TABLES\n";
    } else {
        echo "  ❌ purchase_requests absente de SHOW TABLES\n";
        echo "  Tables trouvées: " . implode(', ', array_slice($tableNames, 0, 10)) . "\n";
    }
}

echo "\n✅ Vérification terminée\n";