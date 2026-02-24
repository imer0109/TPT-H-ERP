<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exécuter les seeders pour les modules de projet
        // Les données de projet sont déjà créées dans ProjectSeeder
        $this->call([
            ProjectSeeder::class,
        ]);
    }
}
