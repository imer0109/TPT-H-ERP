<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Analyse des structures de tables\n";
echo str_repeat("=", 40) . "\n";

// Vérifier la structure de companies
echo "🏢 Table companies:\n";
$companyColumns = DB::select('DESCRIBE companies');
foreach ($companyColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\n";

// Vérifier la structure de users
echo "👤 Table users:\n";
$userColumns = DB::select('DESCRIBE users');
foreach ($userColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\n";

// Vérifier la structure de departments
echo "🏢 Table departments:\n";
$departmentColumns = DB::select('DESCRIBE departments');
foreach ($departmentColumns as $column) {
    echo "- {$column->Field} ({$column->Type})\n";
}

echo "\n";

// Compter les enregistrements existants
echo "📊 Données existantes:\n";
echo "- Companies: " . DB::table('companies')->count() . "\n";
echo "- Users: " . DB::table('users')->count() . "\n";
echo "- Departments: " . DB::table('departments')->count() . "\n";
echo "- Purchase Requests: " . DB::table('purchase_requests')->count() . "\n";

// Test de la requête problématique avec des données existantes
echo "\n🧪 Test de la requête problématique:\n";
try {
    $result = DB::table('purchase_requests')
        ->where('statut', 'approuvee')
        ->whereMonth('created_at', 2)
        ->whereYear('created_at', 2026)
        ->sum('prix_estime_total');
    
    $count = DB::table('purchase_requests')
        ->where('statut', 'approuvee')
        ->whereMonth('created_at', 2)
        ->whereYear('created_at', 2026)
        ->count();
    
    echo "✅ Requête réussie!\n";
    echo "  - Nombre de demandes: {$count}\n";
    echo "  - Montant total: " . number_format($result, 0, ',', ' ') . " XAF\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}