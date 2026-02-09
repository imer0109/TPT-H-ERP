<?php

namespace App\Extensions;

use Illuminate\Foundation\Application;
use Closure;

class ApplicationExtension
{
    /**
     * Enregistre les extensions pour l'application Laravel
     */
    public static function registerExtensions()
    {
        // Ajouter la méthode share() à l'Application
        if (!method_exists(Application::class, 'share')) {
            Application::mixin(new class {
                public function share()
                {
                    // Emule l'ancienne méthode Application::share(Closure $factory): Closure
                    return function (Closure $factory) {
                        return function ($container = null) use ($factory) {
                            static $instance;
                            if ($instance === null) {
                                $instance = $factory($container ?: app());
                            }
                            return $instance;
                        };
                    };
                }
            });
        }
    }
}