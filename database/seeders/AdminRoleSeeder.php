<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Trouver l'utilisateur administrateur
        $adminUser = \App\Models\User::where('email', 'admin@tpt-h.com')->first();
        
        if ($adminUser) {
            // Attacher l'utilisateur au rôle admin ou super admin s'il existe
            $adminRole = \App\Models\Role::where('slug', 'admin')->first();
            
            if (!$adminRole) {
                $adminRole = \App\Models\Role::where('slug', 'super_admin')->first();
            }
            
            if (!$adminRole) {
                $adminRole = \App\Models\Role::where('slug', 'super-admin')->first();
            }
            
            if ($adminRole && !$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
                $adminUser->roles()->attach($adminRole->id);
                $this->command->info('Rôle administrateur attribué à l\'utilisateur admin@tpt-h.com');
            } else {
                $this->command->info('Aucun rôle admin trouvé ou utilisateur déjà attribué');
            }
        }
        
        $this->command->info('Vérification terminée pour l\'attribution des rôles administrateurs');
    }
}
