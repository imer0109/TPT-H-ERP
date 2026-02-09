<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignSupplierRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-supplier {email? : Email of the user to assign supplier role to} {--role=manager : Role to assign (manager, viewer, financial)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign supplier role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $roleOption = $this->option('role');
        
        // Map role option to role slug
        $roleMap = [
            'manager' => 'supplier_manager',
            'viewer' => 'supplier_viewer',
            'financial' => 'financial_manager'
        ];
        
        if (!isset($roleMap[$roleOption])) {
            $this->error("Invalid role option. Valid options are: manager, viewer, financial");
            return 1;
        }
        
        $roleSlug = $roleMap[$roleOption];
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("User with email {$email} not found!");
                return 1;
            }
        } else {
            // Get the first user
            $user = User::first();
            if (!$user) {
                $this->error("No users found in the system!");
                return 1;
            }
        }
        
        $this->info("User: " . $user->nom . " " . $user->prenom . " (" . $user->email . ")");
        
        // Check if supplier role exists
        $supplierRole = Role::where('slug', $roleSlug)->first();
        if (!$supplierRole) {
            $this->error("Supplier role '{$roleSlug}' not found!");
            return 1;
        }
        
        // Check if user already has the supplier role
        if ($user->hasRole($roleSlug)) {
            $this->info("User already has {$roleSlug} role.");
            return 0;
        }
        
        // Assign supplier role to user
        $user->assignRole($roleSlug);
        $this->info("Supplier role '{$roleSlug}' assigned to user successfully!");
        
        // Verify the assignment
        if ($user->hasRole($roleSlug)) {
            $this->info("Verification: User now has {$roleSlug} role.");
        } else {
            $this->error("Verification failed: User does not have {$roleSlug} role.");
            return 1;
        }
        
        return 0;
    }
}