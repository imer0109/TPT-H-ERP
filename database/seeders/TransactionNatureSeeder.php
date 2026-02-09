<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionNature;

class TransactionNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $natures = [
            [
                'nom' => 'Vente',
                'description' => 'Encaissement provenant de la vente de produits ou services',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Acompte client',
                'description' => 'Acompte versé par un client',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Remboursement',
                'description' => 'Remboursement à un client',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Paiement fournisseur',
                'description' => 'Paiement effectué à un fournisseur',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Salaire',
                'description' => 'Paiement des salaires aux employés',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Dépense opérationnelle',
                'description' => 'Dépenses liées aux opérations courantes',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Versement bancaire',
                'description' => 'Versement d\'argent à la banque',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Retrait bancaire',
                'description' => 'Retrait d\'argent de la banque',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Investissement',
                'description' => 'Achat d\'actifs ou investissements',
                'actif' => true,
                'created_by' => 1
            ],
            [
                'nom' => 'Autre',
                'description' => 'Autres types de transactions',
                'actif' => true,
                'created_by' => 1
            ]
        ];

        foreach ($natures as $nature) {
            TransactionNature::firstOrCreate(
                ['nom' => $nature['nom']],
                $nature
            );
        }
    }
}
