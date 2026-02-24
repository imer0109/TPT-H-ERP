<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Company;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(5)->get();
        $clients = Client::limit(3)->get();
        $fournisseurs = Fournisseur::limit(3)->get();
        $companies = Company::all();
        
        $documents = [
            // Documents clients
            [
                'nom' => 'Contrat Client - Tech Solutions',
                'type_document' => 'contrat',
                'chemin_fichier' => '/documents/clients/contrat_tech_solutions.pdf',
                'taille' => 2048000,
                'format' => 'pdf',
                'description' => 'Contrat de prestation de services avec Tech Solutions SARL',
                'documentable_id' => $clients->first()?->id,
                'documentable_type' => 'App\Models\Client',
                'user_id' => $users->random()?->id
            ],
            [
                'nom' => 'Fiche d\'ouverture compte client',
                'type_document' => 'fiche_ouverture',
                'chemin_fichier' => '/documents/clients/fiche_ouverture_services_info.pdf',
                'taille' => 1024000,
                'format' => 'pdf',
                'description' => 'Fiche d\'ouverture de compte client pour Services Informatiques SA',
                'documentable_id' => $clients->skip(1)->first()?->id,
                'documentable_type' => 'App\Models\Client',
                'user_id' => $users->random()?->id
            ],
            
            // Documents fournisseurs
            [
                'nom' => 'Bon de commande fournisseur',
                'type_document' => 'bon_commande',
                'chemin_fichier' => '/documents/fournisseurs/bc_tech_hardware.pdf',
                'taille' => 1536000,
                'format' => 'pdf',
                'description' => 'Bon de commande pour équipements informatiques',
                'documentable_id' => $fournisseurs->first()?->id,
                'documentable_type' => 'App\Models\Fournisseur',
                'user_id' => $users->random()?->id
            ],
            [
                'nom' => 'Contrat de service fournisseur',
                'type_document' => 'contrat',
                'chemin_fichier' => '/documents/fournisseurs/contrat_software_pro.pdf',
                'taille' => 3072000,
                'format' => 'pdf',
                'description' => 'Contrat de service avec Software Services Pro',
                'documentable_id' => $fournisseurs->skip(1)->first()?->id,
                'documentable_type' => 'App\Models\Fournisseur',
                'user_id' => $users->random()?->id
            ],
            
            // Documents société
            [
                'nom' => 'RCCM TPT-H INTERNATIONAL',
                'type_document' => 'rccm',
                'chemin_fichier' => '/documents/societes/rccm_tpth.pdf',
                'taille' => 2560000,
                'format' => 'pdf',
                'description' => 'Registre de Commerce et du Crédit Mobilier',
                'documentable_id' => $companies->first()?->id,
                'documentable_type' => 'App\Models\Company',
                'user_id' => $users->random()?->id
            ],
            [
                'nom' => 'NIU TPT-H INTERNATIONAL',
                'type_document' => 'niu',
                'chemin_fichier' => '/documents/societes/niu_tpth.pdf',
                'taille' => 512000,
                'format' => 'pdf',
                'description' => 'Numéro d\'Identification Unique',
                'documentable_id' => $companies->first()?->id,
                'documentable_type' => 'App\Models\Company',
                'user_id' => $users->random()?->id
            ],
            
            // Documents généraux
            [
                'nom' => 'Procédure de validation des achats',
                'type_document' => 'autre',
                'chemin_fichier' => '/documents/procedures/validation_achats.pdf',
                'taille' => 4096000,
                'format' => 'pdf',
                'description' => 'Procédure standard de validation des achats',
                'documentable_id' => $companies->first()?->id,
                'documentable_type' => 'App\\Models\\Company',
                'user_id' => $users->random()?->id
            ],
            [
                'nom' => 'Manuel utilisateur ERP',
                'type_document' => 'autre',
                'chemin_fichier' => '/documents/manuels/manuel_utilisateur.pdf',
                'taille' => 8192000,
                'format' => 'pdf',
                'description' => 'Manuel utilisateur complet du système ERP',
                'documentable_id' => $companies->first()?->id,
                'documentable_type' => 'App\\Models\\Company',
                'user_id' => $users->random()?->id
            ]
        ];

        foreach ($documents as $documentData) {
            Document::firstOrCreate(
                ['nom' => $documentData['nom']],
                $documentData
            );
        }
    }
}
