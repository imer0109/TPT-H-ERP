<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'raison_sociale',
        'type',
        'niu',
        'rccm',
        'regime_fiscal',
        'secteur_activite',
        'devise',
        'pays',
        'ville',
        'siege_social',
        'email',
        'telephone',
        'whatsapp',
        'site_web',
        'parent_id',
        'logo',
        'visuel',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Get the parent company (for subsidiaries)
     */
    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    /**
     * Get the subsidiaries of this company (for holdings)
     */
    public function filiales()
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    /**
     * Get the agencies of this company
     */
    public function agencies()
    {
        return $this->hasMany(Agency::class);
    }

    /**
     * Get the users assigned to this company
     */
    public function users()
    {
        return $this->morphToMany(User::class, 'entity', 'user_entity');
    }

    /**
     * Get the cash registers of this company
     */
    public function cashRegisters()
    {
        return $this->morphMany(CashRegister::class, 'entity');
    }

    /**
     * Get the bank accounts of this company
     */
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Get the policies of this company
     */
    public function policies()
    {
        return $this->hasMany(CompanyPolicy::class);
    }

    /**
     * Get the tax regulations of this company
     */
    public function taxRegulations()
    {
        return $this->hasMany(TaxRegulation::class);
    }

    /**
     * Get the audit trails for this company
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
     * Scope for active companies
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for holding companies
     */
    public function scopeHoldings($query)
    {
        return $query->where('type', 'holding');
    }

    /**
     * Check if this company is a holding
     */
    public function isHolding()
    {
        return $this->type === 'holding';
    }

    /**
     * Check if this company is a subsidiary
     */
    public function isSubsidiary()
    {
        return $this->type === 'filiale';
    }
    
    /**
     * Mark company as validated
     */
    public function markAsValidated()
    {
        $this->update(['active' => true]);
    }
}