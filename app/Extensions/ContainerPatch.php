<?php

namespace App\Extensions;

use Illuminate\Container\Container;
use Closure;
use Illuminate\Contracts\Container\Container as ContainerContract;
use ReflectionClass;
use ReflectionProperty;

class ContainerPatch
{
    public static function apply()
    {
        static::patchBindMethod();
    }

    protected static function patchBindMethod()
    {
        app()->bind('safe_array_access', function() {
            return function($array, $key, $default) {
                if (is_array($array) 
                    && !($key instanceof Closure) 
                    && array_key_exists($key, $array)) {
                        return $array[$key];
                }
                return $default;
            };
        });
    }
}