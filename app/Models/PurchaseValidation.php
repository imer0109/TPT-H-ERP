<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'validated_by',
        'validation_level', // 1, 2, 3, etc. selon le circuit de validation
        'statut', // 'En attente', 'Approuvée', 'Rejetée'
        'commentaires',
        'validated_at',
        'montant_limite',
        'type_validation' // 'Montant', 'Famille', 'Profil'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'montant_limite' => 'decimal:2'
    ];

    const STATUTS = [
        'En attente' => 'En attente',
        'Approuvée' => 'Approuvée',
        'Rejetée' => 'Rejetée'
    ];

    const TYPES_VALIDATION = [
        'Montant' => 'Montant',
        'Famille' => 'Famille',
        'Profil' => 'Profil'
    ];

    // Relations
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Méthodes utilitaires
    public function isPending(): bool
    {
        return $this->statut === 'En attente';
    }

    public function isApproved(): bool
    {
        return $this->statut === 'Approuvée';
    }

    public function isRejected(): bool
    {
        return $this->statut === 'Rejetée';
    }

    public function canValidate(): bool
    {
        return $this->statut === 'En attente';
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('statut') && $model->statut !== 'En attente') {
                $model->validated_at = now();
            }
        });
    }
}