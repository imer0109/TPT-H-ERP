<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'step_number',
        'validator_id',
        'action',
        'notes',
        'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the validation request this step belongs to
     */
    public function validationRequest()
    {
        return $this->belongsTo(ValidationRequest::class, 'request_id');
    }

    /**
     * Get the validator who performed this step
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    /**
     * Get the display name for the action
     */
    public function getActionDisplayName()
    {
        $actions = [
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'delegated' => 'Délégué',
            'escalated' => 'Escaladé'
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Scope for approved steps
     */
    public function scopeApproved($query)
    {
        return $query->where('action', 'approved');
    }

    /**
     * Scope for rejected steps
     */
    public function scopeRejected($query)
    {
        return $query->where('action', 'rejected');
    }
}