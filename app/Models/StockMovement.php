<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_mouvement',
        'warehouse_id',
        'product_id',
        'type',
        'source',
        'quantite',
        'unite',
        'prix_unitaire',
        'motif',
        'reference',
        'montant_total',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            // Generate a unique movement number if not provided
            if (empty($movement->numero_mouvement)) {
                $movement->numero_mouvement = 'MVT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
            
            // Set a default source if not provided
            if (empty($movement->source)) {
                $movement->source = 'achat';
            }
        });
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
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
    
    public function sourceEntity()
    {
        return $this->morphTo();
    }
    
    public function destinationEntity()
    {
        return $this->morphTo();
    }
}