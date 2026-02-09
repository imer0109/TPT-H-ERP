<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fournisseur;

class SupplierUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un fournisseur de test s'il n'existe pas
        $supplier = Fournisseur::firstOrCreate(
            ['email' => 'fournisseur@tpt-h.com'],
            [
                'nom' => 'FOURNISSEUR TEST',  // Utilisation de 'nom' au lieu de 'raison_sociale'
                'type' => 'entreprise',
                'activite' => 'services',
                'statut' => 'actif',
                'niu' => 'NIU-F-001',
                'rccm' => 'RCCM-F-001',
                'cnss' => 'CNSS-F-001',
                'adresse' => 'Adresse du fournisseur de test',
                'pays' => 'Pays Test',
                'ville' => 'Ville Test',
                'telephone' => '0123456789',
                'email' => 'fournisseur@tpt-h.com',
                'contact_principal' => 'Contact Principal',
                'banque' => 'Banque Test',
                'numero_compte' => 'COMPTE001',
                'devise' => 'XAF',
                'condition_reglement' => 'comptant',
                'delai_paiement' => 30,
                'est_actif' => true,
            ]
        );

        // Créer un utilisateur pour le portail fournisseur s'il n'existe pas
        $user = User::firstOrCreate(
            ['email' => 'fournisseur@tpt-h.com'],
            [
                'nom' => 'Fournisseur',
                'prenom' => 'Test',
                'email' => 'fournisseur@tpt-h.com',
                'password' => bcrypt('password'),
                'telephone' => '0123456789',
                'statut' => 'actif',
                'email_verified_at' => now(),
            ]
        );

        // Associer l'utilisateur au fournisseur
        if (!$user->fournisseur_id) {
            $user->update(['fournisseur_id' => $supplier->id]);
        }

        $this->command->info("Utilisateur fournisseur créé: fournisseur@tpt-h.com - Mot de passe: password");
        $this->command->info("Ce compte permet d'accéder au portail fournisseur");
    }
}