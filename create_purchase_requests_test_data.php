<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "📦 Création de données de test pour purchase_requests\n";
echo str_repeat("=", 50) . "\n";

// Vérifier que les tables nécessaires existent
$requiredTables = ['purchase_requests', 'purchase_request_items', 'companies', 'users', 'departments'];
$missingTables = [];

foreach ($requiredTables as $table) {
    if (!Schema::hasTable($table)) {
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "❌ Tables manquantes: " . implode(', ', $missingTables) . "\n";
    exit(1);
}

// Récupérer les données nécessaires
$companies = DB::table('companies')->pluck('id')->toArray();
$users = DB::table('users')->pluck('id')->toArray();
$departments = DB::table('departments')->pluck('id')->toArray();

if (empty($companies) || empty($users)) {
    echo "❌ Données de base manquantes (sociétés ou utilisateurs)\n";
    exit(1);
}

echo "📊 Données disponibles:\n";
echo "- Sociétés: " . count($companies) . "\n";
echo "- Utilisateurs: " . count($users) . "\n";
echo "- Départements: " . count($departments) . "\n";

// Créer des demandes d'achat de test
$testRequests = [
    [
        'code' => 'DA-001-2026',
        'company_id' => $companies[0],
        'department_id' => $departments[0] ?? null,
        'requested_by' => $users[0],
        'nature_achat' => 'Bien',
        'designation' => 'Ordinateurs portables pour le service informatique',
        'justification' => 'Remplacement du parc informatique obsolète',
        'date_demande' => '2026-02-01',
        'date_echeance_souhaitee' => '2026-02-28',
        'statut' => 'approuvee',
        'prix_estime_total' => 1500000,
        'notes' => 'Priorité haute - besoin urgent'
    ],
    [
        'code' => 'DA-002-2026',
        'company_id' => $companies[0],
        'department_id' => $departments[0] ?? null,
        'requested_by' => $users[1] ?? $users[0],
        'nature_achat' => 'Service',
        'designation' => 'Formation en développement web',
        'justification' => 'Montée en compétence de l\'équipe technique',
        'date_demande' => '2026-02-02',
        'date_echeance_souhaitee' => '2026-03-15',
        'statut' => 'approuvee',
        'prix_estime_total' => 850000,
        'notes' => 'Formation certifiante'
    ],
    [
        'code' => 'DA-003-2026',
        'company_id' => $companies[0],
        'department_id' => $departments[1] ?? null,
        'requested_by' => $users[2] ?? $users[0],
        'nature_achat' => 'Bien',
        'designation' => 'Fournitures de bureau',
        'justification' => 'Réapprovisionnement mensuel',
        'date_demande' => '2026-02-03',
        'date_echeance_souhaitee' => '2026-02-15',
        'statut' => 'en_attente',
        'prix_estime_total' => 250000,
        'notes' => 'Commande standard'
    ]
];

echo "\n📥 Insertion des données de test...\n";

$insertedCount = 0;
foreach ($testRequests as $requestData) {
    try {
        $id = DB::table('purchase_requests')->insertGetId(array_merge($requestData, [
            'created_at' => now(),
            'updated_at' => now()
        ]));
        
        echo "  ✅ {$requestData['code']} insérée (ID: {$id})\n";
        $insertedCount++;
        
        // Créer des items pour cette demande
        if ($requestData['code'] === 'DA-001-2026') {
            DB::table('purchase_request_items')->insert([
                [
                    'purchase_request_id' => $id,
                    'designation' => 'MacBook Pro 14"',
                    'description' => 'Ordinateur portable professionnel',
                    'quantite' => 5,
                    'unite' => 'unité',
                    'prix_unitaire_estime' => 300000,
                    'montant_total_estime' => 1500000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
            echo "    📦 1 item ajouté\n";
        }
        
    } catch (Exception $e) {
        echo "  ❌ Erreur pour {$requestData['code']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ {$insertedCount} demandes d'achat créées\n";

// Vérification finale
echo "\n🔍 Vérification des données:\n";
$totalRequests = DB::table('purchase_requests')->count();
$approvedRequests = DB::table('purchase_requests')->where('statut', 'approuvee')->count();
$februaryRequests = DB::table('purchase_requests')
    ->where('statut', 'approuvee')
    ->whereMonth('created_at', 2)
    ->whereYear('created_at', 2026)
    ->count();

echo "- Total des demandes: {$totalRequests}\n";
echo "- Demandes approuvées: {$approvedRequests}\n";
echo "- Demandes approuvées en février 2026: {$februaryRequests}\n";

// Test de la requête qui causait l'erreur
echo "\n🧪 Test de la requête problématique:\n";
try {
    $result = DB::table('purchase_requests')
        ->where('statut', 'approuvee')
        ->whereMonth('created_at', 2)
        ->whereYear('created_at', 2026)
        ->sum('prix_estime_total');
    
    echo "✅ Requête réussie! Montant total: " . number_format($result, 0, ',', ' ') . " XAF\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n🎉 Configuration terminée!\n";