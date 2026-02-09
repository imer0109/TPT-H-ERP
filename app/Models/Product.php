<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $guarded = ['id'];
    
    protected $fillable = [
        'name', 'description', 'quantite', 'prix_unitaire',
        // legacy columns kept for backward compatibility with old rows
        'site', 'functionality', 'published', 'portfolio', 'category_id',
    ];

    public $timestamps = false;

    public function files(): MorphMany
	{
		return $this->morphMany(File::class, 'owner');
	}

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
    
    public function alerts(): HasMany
    {
        return $this->hasMany(StockAlert::class);
    }
    
    /**
     * Calculate the current stock level for this product in a specific warehouse
     *
     * @param int $warehouseId
     * @return float
     */
    public function getStockInWarehouse($warehouseId)
    {
        $movements = $this->stockMovements()->where('warehouse_id', $warehouseId)->get();
        
        $total = 0;
        foreach ($movements as $movement) {
            if ($movement->type === 'entree') {
                $total += $movement->quantite;
            } elseif ($movement->type === 'sortie') {
                $total -= $movement->quantite;
            }
        }
        
        return $total;
    }
}