<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderItem;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use App\Models\SupplierDelivery;
use App\Models\SupplierDeliveryItem;
use App\Models\SupplierIssue;
use App\Models\SupplierContract;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Agency;
use App\Models\Company;

class SupplierTestDataSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clés étrangères pour éviter les conflits
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Supprimer les données existantes
        SupplierPayment::truncate();
        SupplierInvoice::truncate();
        SupplierDeliveryItem::truncate();
        SupplierDelivery::truncate();
        SupplierOrderItem::truncate();
        SupplierIssue::truncate();
        SupplierContract::truncate();
        SupplierOrder::truncate();
        Fournisseur::truncate();
        Product::truncate();
        Warehouse::truncate();
        Agency::truncate();
        User::truncate();
        Company::truncate();
        
        // Réactiver les contraintes de clés étrangères
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Créer une société de test
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

        // Créer un utilisateur de test
        $user = User::create([
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'telephone' => '123456789',
            'statut' => 'actif'
        ]);

        // Créer une agence de test
        $agency = Agency::create([
            'nom' => 'Agence Test',
            'code_unique' => 'AGT001',
            'adresse' => 'Adresse Test',
            'zone_geographique' => 'Zone Test',
            'statut' => 'active',
            'company_id' => $company->id,
            'responsable_id' => $user->id  // Utiliser un ID existant
        ]);

        // Créer un entrepôt de test
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

        // Créer des produits de test
        $products = [];
        for ($i = 1; $i <= 5; $i++) {
            $products[] = Product::create([
                'name' => "Produit Test $i",
                'description' => "Description du produit test $i",
                'quantite' => rand(10, 100),
                'prix_unitaire' => rand(1000, 10000)
            ]);
        }

        // Créer des fournisseurs de test
        $fournisseurs = [];
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

        // Créer des commandes pour les fournisseurs
        $orders = [];
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

        // Créer des livraisons
        $deliveries = [];
        foreach ($orders as $order) {
            if ($order->statut !== 'commande' && $order->statut !== 'annule') {
                $delivery = SupplierDelivery::create([
                    'supplier_order_id' => $order->id,
                    'fournisseur_id' => $order->fournisseur_id,
                    'warehouse_id' => $warehouse->id,
                    'date_livraison' => now()->subDays(rand(1, 15)),
                    'statut' => ['partiel', 'total', 'annule'][array_rand(['partiel', 'total', 'annule'])],
                    'notes' => 'Livraison de test',
                    'created_by' => $user->id
                ]);

                // Créer des articles de livraison
                foreach ($order->items as $item) {
                    $quantite_livree = rand(1, $item->quantite);
                    
                    SupplierDeliveryItem::create([
                        'supplier_delivery_id' => $delivery->id,
                        'product_id' => $item->product_id,
                        'quantite_commandee' => $item->quantite,
                        'quantite_livree' => $quantite_livree
                    ]);
                }

                $deliveries[] = $delivery;
            }
        }

        // Créer des factures
        $invoices = [];
        foreach ($orders as $order) {
            if ($order->statut !== 'commande' && $order->statut !== 'annule') {
                $invoice = SupplierInvoice::create([
                    'fournisseur_id' => $order->fournisseur_id,
                    'supplier_order_id' => $order->id,
                    'numero_facture' => "FAC-TEST-" . $order->id,
                    'date_facture' => now()->subDays(rand(1, 10)),
                    'date_echeance' => now()->addDays(rand(15, 45)),
                    'montant_total' => $order->montant_ttc,
                    'montant_paye' => 0,
                    'devise' => 'XAF',
                    'statut' => ['pending', 'paid', 'overdue'][array_rand(['pending', 'paid', 'overdue'])],
                    'notes' => 'Facture de test'
                ]);

                $invoices[] = $invoice;
            }
        }

        // Créer des paiements
        foreach ($invoices as $invoice) {
            if ($invoice->statut !== 'paid') {
                $montant_paiement = $invoice->montant_total * (rand(50, 100) / 100); // Paiement partiel ou complet
                
                SupplierPayment::create([
                    'fournisseur_id' => $invoice->fournisseur_id,
                    'supplier_invoice_id' => $invoice->id,
                    'date_paiement' => now()->subDays(rand(1, 5)),
                    'mode_paiement' => ['virement', 'cheque', 'espece'][array_rand(['virement', 'cheque', 'espece'])],
                    'montant' => $montant_paiement,
                    'devise' => 'XAF',
                    'reference_paiement' => "REF-PAY-" . $invoice->id,
                    'notes' => 'Paiement de test',
                    'validated_by' => $user->id,
                    'created_by' => $user->id,
                    'statut' => 'validated'
                ]);

                // Mettre à jour le montant payé de la facture
                $invoice->update([
                    'montant_paye' => $montant_paiement,
                    'statut' => $montant_paiement >= $invoice->montant_total ? 'paid' : 'partial'
                ]);
            }
        }

        // Créer des contrats
        foreach ($fournisseurs as $fournisseur) {
            SupplierContract::create([
                'fournisseur_id' => $fournisseur->id,
                'contract_type' => ['cadre', 'cadre_detaille', 'particulier'][array_rand(['cadre', 'cadre_detaille', 'particulier'])],
                'description' => "Contrat de test pour $fournisseur->nom",
                'start_date' => now()->subMonths(rand(1, 6)),
                'end_date' => now()->addMonths(rand(6, 12)),
                'value' => rand(1000000, 10000000),
                'currency' => 'XAF',
                'status' => 'active',
                'terms' => 'Conditions générales de test',
                'responsible_id' => $user->id,
                'titre' => "Contrat Test $fournisseur->nom"
            ]);
        }

        // Créer des réclamations
        foreach ($fournisseurs as $fournisseur) {
            SupplierIssue::create([
                'fournisseur_id' => $fournisseur->id,
                'type' => ['retard', 'produit_non_conforme', 'erreur_facturation', 'autre'][array_rand(['retard', 'produit_non_conforme', 'erreur_facturation', 'autre'])],
                'statut' => ['open', 'in_progress', 'resolved'][array_rand(['open', 'in_progress', 'resolved'])],
                'titre' => "Réclamation Test pour $fournisseur->nom",
                'description' => "Description de la réclamation de test pour le fournisseur $fournisseur->nom",
                'created_by' => $user->id
            ]);
        }

        $this->command->info('Données de test des fournisseurs créées avec succès !');
    }
}
