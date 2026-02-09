<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'theoretical_quantity',
        'actual_quantity',
        'difference',
        'notes'
    ];

    public function inventory()
    {
        return $this->belongsTo(StockInventory::class, 'inventory_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}