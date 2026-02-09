<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierInvoice extends Model
{
    use HasFactory;
    
    // Spécifier que le modèle utilise des entiers et non des UUID
    public $incrementing = true;
    
    // Définir le type de clé primaire
    protected $keyType = 'int';

    protected $fillable = [
        'fournisseur_id',
        'supplier_order_id',
        'numero_facture',
        'date_facture',
        'date_echeance',
        'montant_total',
        'montant_paye',
        'devise',
        'statut',
        'fichier_facture',
        'notes',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(SupplierOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class, 'invoice_id');
    }

    public function getSoldeAttribute()
    {
        return $this->montant_total - $this->montant_paye;
    }

    public function getIsOverdueAttribute()
    {
        return $this->date_echeance < now() && $this->solde > 0;
    }
}