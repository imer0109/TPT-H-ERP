<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyCard extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'client_id',
        'card_number',
        'points',
        'tier', // bronze, silver, gold, platinum
        'status', // active, inactive, suspended
        'issued_at',
        'expires_at',
        'last_transaction_at'
    ];
    
    protected $casts = [
        'points' => 'integer',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_transaction_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }
    
    // Méthode pour générer un numéro de carte unique
    public static function generateCardNumber()
    {
        $prefix = 'LC';
        $randomPart = strtoupper(substr(uniqid(), -8));
        $cardNumber = $prefix . $randomPart;
        
        // Vérifier si le numéro existe déjà
        while (self::where('card_number', $cardNumber)->exists()) {
            $randomPart = strtoupper(substr(uniqid(), -8));
            $cardNumber = $prefix . $randomPart;
        }
        
        return $cardNumber;
    }
    
    // Méthode pour ajouter des points
    public function addPoints($points)
    {
        $this->points += $points;
        $this->last_transaction_at = now();
        $this->updateTier();
        $this->save();
        
        // Enregistrer la transaction
        $this->transactions()->create([
            'points' => $points,
            'type' => 'earned',
            'description' => 'Points earned'
        ]);
    }
    
    // Méthode pour utiliser des points
    public function usePoints($points)
    {
        if ($this->points >= $points) {
            $this->points -= $points;
            $this->last_transaction_at = now();
            $this->updateTier();
            $this->save();
            
            // Enregistrer la transaction
            $this->transactions()->create([
                'points' => $points,
                'type' => 'redeemed',
                'description' => 'Points redeemed'
            ]);
            
            return true;
        }
        
        return false;
    }
    
    // Méthode pour mettre à jour le niveau de la carte selon les points
    public function updateTier()
    {
        if ($this->points >= 5000) {
            $this->tier = 'platinum';
        } elseif ($this->points >= 2000) {
            $this->tier = 'gold';
        } elseif ($this->points >= 500) {
            $this->tier = 'silver';
        } else {
            $this->tier = 'bronze';
        }
    }
}