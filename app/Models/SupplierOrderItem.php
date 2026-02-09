<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_order_id','product_id','designation','description','quantite','unite','prix_unitaire','montant_total','tva_rate','tva_amount'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(SupplierOrder::class, 'supplier_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calculate the total amount including TVA for this item
    public function calculateTotalWithTva()
    {
        $ht = $this->quantite * $this->prix_unitaire;
        $tva = $ht * ($this->tva_rate / 100);
        return $ht + $tva;
    }

    // Get the TVA amount for this item
    public function getTvaAmount()
    {
        $ht = $this->quantite * $this->prix_unitaire;
        return $ht * ($this->tva_rate / 100);
    }
}