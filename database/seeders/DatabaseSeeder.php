<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\TypeProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@tpth.erp',
            'telephone' => '0123456789',
            'password' => Hash::make('password'),
            'statut' => 'actif'
        ]);

        TypeProduct::factory()->create([
            'name' => 'Logiciel',
        ]);

        TypeProduct::factory()->create([
            'name' => 'Equipement',
        ]);

        Category::factory()->create([
            'type_product_id' => TypeProduct::query()->first()->id,
            'name' => 'App Mobile',
        ]);
    }
}
