<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_transfert',
        'warehouse_source_id',
        'warehouse_destination_id',
        'product_id',
        'quantite',
        'unite',
        'statut',
        'justificatif',
        'notes',
        'created_by',
        'validated_by',
        'received_by',
        'date_validation',
        'date_reception'
    ];

    protected $casts = [
        'date_validation' => 'datetime',
        'date_reception' => 'datetime'
    ];

    public function warehouseSource()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_source_id');
    }

    public function warehouseDestination()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}