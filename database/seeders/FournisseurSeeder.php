<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fournisseur;
use App\Models\Company;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        
        $fournisseurs = [
            [
                'nom' => 'Tech Hardware Solutions',
                'type' => 'entreprise',
                'activite' => 'matieres_premieres',
                'statut' => 'actif',
                'niu' => 'NIU-THS-001',
                'rccm' => 'RCCM-THS-001',
                'adresse' => '15 Rue de l\'Innovation',
                'pays' => 'France',
                'ville' => 'Lyon',
                'telephone' => '+33 4 72 12 34 56',
                'email' => 'contact@tech-hardware.fr',
                'site_web' => 'https://www.tech-hardware.fr',
                'contact_principal' => 'M. Dubois Michel',
                'banque' => 'BNP Paribas',
                'iban' => 'FR7630006000011234567890189',
                'numero_compte' => '12345678901',
                'devise' => 'EUR',
                'condition_reglement' => 'credit',
                'delai_paiement' => 30,
                'plafond_credit' => 50000.00,
                'date_debut_relation' => '2022-01-15',
                'est_actif' => true
            ],
            [
                'nom' => 'Software Services Pro',
                'type' => 'entreprise',
                'activite' => 'services',
                'statut' => 'actif',
                'niu' => 'NIU-SSP-001',
                'rccm' => 'RCCM-SSP-001',
                'adresse' => '45 Avenue des Technologies',
                'pays' => 'France',
                'ville' => 'Paris',
                'telephone' => '+33 1 45 67 89 01',
                'email' => 'info@software-pro.fr',
                'site_web' => 'https://www.software-pro.fr',
                'contact_principal' => 'Mme Martin Sophie',
                'banque' => 'Société Générale',
                'iban' => 'FR7630003000501234567890123',
                'numero_compte' => '23456789012',
                'devise' => 'EUR',
                'condition_reglement' => 'credit',
                'delai_paiement' => 45,
                'plafond_credit' => 30000.00,
                'date_debut_relation' => '2021-06-01',
                'est_actif' => true
            ],
            [
                'nom' => 'Logistics Express',
                'type' => 'entreprise',
                'activite' => 'logistique',
                'statut' => 'actif',
                'niu' => 'NIU-LE-001',
                'rccm' => 'RCCM-LE-001',
                'adresse' => '78 Boulevard de la Logistique',
                'pays' => 'France',
                'ville' => 'Marseille',
                'telephone' => '+33 4 91 23 45 67',
                'email' => 'contact@logistics-express.fr',
                'site_web' => 'https://www.logistics-express.fr',
                'contact_principal' => 'M. Bernard Pierre',
                'banque' => 'Crédit Agricole',
                'iban' => 'FR7630002000801234567890145',
                'numero_compte' => '34567890123',
                'devise' => 'EUR',
                'condition_reglement' => 'comptant',
                'delai_paiement' => 0,
                'plafond_credit' => 0.00,
                'date_debut_relation' => '2020-03-10',
                'est_actif' => true
            ],
            [
                'nom' => 'Cloud Infrastructure Ltd',
                'type' => 'entreprise',
                'activite' => 'services',
                'statut' => 'actif',
                'niu' => 'NIU-CIL-001',
                'rccm' => 'RCCM-CIL-001',
                'adresse' => '22 Rue du Cloud',
                'pays' => 'France',
                'ville' => 'Lille',
                'telephone' => '+33 3 20 12 34 56',
                'email' => 'support@cloud-infra.fr',
                'site_web' => 'https://www.cloud-infra.fr',
                'contact_principal' => 'Mme Petit Marie',
                'banque' => 'LCL',
                'iban' => 'FR7630004000901234567890167',
                'numero_compte' => '45678901234',
                'devise' => 'EUR',
                'condition_reglement' => 'credit',
                'delai_paiement' => 15,
                'plafond_credit' => 25000.00,
                'date_debut_relation' => '2023-01-20',
                'est_actif' => true
            ],
            [
                'nom' => 'Security Systems SARL',
                'type' => 'entreprise',
                'activite' => 'services',
                'statut' => 'actif',
                'niu' => 'NIU-SS-001',
                'rccm' => 'RCCM-SS-001',
                'adresse' => '33 Avenue de la Sécurité',
                'pays' => 'France',
                'ville' => 'Toulouse',
                'telephone' => '+33 5 61 12 34 56',
                'email' => 'contact@security-sys.fr',
                'site_web' => 'https://www.security-sys.fr',
                'contact_principal' => 'M. Robert Thomas',
                'banque' => 'Banque Postale',
                'iban' => 'FR7630005000101234567890189',
                'numero_compte' => '56789012345',
                'devise' => 'EUR',
                'condition_reglement' => 'credit',
                'delai_paiement' => 60,
                'plafond_credit' => 40000.00,
                'date_debut_relation' => '2021-12-01',
                'est_actif' => true
            ]
        ];

        foreach ($fournisseurs as $fournisseurData) {
            $company = $companies->random();
            
            Fournisseur::firstOrCreate(
                ['email' => $fournisseurData['email']],
                array_merge($fournisseurData, [
                    'societe_id' => $company->id,
                    'code_fournisseur' => Fournisseur::generateFournisseurCode()
                ])
            );
        }
    }
}
