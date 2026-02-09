<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Fournisseur;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Agency;
use App\Models\Company;

class SimpleSupplierSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clés étrangères pour éviter les conflits
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Supprimer les données existantes
        try {
            \App\Models\SupplierOrderItem::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\SupplierOrder::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\Fournisseur::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\Product::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\Warehouse::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\User::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\Agency::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        try {
            \App\Models\Company::truncate();
        } catch (\Exception $e) {
            // La table n'existe peut-être pas
        }
        
        // Réactiver les contraintes de clés étrangères
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Créer une société de test
        if (Schema::hasTable('companies')) {
            $company = Company::create([
                'raison_sociale' => 'TPT-H ERP TEST',
                'type' => 'holding',
                'siege_social' => 'Test City',
                'telephone' => '123456789',
                'email' => 'test@tpt-h.com',
                'niu' => 'NIU123456',
                'rccm' => 'RCCM123456',
                'regime_fiscal' => 'Régime Général',
                'secteur_activite' => 'Technology',
                'devise' => 'XAF',
                'pays' => 'Pays Test',
                'ville' => 'Ville Test',
                'active' => true
            ]);
        } else {
            // Si la table n'existe pas, on ne crée pas de société
            $company = (object) ['id' => 1];
        }

        // Créer un utilisateur de test
        if (Schema::hasTable('users')) {
            $user = User::create([
                'nom' => 'Test',
                'prenom' => 'User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'telephone' => '123456789',
                'statut' => 'actif'
            ]);
        } else {
            $user = (object) ['id' => 1];
        }

        // Créer une agence de test
        if (Schema::hasTable('agencies')) {
            $agency = Agency::create([
                'nom' => 'Agence Test',
                'code_unique' => 'AGT001',
                'adresse' => 'Adresse Test',
                'zone_geographique' => 'Zone Test',
                'statut' => 'active',
                'company_id' => $company->id,
                'responsable_id' => $user->id
            ]);
        } else {
            $agency = (object) ['id' => 1];
        }

        // Vérifier si la table warehouses existe avant de créer un entrepôt
        if (Schema::hasTable('warehouses')) {
            $warehouse = Warehouse::create([
                'nom' => 'Entrepôt Principal',
                'code' => 'WH001',
                'adresse' => 'Adresse Entrepôt',
                'entity_type' => 'App\\Models\\Company',
                'entity_id' => $company->id,
                'type' => 'principal',
                'actif' => true,
                'created_by' => $user->id
            ]);
        } else {
            // Si la table n'existe pas, on crée un entrepôt factice pour la suite
            $warehouse = (object) ['id' => 1];
        }
        
        // Créer des produits de test
        $products = [];
        if (Schema::hasTable('products')) {
            for ($i = 1; $i <= 5; $i++) {
                $products[] = Product::create([
                    'name' => "Produit Test $i",
                    'description' => "Description du produit test $i",
                    'quantite' => rand(10, 100),
                    'prix_unitaire' => rand(1000, 10000)
                ]);
            }
        } else {
            // Si la table n'existe pas, on crée des produits factices
            for ($i = 1; $i <= 5; $i++) {
                $products[] = (object) ['id' => $i, 'name' => "Produit Test $i", 'description' => "Description du produit test $i", 'prix_unitaire' => rand(1000, 10000)];
            }
        }

        // Créer des fournisseurs de test
        $fournisseurs = [];
        if (Schema::hasTable('fournisseurs')) {
            for ($i = 1; $i <= 3; $i++) {
                $fournisseurs[] = Fournisseur::create([
                    'nom' => "Fournisseur Test $i",
                    'type' => ['personne_physique', 'entreprise', 'institution'][array_rand(['personne_physique', 'entreprise', 'institution'])],
                    'activite' => ['transport', 'logistique', 'matieres_premieres', 'services', 'autre'][array_rand(['transport', 'logistique', 'matieres_premieres', 'services', 'autre'])],
                    'statut' => 'actif',
                    'niu' => "NIU$i",
                    'rccm' => "RCCM$i",
                    'cnss' => "CNSS$i",
                    'adresse' => "Adresse Fournisseur $i",
                    'pays' => 'Pays Test',
                    'ville' => 'Ville Test',
                    'telephone' => "12345678$i",
                    'email' => "fournisseur$i@example.com",
                    'contact_principal' => "Contact $i",
                    'banque' => "Banque $i",
                    'numero_compte' => "COMPTE$i",
                    'devise' => 'XAF',
                    'condition_reglement' => ['comptant', 'credit'][array_rand(['comptant', 'credit'])],
                    'delai_paiement' => 30,
                    'est_actif' => true,
                    'societe_id' => $company->id,
                    'agency_id' => $agency->id
                ]);
            }
        } else {
            // Si la table n'existe pas, on crée des fournisseurs factices
            for ($i = 1; $i <= 3; $i++) {
                $fournisseurs[] = (object) ['id' => $i, 'nom' => "Fournisseur Test $i", 'societe_id' => $company->id, 'agency_id' => $agency->id];
            }
        }

        // Créer des commandes pour les fournisseurs
        $orders = [];
        if (Schema::hasTable('supplier_orders') && Schema::hasTable('supplier_order_items')) {
            foreach ($fournisseurs as $index => $fournisseur) {
                for ($i = 1; $i <= 2; $i++) {
                    $order = SupplierOrder::create([
                        'fournisseur_id' => $fournisseur->id,
                        'agency_id' => $agency->id,
                        'code' => "BOC-TEST-" . ($index + 1) . "-$i",
                        'date_commande' => now()->subDays(rand(1, 30)),
                        'statut' => ['commande', 'livre_partiel', 'livre_total', 'annule'][array_rand(['commande', 'livre_partiel', 'livre_total', 'annule'])],
                        'nature_achat' => ['Bien', 'Service'][array_rand(['Bien', 'Service'])],
                        'adresse_livraison' => $fournisseur->adresse,
                        'delai_contractuel' => now()->addDays(rand(15, 60)),
                        'conditions_paiement' => '30 jours',
                        'montant_ht' => 0,
                        'montant_tva' => 0,
                        'montant_ttc' => 0,
                        'tva_percentage' => 18.00,
                        'devise' => 'XAF',
                        'notes' => "Commande de test pour $fournisseur->nom",
                        'created_by' => $user->id
                    ]);

                    // Créer des articles pour la commande
                    $montant_ht = 0;
                    foreach (array_slice($products, 0, rand(2, 4)) as $product) {
                        $quantite = rand(5, 20);
                        $prix_unitaire = $product->prix_unitaire;
                        $total = $quantite * $prix_unitaire;
                        $tva_amount = $total * 0.18;

                        SupplierOrderItem::create([
                            'supplier_order_id' => $order->id,
                            'product_id' => $product->id,
                            'designation' => $product->name,
                            'description' => $product->description,
                            'quantite' => $quantite,
                            'unite' => 'unit',
                            'prix_unitaire' => $prix_unitaire,
                            'montant_total' => $total,
                            'tva_rate' => 18.00,
                            'tva_amount' => $tva_amount
                        ]);

                        $montant_ht += $total;
                    }

                    // Mettre à jour les montants de la commande
                    $tva = $montant_ht * 0.18;
                    $tcc = $montant_ht + $tva;

                    $order->update([
                        'montant_ht' => $montant_ht,
                        'montant_tva' => $tva,
                        'montant_ttc' => $tcc
                    ]);

                    $orders[] = $order;
                }
            }
        } else {
            // Si les tables n'existent pas, on crée des commandes factices
            foreach ($fournisseurs as $index => $fournisseur) {
                for ($i = 1; $i <= 2; $i++) {
                    $orders[] = (object) ['id' => ($index * 2) + $i, 'fournisseur_id' => $fournisseur->id];
                }
            }
        }

        $this->command->info('Données de test des fournisseurs créées avec succès !');
    }
}