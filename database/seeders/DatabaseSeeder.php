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
        User::firstOrCreate(
            ['email' => 'admin@tpth.erp'],
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'telephone' => '0123456789',
                'password' => Hash::make('password'),
                'statut' => 'actif'
            ]
        );

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
        
        // Seed transaction natures
        $this->call(TransactionNatureSeeder::class);
        
        // Seed supplier permissions and roles
        $this->call(SupplierPermissionsSeeder::class);
        
        // Seed purchase validation workflows
        $this->call(PurchaseValidationWorkflowSeeder::class);
        
        // Seed all roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed complete user list with roles
        $this->call(CompleteUserSeeder::class);
    }
}
