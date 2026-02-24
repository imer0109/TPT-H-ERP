<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountingJournal;
use App\Models\Company;
use App\Models\User;

class AccountingJournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $adminUser = User::where('email', 'admin@tpth.erp')->first();
        $createdBy = $adminUser ? $adminUser->id : 1;
        
        $journals = [
            ['code' => 'CA', 'name' => 'Journal de Caisse', 'journal_type' => 'caisse', 'description' => 'Journal des opérations de caisse'],
            ['code' => 'BN', 'name' => 'Journal de Banque', 'journal_type' => 'banque', 'description' => 'Journal des opérations bancaires'],
            ['code' => 'AC', 'name' => 'Journal d\'Achats', 'journal_type' => 'achat', 'description' => 'Journal des achats et fournisseurs'],
            ['code' => 'VT', 'name' => 'Journal de Ventes', 'journal_type' => 'vente', 'description' => 'Journal des ventes et clients'],
            ['code' => 'PA', 'name' => 'Journal de Paie', 'journal_type' => 'salaire', 'description' => 'Journal des opérations de paie'],
            ['code' => 'JG', 'name' => 'Journal Général', 'journal_type' => 'general', 'description' => 'Journal général des opérations'],
            ['code' => 'OD', 'name' => 'Opérations Diverses', 'journal_type' => 'od', 'description' => 'Journal des opérations diverses']
        ];
        
        foreach ($companies as $company) {
            // Vérifier si les journaux existent déjà pour cette entreprise
            if (AccountingJournal::where('company_id', $company->id)->count() > 0) {
                echo "Journaux déjà existants pour l'entreprise: " . $company->raison_sociale . PHP_EOL;
                continue;
            }
            
            // Créer les journaux pour cette entreprise
            foreach ($journals as $journalData) {
                AccountingJournal::create([
                    'company_id' => $company->id,
                    'code' => $journalData['code'],
                    'name' => $journalData['name'],
                    'journal_type' => $journalData['journal_type'],
                    'description' => $journalData['description'],
                    'is_active' => true,
                    'requires_validation' => in_array($journalData['code'], ['BN', 'AC', 'PA', 'JG', 'OD']),
                    'auto_numbering' => true,
                    'number_prefix' => $journalData['code'],
                    'created_by' => $createdBy
                ]);
            }
        }
    }
}
