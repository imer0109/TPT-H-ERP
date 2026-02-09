<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EntityAuditTrail extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',
        'entity_type',
        'user_id',
        'action',
        'changes',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    // Scope for companies
    public function scopeCompanies($query)
    {
        return $query->where('entity_type', 'company');
    }

    // Scope for agencies
    public function scopeAgencies($query)
    {
        return $query->where('entity_type', 'agency');
    }

    // Scope for specific actions
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Log an entity audit trail entry
     */
    public static function logEvent($action, $entity, $user = null, $description = '', $metadata = [])
    {
        // If no user provided, get the currently authenticated user
        if (!$user) {
            $user = Auth::user();
        }

        return self::create([
            'entity_id' => $entity->getKey(),
            'entity_type' => get_class($entity),
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'changes' => $metadata,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}