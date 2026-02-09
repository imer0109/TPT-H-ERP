<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_delivery_id',
        'supplier_order_item_id',
        'product_id',
        'quantite_commandee',
        'quantite_livree',
        'ecart',
        'condition_emballage',
        'notes',
        'compte_rendu',
        'preuve_service',
        'satisfaction'
    ];

    protected $casts = [
        'quantite_commandee' => 'integer',
        'quantite_livree' => 'integer',
        'ecart' => 'integer',
        'satisfaction' => 'integer'
    ];

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(SupplierDelivery::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(SupplierOrderItem::class, 'supplier_order_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Check if this item has a discrepancy
    public function hasDiscrepancy(): bool
    {
        return $this->ecart != 0;
    }

    // Get discrepancy type
    public function getDiscrepancyType()
    {
        if ($this->ecart > 0) {
            return 'excès';
        } elseif ($this->ecart < 0) {
            return 'manquant';
        }
        return 'conforme';
    }

    // Get discrepancy description
    public function getDiscrepancyDescription()
    {
        if ($this->ecart == 0) {
            return 'Conforme';
        }
        
        $type = $this->ecart > 0 ? 'en excès' : 'manquant';
        $quantity = abs($this->ecart);
        
        return "{$quantity} {$type}";
    }
}