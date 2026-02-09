<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de quelques sociétés de test
        DB::table('companies')->insert([
            [
                'id' => 1,
                'raison_sociale' => 'TPT INTERNATIONAL',
                'type' => 'holding',
                'secteur_activite' => 'Informatique',
                'devise' => 'XOF',
                'pays' => 'Côte d\'Ivoire',
                'ville' => 'Abidjan',
                'siege_social' => 'Cocody',
                'telephone' => '+225 01 02 03 04',
                'email' => 'contact@tptinternational.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'raison_sociale' => 'TPT SOLUTIONS',
                'type' => 'filiale',
                'secteur_activite' => 'Services',
                'devise' => 'XOF',
                'pays' => 'Côte d\'Ivoire',
                'ville' => 'Abidjan',
                'siege_social' => 'Plateau',
                'telephone' => '+225 05 06 07 08',
                'email' => 'info@tptsolutions.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Création de quelques agences de test
        DB::table('agencies')->insert([
            [
                'id' => 1,
                'company_id' => 1,
                'nom' => 'Agence Cocody',
                'code_unique' => 'AG001',
                'adresse' => 'Cocody, Rue des Jardins',
                'responsable_id' => 1,
                'zone_geographique' => 'Cocody',
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'nom' => 'Agence Plateau',
                'code_unique' => 'AG002',
                'adresse' => 'Plateau, Boulevard du Commerce',
                'responsable_id' => 1,
                'zone_geographique' => 'Plateau',
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'company_id' => 2,
                'nom' => 'Agence Marcory',
                'code_unique' => 'AG003',
                'adresse' => 'Marcory, Zone Industrielle',
                'responsable_id' => 1,
                'zone_geographique' => 'Marcory',
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
