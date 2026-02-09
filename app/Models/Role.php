<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'color',
        'is_system',
        'is_temporary',
        'expires_at',
        'company_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_temporary' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the company that owns this role
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the users that have this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
                    ->withPivot(['assigned_by', 'assigned_at', 'expires_at'])
                    ->withTimestamps();
    }

    /**
     * Get the permissions assigned to this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
                    ->withTimestamps();
    }

    /**
     * Get the user who created this role
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this role
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Check if the role is expired (for temporary roles)
     */
    public function isExpired()
    {
        return $this->is_temporary && $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the role can be deleted
     */
    public function isDeletable()
    {
        return !$this->is_system && $this->users()->count() === 0;
    }

    /**
     * Get system roles that cannot be deleted
     */
    public static function getSystemRoles()
    {
        return self::where('is_system', true)->get();
    }

    /**
     * Scope for non-expired roles
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_temporary', false)
              ->orWhere(function ($subQ) {
                  $subQ->where('is_temporary', true)
                       ->where(function ($expiredQ) {
                           $expiredQ->whereNull('expires_at')
                                   ->orWhere('expires_at', '>', now());
                       });
              });
        });
    }

    /**
     * Scope for specific company roles
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}