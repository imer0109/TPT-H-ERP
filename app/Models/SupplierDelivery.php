<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierDelivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_order_id',
        'fournisseur_id',
        'warehouse_id',
        'numero_bl',
        'date_reception',
        'condition_emballage',
        'notes',
        'statut',
        'received_by',
        'validated_by',
        'validated_at',
        'validation_notes',
        'is_service'
    ];

    protected $casts = [
        'date_reception' => 'date',
        'validated_at' => 'datetime',
        'is_service' => 'boolean'
    ];

    const STATUTS = [
        'received' => 'Reçue',
        'partial' => 'Partielle',
        'validated' => 'Validée',
        'rejected' => 'Rejetée',
        'service_delivered' => 'Service livré'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(SupplierOrder::class, 'supplier_order_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SupplierDeliveryItem::class);
    }

    // Check if this is a service delivery
    public function isServiceDelivery(): bool
    {
        return $this->is_service === true;
    }

    // Check if this delivery has discrepancies
    public function hasDiscrepancies(): bool
    {
        return $this->items->contains(function ($item) {
            return $item->ecart != 0;
        });
    }

    // Get formatted status with colors for display
    public function getFormattedStatus()
    {
        $status = $this->statut;
        $colors = [
            'received' => 'bg-green-100 text-green-800',
            'partial' => 'bg-yellow-100 text-yellow-800',
            'validated' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'service_delivered' => 'bg-purple-100 text-purple-800'
        ];
        
        $texts = [
            'received' => 'Reçue',
            'partial' => 'Partielle',
            'validated' => 'Validée',
            'rejected' => 'Rejetée',
            'service_delivered' => 'Service livré'
        ];
        
        return [
            'text' => $texts[$status] ?? $status,
            'color' => $colors[$status] ?? 'bg-gray-100 text-gray-800'
        ];
    }
}