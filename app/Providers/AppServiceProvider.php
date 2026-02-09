<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Extensions\ApplicationExtension;
use App\Extensions\ContainerPatch;
use App\Services\RolePermissionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistrer l'extension pour ajouter la méthode share()
        ApplicationExtension::registerExtensions();
        
        // Appliquer le correctif pour le problème "Cannot access offset of type Closure on array"
        ContainerPatch::apply();
        
        // Register RolePermissionService
        $this->app->singleton(RolePermissionService::class, function ($app) {
            return new RolePermissionService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('fr');
        
        Relation::morphMap([
            'Agency' => 'App\Models\Agency',
            'Company' => 'App\Models\Company',
        ]);
    }
}
