<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'session_id',
        'risk_level',
        'metadata'
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'metadata' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company this audit belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the auditable model
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Log an audit trail entry
     */
    public static function logEvent($event, $model, $oldValues = null, $newValues = null, $metadata = [])
    {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user ? $user->id : null,
            'company_id' => $user ? $user->company_id : null,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'risk_level' => self::calculateRiskLevel($event, $model),
            'metadata' => $metadata
        ]);
    }

    /**
     * Calculate risk level based on event and model
     */
    protected static function calculateRiskLevel($event, $model)
    {
        $sensitiveModels = [
            'App\Models\AccountingEntry',
            'App\Models\Employee',
            'App\Models\CashRegister',
            'App\Models\User',
            'App\Models\Role',
            'App\Models\Permission'
        ];

        $highRiskEvents = ['deleted', 'force_deleted'];
        $mediumRiskEvents = ['created', 'updated'];

        if (in_array(get_class($model), $sensitiveModels)) {
            if (in_array($event, $highRiskEvents)) {
                return 'high';
            } elseif (in_array($event, $mediumRiskEvents)) {
                return 'medium';
            }
        }

        return 'low';
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChanges()
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        return $changes;
    }

    /**
     * Get the display name for the event
     */
    public function getEventDisplayName()
    {
        $events = [
            'created' => 'Créé',
            'updated' => 'Modifié',
            'deleted' => 'Supprimé',
            'restored' => 'Restauré',
            'force_deleted' => 'Supprimé définitivement',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'failed_login' => 'Tentative de connexion échouée',
            'password_changed' => 'Mot de passe modifié',
            'role_assigned' => 'Rôle attribué',
            'role_revoked' => 'Rôle révoqué',
            'permission_granted' => 'Permission accordée',
            'permission_revoked' => 'Permission révoquée'
        ];

        return $events[$this->event] ?? ucfirst($this->event);
    }

    /**
     * Scope for high risk events
     */
    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'high');
    }

    /**
     * Scope for specific company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for specific model type
     */
    public function scopeForModel($query, $modelType)
    {
        return $query->where('auditable_type', $modelType);
    }
}