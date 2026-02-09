<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-admin {email? : Email of the user to assign admin role to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign administrator role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
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
        
        // Check if administrator role exists
        $adminRole = Role::where('slug', 'administrateur')->first();
        if (!$adminRole) {
            $this->error("Administrator role not found!");
            return 1;
        }
        
        // Check if user already has administrator role
        if ($user->hasRole('administrateur')) {
            $this->info("User already has administrator role.");
            return 0;
        }
        
        // Assign administrator role to user
        $user->assignRole('administrateur');
        $this->info("Administrator role assigned to user successfully!");
        
        // Verify the assignment
        if ($user->hasRole('administrateur')) {
            $this->info("Verification: User now has administrator role.");
        } else {
            $this->error("Verification failed: User does not have administrator role.");
            return 1;
        }
        
        return 0;
    }
}