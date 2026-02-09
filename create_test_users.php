<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Liste des utilisateurs à créer
$users = [
    [
        'email' => 'admin@tpt-h.com',
        'nom' => 'Admin',
        'prenom' => 'Super',
        'password' => 'password'
    ],
    [
        'email' => 'manager@tpt-h.com',
        'nom' => 'Manager',
        'prenom' => 'Principal',
        'password' => 'password'
    ],
    [
        'email' => 'supervisor@tpt-h.com',
        'nom' => 'Superviseur',
        'prenom' => 'Senior',
        'password' => 'password'
    ],
    [
        'email' => 'agent@tpt-h.com',
        'nom' => 'Agent',
        'prenom' => 'Opérationnel',
        'password' => 'password'
    ],
    [
        'email' => 'viewer@tpt-h.com',
        'nom' => 'Consultant',
        'prenom' => 'Viewer',
        'password' => 'password'
    ],
    [
        'email' => 'hr@tpt-h.com',
        'nom' => 'RH',
        'prenom' => 'Manager',
        'password' => 'password'
    ],
    [
        'email' => 'accounting@tpt-h.com',
        'nom' => 'Comptabilité',
        'prenom' => 'Manager',
        'password' => 'password'
    ],
    [
        'email' => 'purchases@tpt-h.com',
        'nom' => 'Achats',
        'prenom' => 'Manager',
        'password' => 'password'
    ],
    [
        'email' => 'fournisseur@tpt-h.com',
        'nom' => 'Fournisseur',
        'prenom' => 'Test',
        'password' => 'password'
    ]
];

echo "Création des utilisateurs de test...\n";

foreach ($users as $userData) {
    try {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = DB::table('users')->where('email', $userData['email'])->first();
        
        if ($existingUser) {
            // Mettre à jour le mot de passe
            DB::table('users')
                ->where('email', $userData['email'])
                ->update([
                    'password' => Hash::make($userData['password']),
                    'updated_at' => now()
                ]);
            echo "Utilisateur {$userData['email']} mis à jour avec le mot de passe: {$userData['password']}\n";
        } else {
            // Créer un nouvel utilisateur
            DB::table('users')->insert([
                'email' => $userData['email'],
                'nom' => $userData['nom'],
                'prenom' => $userData['prenom'],
                'password' => Hash::make($userData['password']),
                'telephone' => '000000000',
                'statut' => 'actif',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "Utilisateur {$userData['email']} créé avec le mot de passe: {$userData['password']}\n";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la création/mise à jour de {$userData['email']}: " . $e->getMessage() . "\n";
    }
}

echo "Processus terminé.\n";