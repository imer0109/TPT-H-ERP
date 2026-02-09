<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'code_unique',
        'adresse',
        'responsable_id',
        'zone_geographique',
        'latitude',
        'longitude',
        'company_id',
        'statut'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Get the company that owns this agency
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the responsible user for this agency
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Get the cash registers of this agency
     */
    public function cashRegisters()
    {
        return $this->morphMany(CashRegister::class, 'entity');
    }

    /**
     * Get the users assigned to this agency
     */
    public function users()
    {
        return $this->morphToMany(User::class, 'entity', 'user_entity');
    }

    /**
     * Get the bank accounts of this agency
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Get the policies of this agency
     */
    public function policies()
    {
        return $this->hasMany(CompanyPolicy::class);
    }

    /**
     * Get the tax regulations of this agency
     */
    public function taxRegulations()
    {
        return $this->hasMany(TaxRegulation::class);
    }

    /**
     * Get the audit trails for this agency
     */
    public function auditTrails()
    {
        return $this->morphMany(EntityAuditTrail::class, 'entity');
    }

    /**
     * Log an audit trail entry
     */
    public function logAuditTrail($action, $description, $metadata = [])
    {
        EntityAuditTrail::logEvent($action, $this, Auth::user(), $description, $metadata);
    }

    /**
     * Scope for active agencies
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope for agencies in standby
     */
    public function scopeInStandby($query)
    {
        return $query->where('statut', 'en veille');
    }
    
    /**
     * Mark agency as validated
     */
    public function markAsValidated()
    {
        $this->update(['statut' => 'active']);
    }
}