<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'module',
        'resource',
        'action',
        'is_system',
        'requires_validation',
        'validation_level'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'requires_validation' => 'boolean',
        'validation_level' => 'integer'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($permission) {
            // Automatically generate slug if not provided
            if (empty($permission->slug)) {
                $permission->slug = self::generateSlug($permission->module, $permission->resource ?? '', $permission->action);
            }
        });
    }

    /**
     * Get the roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withTimestamps();
    }

    /**
     * Get the users that have this permission directly
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
                    ->withPivot(['granted_by', 'granted_at', 'expires_at'])
                    ->withTimestamps();
    }

    /**
     * Check if this permission requires validation workflow
     */
    public function requiresValidation()
    {
        return $this->requires_validation;
    }

    /**
     * Get permissions by module
     */
    public static function getByModule($module)
    {
        return self::where('module', $module)->orderBy('resource')->orderBy('action')->get();
    }

    /**
     * Get all available modules
     */
    public static function getModules()
    {
        return self::distinct('module')->pluck('module')->sort()->values();
    }

    /**
     * Get all available actions
     */
    public static function getActions()
    {
        return self::distinct('action')->pluck('action')->sort()->values();
    }

    /**
     * Scope for system permissions
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope for custom permissions
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    /**
     * Generate permission slug from components
     */
    public static function generateSlug($module, $resource, $action)
    {
        if (!empty($resource)) {
            return strtolower($module . '.' . $resource . '.' . $action);
        }
        return strtolower($module . '.' . $action);
    }

    /**
     * Create default permissions for a module
     */
    public static function createModulePermissions($module, $resources)
    {
        $actions = ['view', 'create', 'edit', 'delete', 'export'];
        $permissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'nom' => ucfirst($action) . ' ' . ucfirst($resource),
                    'slug' => self::generateSlug($module, $resource, $action),
                    'description' => ucfirst($action) . ' access to ' . $resource . ' in ' . $module . ' module',
                    'module' => $module,
                    'resource' => $resource,
                    'action' => $action,
                    'is_system' => true,
                    'requires_validation' => in_array($action, ['create', 'edit', 'delete']),
                    'validation_level' => in_array($action, ['delete']) ? 2 : 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        return self::insert($permissions);
    }
}