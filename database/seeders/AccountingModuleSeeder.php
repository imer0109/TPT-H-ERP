<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exécuter les seeders dans l'ordre correct
        $this->call([
            CompanySeeder::class,
            AccountingJournalSeeder::class,
            CostCenterSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
