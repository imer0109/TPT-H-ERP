<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class CheckAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:admin-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if administrator role has all permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking administrator permissions...');
        
        // Check if administrator role exists
        $adminRole = Role::where('slug', 'administrateur')->first();
        
        if (!$adminRole) {
            $this->error('Administrator role not found!');
            return 1;
        }
        
        $this->info("Administrator role found: " . $adminRole->nom);
        
        // Count permissions assigned to admin role
        $permissionCount = $adminRole->permissions()->count();
        $this->info("Number of permissions assigned to administrator role: " . $permissionCount);
        
        // Total permissions in system
        $totalPermissions = Permission::count();
        $this->info("Total permissions in system: " . $totalPermissions);
        
        if ($permissionCount >= $totalPermissions * 0.9) {
            $this->info("Administrator role has sufficient permissions.");
        } else {
            $this->warn("Administrator role may be missing some permissions.");
        }
        
        // Check if there are any users
        $userCount = User::count();
        $this->info("Total users in system: " . $userCount);
        
        if ($userCount > 0) {
            $firstUser = User::first();
            $this->info("First user: " . $firstUser->nom . " " . $firstUser->prenom . " (" . $firstUser->email . ")");
            
            // Check roles assigned to first user
            $userRoles = $firstUser->roles()->pluck('nom')->toArray();
            $this->info("Roles assigned to first user: " . implode(', ', $userRoles));
            
            // Check if first user has administrator role
            if ($firstUser->hasRole('administrateur')) {
                $this->info("First user has administrator role - they should have full access.");
            } else {
                $this->warn("First user does not have administrator role.");
            }
        }
        
        return 0;
    }
}