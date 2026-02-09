<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Définir explicitement les mappings morphiques
        Relation::morphMap([
            'agence' => 'App\Models\Agency',
            'societe' => 'App\Models\Company',
            'Agency' => 'App\Models\Agency',
            'Company' => 'App\Models\Company',
            'App\Models\Agency' => 'App\Models\Agency',
            'App\Models\Company' => 'App\Models\Company',
        ]);
    }
}