<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAlert extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'seuil_minimum',
        'seuil_securite',
        'alerte_active',
        'email_notification', // Changed from email_notifications to match the DB column
        'created_by'
    ];

    protected $casts = [
        'alerte_active' => 'boolean',
        'seuil_minimum' => 'decimal:2',
        'seuil_securite' => 'decimal:2'
    ];

    // Ensure the correct primary key is used for route model binding
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
