<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Company;
use App\Models\User;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $users = User::limit(5)->get();
        
        $clients = [
            [
                'nom_raison_sociale' => 'Tech Solutions SARL',
                'type_client' => 'entreprise',
                'telephone' => '+33 1 45 67 89 01',
                'email' => 'contact@techsolutions.fr',
                'adresse' => '123 Avenue des Champs-Élysées',
                'ville' => 'Paris',
                'contact_principal' => 'M. Dupont Jean',
                'canal_acquisition' => 'web',
                'type_relation' => 'client à crédit',
                'delai_paiement' => 30,
                'plafond_credit' => 50000.00,
                'mode_paiement_prefere' => 'virement',
                'statut' => 'actif',
                'categorie' => 'Or',
                'site_web' => 'https://www.techsolutions.fr'
            ],
            [
                'nom_raison_sociale' => 'Services Informatiques SA',
                'type_client' => 'entreprise',
                'telephone' => '+33 4 72 12 34 56',
                'email' => 'info@servicesinfo.fr',
                'adresse' => '45 Rue de la République',
                'ville' => 'Lyon',
                'contact_principal' => 'Mme Martin Sophie',
                'canal_acquisition' => 'recommandé',
                'type_relation' => 'client à crédit',
                'delai_paiement' => 45,
                'plafond_credit' => 30000.00,
                'mode_paiement_prefere' => 'chèque',
                'statut' => 'actif',
                'categorie' => 'Argent',
                'site_web' => 'https://www.servicesinfo.fr'
            ],
            [
                'nom_raison_sociale' => 'Digital Agency',
                'type_client' => 'entreprise',
                'telephone' => '+33 5 55 12 34 56',
                'email' => 'hello@digitalagency.fr',
                'adresse' => '78 Boulevard de la Liberté',
                'ville' => 'Bordeaux',
                'contact_principal' => 'M. Bernard Pierre',
                'canal_acquisition' => 'commerce direct',
                'type_relation' => 'client comptant',
                'delai_paiement' => 0,
                'plafond_credit' => 0.00,
                'mode_paiement_prefere' => 'carte bancaire',
                'statut' => 'actif',
                'categorie' => 'Bronze',
                'site_web' => 'https://www.digitalagency.fr'
            ],
            [
                'nom_raison_sociale' => 'StartUp Innovation',
                'type_client' => 'entreprise',
                'telephone' => '+33 3 88 12 34 56',
                'email' => 'contact@startup-innovation.fr',
                'adresse' => '22 Place de la Gare',
                'ville' => 'Strasbourg',
                'contact_principal' => 'Mme Petit Marie',
                'canal_acquisition' => 'web',
                'type_relation' => 'client à crédit',
                'delai_paiement' => 15,
                'plafond_credit' => 15000.00,
                'mode_paiement_prefere' => 'virement',
                'statut' => 'actif',
                'categorie' => 'Argent',
                'site_web' => 'https://www.startup-innovation.fr'
            ],
            [
                'nom_raison_sociale' => 'Consulting Pro',
                'type_client' => 'entreprise',
                'telephone' => '+33 2 40 12 34 56',
                'email' => 'contact@consultingpro.fr',
                'adresse' => '56 Avenue de la Paix',
                'ville' => 'Nantes',
                'contact_principal' => 'M. Robert Thomas',
                'canal_acquisition' => 'recommandé',
                'type_relation' => 'client VIP',
                'delai_paiement' => 60,
                'plafond_credit' => 100000.00,
                'mode_paiement_prefere' => 'virement',
                'statut' => 'actif',
                'categorie' => 'Or',
                'site_web' => 'https://www.consultingpro.fr'
            ]
        ];

        foreach ($clients as $clientData) {
            $company = $companies->random();
            $referent = $users->random();
            
            Client::firstOrCreate(
                ['email' => $clientData['email']],
                array_merge($clientData, [
                    'code_client' => Client::generateUniqueCode(),
                    'company_id' => $company->id,
                    'referent_commercial_id' => $referent->id
                ])
            );
        }
    }
}
