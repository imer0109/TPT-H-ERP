<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CashTransaction extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'cash_register_id',
        'cash_session_id',
        'user_id',
        'numero_transaction',
        'type',
        'montant',
        'libelle',
        'nature_operation',
        'mode_paiement',
        'justificatif',
        'projet',
        'champs_personnalises',
        'validateur_id',
        'date_validation'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_validation' => 'datetime',
        'champs_personnalises' => 'json'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function cashSession()
    {
        return $this->belongsTo(CashSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    public function tiers()
    {
        return $this->morphTo();
    }

    public function operation()
    {
        return $this->morphTo();
    }

    public function isValidated()
    {
        return !is_null($this->validateur_id) && !is_null($this->date_validation);
    }

    public function generateTransactionNumber()
    {
        $prefix = $this->type === 'encaissement' ? 'ENC' : 'DEC';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . '-' . $date . '-' . $random;
    }
}
