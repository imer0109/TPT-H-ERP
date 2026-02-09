<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Auditable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'photo',
        'statut',
        'two_factor_enabled',
        'last_login_at',
        'last_login_ip',
        'login_restrictions',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime',
        'login_restrictions' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Audit configuration
     */
    protected $auditExclude = ['password', 'remember_token', 'last_login_at', 'last_login_ip', 'updated_at'];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withTimestamps();
    }
    
    /**
     * Get role IDs for the user
     */
    public function getRoleIds()
    {
        return $this->roles()->pluck('roles.id');
    }

    /**
     * Get permissions directly assigned to user
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot(['granted_by', 'granted_at', 'expires_at'])
                    ->withTimestamps();
    }

    /**
     * Get the company this user belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user's 2FA settings
     */
    public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuth::class);
    }

    /**
     * Get the user's sessions
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get the user's audit trail entries
     */
    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    /**
     * Get validation requests made by this user
     */
    public function validationRequests()
    {
        return $this->hasMany(ValidationRequest::class, 'requested_by');
    }

    /**
     * Get validation steps performed by this user
     */
    public function validationSteps()
    {
        return $this->hasMany(ValidationStep::class, 'validator_id');
    }

    public function societes()
    {
        return $this->morphedByMany(Company::class, 'entity', 'user_entity')
            ->withPivot(['date_debut', 'date_fin']);
    }

    public function agences()
    {
        return $this->morphedByMany(Agency::class, 'entity', 'user_entity')
            ->withPivot(['date_debut', 'date_fin']);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            // Special case for administrator - check both 'admin' and 'administrateur'
            if ($role === 'administrateur' || $role === 'admin') {
                return $this->roles()->where('roles.slug', 'administrateur')->exists() || 
                       $this->roles()->where('roles.nom', 'Administrateur')->exists() ||
                       $this->roles()->where('roles.slug', 'admin')->exists() ||
                       $this->roles()->where('roles.nom', 'Administrateur Système')->exists();
            }
            
            return $this->roles()->where('roles.slug', $role)->exists() || 
                   $this->roles()->where('roles.nom', $role)->exists();
        }
        
        return $this->roles()->where('roles.id', $role)->exists();
    }
    /**
     * Check if user has a specific permission (using static permissions for user management)
     */
    public function hasPermission($permission)
    {
        // Check if user has administrator role - they have access to everything
        if ($this->hasRole('administrateur')) {
            return true;
        }
        
        // First check for static permissions for user management
        if ($this->hasStaticUserManagementPermission($permission)) {
            return true;
        }
        
        // Check direct permissions
        $directPermission = $this->permissions()->where('slug', $permission)->exists() ||
                           $this->permissions()->where('nom', $permission)->exists();
        
        if ($directPermission) {
            return true;
        }
        
        // Check permissions through roles
        $hasPermissionThroughRole = $this->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('slug', $permission)->orWhere('nom', $permission);
        })->exists();
        
        if ($hasPermissionThroughRole) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if user has a static user management permission based on their role
     */
    public function hasStaticUserManagementPermission($permission)
    {
        // Only apply static permissions to user management
        if (!str_starts_with($permission, 'users.')) {
            return false;
        }
        
        // Get user roles
        $userRoles = $this->roles()->pluck('slug')->toArray();
        
        // Define static permissions for user management by role
        $staticPermissions = config('static_permissions.users', []);
        
        // Check each role against static permissions
        foreach ($userRoles as $role) {
            if (isset($staticPermissions[$role])) {
                if (in_array($permission, $staticPermissions[$role])) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        foreach ((array) $permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Assign a role to the user
     */
    public function assignRole($role, $assignedBy = null, $expiresAt = null)
    {
        $roleModel = is_string($role) ? Role::where('slug', $role)->first() : $role;
        
        if (!$roleModel) {
            return false;
        }
        
        $this->roles()->syncWithoutDetaching([$roleModel->id]);
        
        // Log the role assignment
        AuditTrail::logEvent('role_assigned', $this, null, null, [
            'role_id' => $roleModel->id,
            'role_nom' => $roleModel->nom,
            'assigned_by' => $assignedBy,
            'expires_at' => $expiresAt
        ]);
        
        return true;
    }

    /**
     * Remove a role from the user
     */
    public function removeRole($role)
    {
        $roleModel = is_string($role) ? Role::where('slug', $role)->first() : $role;
        
        if (!$roleModel) {
            return false;
        }
        
        $this->roles()->detach($roleModel->id);
        
        // Log the role removal
        AuditTrail::logEvent('role_revoked', $this, null, null, [
            'role_id' => $roleModel->id,
            'role_nom' => $roleModel->nom
        ]);
        
        return true;
    }

    /**
     * Grant a permission directly to the user
     */
    public function grantPermission($permission, $grantedBy = null, $expiresAt = null)
    {
        $permissionModel = is_string($permission) ? Permission::where('slug', $permission)->first() : $permission;
        
        if (!$permissionModel) {
            return false;
        }
        
        $this->permissions()->syncWithoutDetaching([
            $permissionModel->id => [
                'granted_by' => $grantedBy,
                'granted_at' => now(),
                'expires_at' => $expiresAt
            ]
        ]);
        
        // Log the permission grant
        AuditTrail::logEvent('permission_granted', $this, null, null, [
            'permission_id' => $permissionModel->id,
            'permission_nom' => $permissionModel->nom,
            'granted_by' => $grantedBy,
            'expires_at' => $expiresAt
        ]);
        
        return true;
    }

    /**
     * Revoke a permission from the user
     */
    public function revokePermission($permission)
    {
        $permissionModel = is_string($permission) ? Permission::where('slug', $permission)->first() : $permission;
        
        if (!$permissionModel) {
            return false;
        }
        
        $this->permissions()->detach($permissionModel->id);
        
        // Log the permission revocation
        AuditTrail::logEvent('permission_revoked', $this, null, null, [
            'permission_id' => $permissionModel->id,
            'permission_nom' => $permissionModel->nom
        ]);
        
        return true;
    }

    /**
     * Check if 2FA is enabled
     */
    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled && $this->twoFactorAuth && $this->twoFactorAuth->is_enabled;
    }

    /**
     * Get all user permissions (direct, through roles, and static for user management)
     */
    public function getAllPermissions()
    {
        $cacheKey = 'user_permissions_' . $this->id . '_' . $this->updated_at->timestamp;
        
        return cache()->remember($cacheKey, 3600, function() { // Cache pour 1 heure
            // Check if user has administrator role - they have access to everything
            if ($this->hasRole('administrateur')) {
                return Permission::all();
            }
            
            // Get direct permissions with optimized query
            $directPermissions = $this->permissions()->select('permissions.id', 'permissions.nom', 'permissions.slug', 'permissions.module')->get();
            
            // Get role permissions with optimized query
            $roleIds = $this->getRoleIds()->toArray();
            $rolePermissions = Permission::select('permissions.id', 'permissions.nom', 'permissions.slug', 'permissions.module')
                ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                ->whereIn('permission_role.role_id', $roleIds)
                ->distinct()
                ->get();
            
            // Get static user management permissions based on user roles
            $staticPermissions = collect();
            $userRoles = $this->roles()->select('roles.slug')->pluck('slug')->toArray();
            $configStaticPermissions = config('static_permissions.users', []);
            
            foreach ($userRoles as $role) {
                if (isset($configStaticPermissions[$role])) {
                    foreach ($configStaticPermissions[$role] as $perm) {
                        // Only include user management permissions
                        if (str_starts_with($perm, 'users.')) {
                            // Create a temporary permission object for static permissions
                            $staticPermissions->push((object)[
                                'id' => 'static_' . md5($perm),
                                'slug' => $perm,
                                'name' => config('static_permissions.descriptions.' . $perm, $perm),
                                'module' => 'users',
                                'description' => config('static_permissions.descriptions.' . $perm, ''),
                                'is_static' => true
                            ]);
                        }
                    }
                }
            }
            
            // Merge all permissions and remove duplicates
            return $directPermissions->merge($rolePermissions)->merge($staticPermissions)->unique('slug');
        });
    }
    /**
     * Check if user can access a specific module
     */
    public function canAccessModule($module)
    {
        $cacheKey = 'user_module_access_' . $this->id . '_' . $module . '_' . $this->updated_at->timestamp;
        
        return cache()->remember($cacheKey, 3600, function() use ($module) { // Cache pour 1 heure
            // Check if user has administrator role - they have access to everything
            if ($this->hasRole('administrateur') || $this->hasRole('admin')) {
                return true;
            }
            
            // Optimized check: directly query permissions instead of loading all
            // Check direct permissions
            if ($this->permissions()->where('module', $module)->exists()) {
                return true;
            }
            
            // Check direct user permissions (new approach)
            $hasUserPermission = DB::table('user_permissions')
                ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                ->where('user_permissions.user_id', $this->id)
                ->where('permissions.module', $module)
                ->exists();
            
            if ($hasUserPermission) {
                return true;
            }
            
            // Check permissions through roles
            $roleIds = $this->getRoleIds()->toArray();
            if (empty($roleIds)) {
                return false;
            }
            
            $hasModulePermission = Permission::join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                ->whereIn('permission_role.role_id', $roleIds)
                ->where('permissions.module', $module)
                ->exists();
            
            if ($hasModulePermission) {
                return true;
            }
            
            // Check static permissions for all modules
            $userRoles = $this->roles()->select('roles.slug')->pluck('slug')->toArray();
            $configStaticPermissions = config('static_permissions', []);
            
            // Check each module's static permissions
            foreach ($configStaticPermissions as $moduleName => $rolePermissions) {
                if ($moduleName === 'descriptions') continue; // Skip descriptions section
                
                if ($moduleName === $module) {
                    foreach ($userRoles as $role) {
                        if (isset($rolePermissions[$role])) {
                            foreach ($rolePermissions[$role] as $perm) {
                                // Check if permission belongs to this module
                                if (str_starts_with($perm, $module . '.')) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
            
            return false;
        });
    }    /**
     * Check if user is currently assigned to a company
     */
    public function isAssignedToCompany($companyId)
    {
        return $this->societes()
            ->where('company_id', $companyId)
            ->where(function ($query) {
                $query->whereNull('date_debut')
                      ->orWhere('date_debut', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('date_fin')
                      ->orWhere('date_fin', '>=', now());
            })
            ->exists();
    }

    /**
     * Check if user is currently assigned to an agency
     */
    public function isAssignedToAgency($agencyId)
    {
        return $this->agences()
            ->where('agency_id', $agencyId)
            ->where(function ($query) {
                $query->whereNull('date_debut')
                      ->orWhere('date_debut', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('date_fin')
                      ->orWhere('date_fin', '>=', now());
            })
            ->exists();
    }

    /**
     * Get all currently assigned entities (companies and agencies)
     */
    public function getCurrentEntities()
    {
        $currentDate = now();
        
        $companies = $this->societes()
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('date_debut')
                      ->orWhere('date_debut', '<=', $currentDate);
            })
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('date_fin')
                      ->orWhere('date_fin', '>=', $currentDate);
            })
            ->get();
            
        $agencies = $this->agences()
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('date_debut')
                      ->orWhere('date_debut', '<=', $currentDate);
            })
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('date_fin')
                      ->orWhere('date_fin', '>=', $currentDate);
            })
            ->get();
            
        return [
            'companies' => $companies,
            'agencies' => $agencies
        ];
    }

    /**
     * Assign user to a company with date range
     */
    public function assignToCompany($companyId, $dateDebut = null, $dateFin = null)
    {
        $this->societes()->attach($companyId, [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        
        // Log the assignment
        AuditTrail::logEvent('company_assigned', $this, Company::find($companyId), null, [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
    }

    /**
     * Assign user to an agency with date range
     */
    public function assignToAgency($agencyId, $dateDebut = null, $dateFin = null)
    {
        $this->agences()->attach($agencyId, [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
        
        // Log the assignment
        AuditTrail::logEvent('agency_assigned', $this, Agency::find($agencyId), null, [
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]);
    }

    /**
     * Remove user assignment from a company
     */
    public function removeFromCompany($companyId)
    {
        $this->societes()->detach($companyId);
        
        // Log the removal
        AuditTrail::logEvent('company_removed', $this, Company::find($companyId), null, []);
    }

    /**
     * Remove user assignment from an agency
     */
    public function removeFromAgency($agencyId)
    {
        $this->agences()->detach($agencyId);
        
        // Log the removal
        AuditTrail::logEvent('agency_removed', $this, Agency::find($agencyId), null, []);
    }

    /**
     * Get the manager of this user
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the subordinates of this user
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Check if this user is a manager of another user
     */
    public function isManagerOf($userId)
    {
        return $this->subordinates()->where('id', $userId)->exists();
    }

    /**
     * Get the full hierarchy of subordinates (recursive)
     */
    public function getAllSubordinates()
    {
        $subordinates = $this->subordinates;
        $allSubordinates = collect();
        
        foreach ($subordinates as $subordinate) {
            $allSubordinates->push($subordinate);
            $allSubordinates = $allSubordinates->merge($subordinate->getAllSubordinates());
        }
        
        return $allSubordinates;
    }

    /**
     * Get the chain of command (managers up to the top)
     */
    public function getChainOfCommand()
    {
        $chain = collect();
        $current = $this;
        
        while ($current->manager) {
            $chain->push($current->manager);
            $current = $current->manager;
        }
        
        return $chain;
    }

    /**
     * Get the team this user belongs to
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the department this user belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    
    public function documents()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    /**
     * Record user login
     */
    public function recordLogin($ipAddress, $userAgent, $location = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress
        ]);
        
        // Create session record
        $session = UserSession::createSession(
            $this->id,
            session()->getId(),
            $ipAddress,
            $userAgent,
            $location
        );
        
        return $session;
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->statut === 'actif';
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope for users with specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }

    /**
     * Get allowed devices for this user
     */
    public function getAllowedDevices()
    {
        if (!$this->login_restrictions) {
            return [];
        }
        
        $restrictions = json_decode($this->login_restrictions, true);
        return $restrictions['devices'] ?? [];
    }
    
    /**
     * Get allowed IP addresses for this user
     */
    public function getAllowedIPs()
    {
        if (!$this->login_restrictions) {
            return [];
        }
        
        $restrictions = json_decode($this->login_restrictions, true);
        return $restrictions['ips'] ?? [];
    }
    
    /**
     * Get allowed time windows for this user
     */
    public function getAllowedTimeWindows()
    {
        if (!$this->login_restrictions) {
            return [];
        }
        
        $restrictions = json_decode($this->login_restrictions, true);
        return $restrictions['time_windows'] ?? [];
    }
    
    /**
     * Check if user can login from current device/IP/time
     */
    public function canLoginFromCurrentContext()
    {
        $session = new UserSession();
        $session->user = $this;
        $session->ip_address = request()->ip();
        $session->user_agent = request()->userAgent();
        
        // Parse user agent to get device info
        $deviceInfo = UserSession::parseUserAgent($session->user_agent);
        $session->device_type = $deviceInfo['device'];
        $session->browser = $deviceInfo['browser'];
        $session->platform = $deviceInfo['platform'];
        
        // Check all restrictions
        if (!$session->isAllowedDevice() || 
            !$session->isAllowedIP() || 
            !$session->isWithinAllowedTime()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set login restrictions for this user
     */
    public function setLoginRestrictions($restrictions)
    {
        $this->update([
            'login_restrictions' => json_encode($restrictions)
        ]);
    }
    
    /**
     * Check if user can validate a specific validation request
     */
    public function canValidateRequest(ValidationRequest $request)
    {
        // Check if request is pending
        if ($request->status !== 'pending') {
            return false;
        }
        
        // Get current step configuration
        $workflowSteps = $request->workflow->steps ?? [];
        $currentStep = $request->current_step;
        
        if (!isset($workflowSteps[$currentStep])) {
            return false;
        }
        
        $stepConfig = $workflowSteps[$currentStep];
        
        // Check if user has the required role
        $requiredRole = $stepConfig['role'] ?? null;
        if (!$requiredRole) {
            return false;
        }
        
        // Check if user has the required role
        return $this->hasRole($requiredRole);
    }
}