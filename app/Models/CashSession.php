<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_id',
        'user_id',
        'solde_initial',
        'solde_final',
        'date_ouverture',
        'date_fermeture',
        'justificatif_fermeture',
        'commentaire'
    ];

    protected $casts = [
        'date_ouverture' => 'datetime',
        'date_fermeture' => 'datetime',
        'solde_initial' => 'decimal:2',
        'solde_final' => 'decimal:2'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function isOpen()
    {
        return is_null($this->date_fermeture);
    }

    public function calculateBalance()
    {
        $encaissements = $this->transactions()
            ->where('type', 'encaissement')
            ->sum('montant');

        $decaissements = $this->transactions()
            ->where('type', 'decaissement')
            ->sum('montant');

        return $this->solde_initial + $encaissements - $decaissements;
    }
}
