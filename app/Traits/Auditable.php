<?php

namespace App\Traits;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the trait
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            static::auditEvent('created', $model);
        });

        static::updated(function ($model) {
            static::auditEvent('updated', $model, $model->getOriginal(), $model->getAttributes());
        });

        static::deleted(function ($model) {
            static::auditEvent('deleted', $model);
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                static::auditEvent('restored', $model);
            });
        }

        if (method_exists(static::class, 'forceDeleted')) {
            static::forceDeleted(function ($model) {
                static::auditEvent('force_deleted', $model);
            });
        }
    }

    /**
     * Log audit event
     */
    protected static function auditEvent($event, $model, $oldValues = null, $newValues = null)
    {
        // Skip auditing if disabled for this model
        if (property_exists($model, 'auditEnabled') && !$model->auditEnabled) {
            return;
        }

        // Get excluded attributes
        $excludedAttributes = property_exists($model, 'auditExclude') ? $model->auditExclude : ['updated_at'];

        // Filter out excluded attributes from old and new values
        if ($oldValues) {
            $oldValues = collect($oldValues)->except($excludedAttributes)->toArray();
        }

        if ($newValues) {
            $newValues = collect($newValues)->except($excludedAttributes)->toArray();
        }

        // Only log if there are actual changes (for updates)
        if ($event === 'updated' && $oldValues && $newValues) {
            $changes = [];
            foreach ($newValues as $key => $newValue) {
                $oldValue = $oldValues[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = $newValue;
                }
            }

            // No changes to audit
            if (empty($changes)) {
                return;
            }

            $newValues = $changes;
        }

        // Create audit trail entry
        AuditTrail::logEvent($event, $model, $oldValues, $newValues, [
            'model_class' => get_class($model),
            'model_id' => $model->getKey(),
            'model_display_name' => method_exists($model, 'getAuditDisplayName') ? $model->getAuditDisplayName() : null
        ]);
    }

    /**
     * Get display name for audit trail
     */
    public function getAuditDisplayName()
    {
        // Try common name attributes
        $nameAttributes = ['name', 'nom', 'title', 'titre', 'label', 'libelle'];
        
        foreach ($nameAttributes as $attribute) {
            if (isset($this->{$attribute})) {
                return $this->{$attribute};
            }
        }

        // Try concatenated name attributes
        if (isset($this->nom) && isset($this->prenom)) {
            return $this->nom . ' ' . $this->prenom;
        }

        if (isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        }

        // Fall back to ID
        return get_class($this) . ' #' . $this->getKey();
    }

    /**
     * Get audit trail entries for this model
     */
    public function auditTrails()
    {
        return $this->morphMany(AuditTrail::class, 'auditable');
    }

    /**
     * Get latest audit trail entry
     */
    public function latestAudit()
    {
        return $this->morphOne(AuditTrail::class, 'auditable')->latest();
    }

    /**
     * Manually log an audit event
     */
    public function audit($event, $metadata = [])
    {
        AuditTrail::logEvent($event, $this, null, null, $metadata);
    }

    /**
     * Get audit history for this model
     */
    public function getAuditHistory()
    {
        return $this->auditTrails()
                   ->with('user')
                   ->orderBy('created_at', 'desc')
                   ->get()
                   ->map(function ($audit) {
                       return [
                           'event' => $audit->getEventDisplayName(),
                           'user' => $audit->user ? $audit->user->name : 'Système',
                           'date' => $audit->created_at,
                           'changes' => $audit->getFormattedChanges(),
                           'ip_address' => $audit->ip_address,
                           'risk_level' => $audit->risk_level
                       ];
                   });
    }

    /**
     * Check if model has been audited
     */
    public function hasAuditTrail()
    {
        return $this->auditTrails()->exists();
    }

    /**
     * Get audit statistics for this model
     */
    public function getAuditStats()
    {
        $audits = $this->auditTrails();

        return [
            'total_events' => $audits->count(),
            'created_at' => $audits->where('event', 'created')->first()?->created_at,
            'last_updated_at' => $audits->where('event', 'updated')->latest()->first()?->created_at,
            'update_count' => $audits->where('event', 'updated')->count(),
            'high_risk_events' => $audits->where('risk_level', 'high')->count(),
            'unique_users' => $audits->distinct('user_id')->count('user_id')
        ];
    }
}