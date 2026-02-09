<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🏢 Configuration des données de base\n";
echo str_repeat("=", 40) . "\n";

// Créer une société de test si elle n'existe pas
$company = DB::table('companies')->where('raison_sociale', 'TPT-H ERP')->first();
if (!$company) {
    $companyId = DB::table('companies')->insertGetId([
        'raison_sociale' => 'TPT-H ERP',
        'type' => 'entreprise',
        'devise' => 'XAF',
        'pays' => 'CM',
        'ville' => 'Douala',
        'siege_social' => 'Douala, Cameroun',
        'email' => 'contact@tpth-erp.com',
        'telephone' => '+237 123456789',
        'active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Société TPT-H ERP créée (ID: {$companyId})\n";
} else {
    $companyId = $company->id;
    echo "ℹ️  Société TPT-H ERP existe déjà (ID: {$companyId})\n";
}

// Créer un utilisateur de test si nécessaire
$user = DB::table('users')->where('email', 'test@tpth-erp.com')->first();
if (!$user) {
    $userId = DB::table('users')->insertGetId([
        'nom' => 'Test',
        'prenom' => 'Utilisateur',
        'email' => 'test@tpth-erp.com',
        'password' => Hash::make('password'),
        'company_id' => $companyId,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Utilisateur test créé (ID: {$userId})\n";
} else {
    $userId = $user->id;
    echo "ℹ️  Utilisateur test existe déjà (ID: {$userId})\n";
}

// Créer un département de test
$department = DB::table('departments')->where('nom', 'Informatique')->first();
if (!$department) {
    $departmentId = DB::table('departments')->insertGetId([
        'nom' => 'Informatique',
        'description' => 'Service informatique',
        'company_id' => $companyId,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Département Informatique créé (ID: {$departmentId})\n";
} else {
    $departmentId = $department->id;
    echo "ℹ️  Département Informatique existe déjà (ID: {$departmentId})\n";
}

// Maintenant créer des demandes d'achat
echo "\n📦 Création des demandes d'achat...\n";

$testRequests = [
    [
        'code' => 'DA-001-2026',
        'company_id' => $companyId,
        'department_id' => $departmentId,
        'requested_by' => $userId,
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
        'company_id' => $companyId,
        'department_id' => $departmentId,
        'requested_by' => $userId,
        'nature_achat' => 'Service',
        'designation' => 'Formation en développement web',
        'justification' => 'Montée en compétence de l\'équipe technique',
        'date_demande' => '2026-02-02',
        'date_echeance_souhaitee' => '2026-03-15',
        'statut' => 'approuvee',
        'prix_estime_total' => 850000,
        'notes' => 'Formation certifiante'
    ]
];

$insertedCount = 0;
foreach ($testRequests as $requestData) {
    try {
        // Vérifier si la demande existe déjà
        $existing = DB::table('purchase_requests')
            ->where('code', $requestData['code'])
            ->first();
            
        if (!$existing) {
            $id = DB::table('purchase_requests')->insertGetId(array_merge($requestData, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
            
            echo "  ✅ {$requestData['code']} insérée (ID: {$id})\n";
            $insertedCount++;
        } else {
            echo "  ℹ️  {$requestData['code']} existe déjà (ID: {$existing->id})\n";
        }
        
    } catch (Exception $e) {
        echo "  ❌ Erreur pour {$requestData['code']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ {$insertedCount} nouvelles demandes d'achat créées\n";

// Test de la requête problématique
echo "\n🧪 Test final de la requête:\n";
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

echo "\n🎉 Configuration terminée avec succès!\n";