<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fournisseur_id',
        'contract_number',
        'contract_type',
        'description',
        'start_date',
        'end_date',
        'renewal_date',
        'auto_renewal',
        'value',
        'currency',
        'status',
        'terms',
        'special_conditions',
        'responsible_id',
        'last_review_date',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'last_review_date' => 'date',
        'auto_renewal' => 'boolean',
        'value' => 'decimal:2'
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function documents()
    {
        return $this->morphMany(FournisseurDocument::class, 'documentable');
    }

    public function isExpiringSoon()
    {
        // Contract is expiring within 30 days
        return $this->end_date && $this->end_date->diffInDays(now()) <= 30 && $this->end_date->isFuture();
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'active':
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>';
            case 'expired':
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Expiré</span>';
            case 'terminated':
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Résilié</span>';
            default:
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . ucfirst($this->status) . '</span>';
        }
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->end_date) {
            return $this->end_date->diffInDays(now());
        }
        return null;
    }

    protected static function booted()
    {
        static::creating(function ($contract) {
            // Generate contract number if not provided
            if (empty($contract->contract_number)) {
                $contract->contract_number = 'CTR-' . now()->format('Y') . '-' . strtoupper(uniqid());
            }
            
            // Set status based on dates
            if ($contract->end_date && $contract->end_date->isPast()) {
                $contract->status = 'expired';
            } elseif ($contract->start_date && $contract->start_date->isFuture()) {
                $contract->status = 'pending';
            } else {
                $contract->status = 'active';
            }
        });

        static::updating(function ($contract) {
            // Update status based on dates
            if ($contract->end_date && $contract->end_date->isPast()) {
                $contract->status = 'expired';
            } elseif ($contract->start_date && $contract->start_date->isFuture()) {
                $contract->status = 'pending';
            } else {
                $contract->status = 'active';
            }
        });
    }
}