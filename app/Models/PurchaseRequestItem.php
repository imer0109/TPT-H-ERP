<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'product_id',
        'designation',
        'description',
        'quantite',
        'unite',
        'prix_unitaire_estime',
        'montant_total_estime',
        'fournisseur_suggere_id',
        'notes'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire_estime' => 'decimal:2',
        'montant_total_estime' => 'decimal:2'
    ];

    // Relations
    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fournisseurSuggere(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_suggere_id');
    }

    // Méthodes utilitaires
    public function calculateTotal()
    {
        $this->montant_total_estime = $this->quantite * $this->prix_unitaire_estime;
        return $this->montant_total_estime;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateTotal();
        });
    }
}