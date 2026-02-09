<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyTransaction extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'loyalty_card_id',
        'points',
        'type', // earned, redeemed
        'description',
        'transactionable_id',
        'transactionable_type'
    ];
    
    protected $casts = [
        'points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    public function loyaltyCard()
    {
        return $this->belongsTo(LoyaltyCard::class);
    }
    
    public function transactionable()
    {
        return $this->morphTo();
    }
}