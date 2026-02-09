<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Agency;


use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'entity_type',
        'entity_id',
        'adresse',
        'type',
        'actif',
        'created_by'
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function transfertsSource()
    {
        return $this->hasMany(StockTransfer::class, 'warehouse_source_id');
    }

    public function transfertsDestination()
    {
        return $this->hasMany(StockTransfer::class, 'warehouse_destination_id');
    }

    public function alerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
