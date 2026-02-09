<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'type',
        'solde_actuel',
        'est_ouverte',
        'entity_type',
        'entity_id'
    ];

    protected $casts = [
        'est_ouverte' => 'boolean',
        'solde_actuel' => 'decimal:2'
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function sessions()
    {
        return $this->hasMany(CashSession::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function currentSession()
    {
        return $this->sessions()->whereNull('date_fermeture')->first();
    }

    public function isOpen()
    {
        return $this->est_ouverte;
    }
}