<?php

namespace App\Traits;

use App\Models\EntityAuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait HasAuditTrail
{
    public static function bootHasAuditTrail()
    {
        static::created(function ($model) {
            $model->logAuditTrail('created');
        });

        static::updated(function ($model) {
            $model->logAuditTrail('updated');
        });

        static::deleted(function ($model) {
            $model->logAuditTrail('deleted');
        });
    }

    public function logAuditTrail($action, $description = null, $changes = null)
    {
        // Get the changes if not provided
        if ($changes === null && in_array($action, ['updated'])) {
            $changes = $this->getChanges();
            
            // Remove timestamps from changes
            unset($changes['updated_at']);
            unset($changes['created_at']);
        }

        EntityAuditTrail::create([
            'entity_id' => $this->id,
            'entity_type' => $this->getEntityType(),
            'user_id' => Auth::id(),
            'action' => $action,
            'changes' => $changes,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected function getEntityType()
    {
        return strtolower(class_basename($this));
    }

    public function auditTrails()
    {
        return $this->morphMany(EntityAuditTrail::class, 'entity');
    }
}