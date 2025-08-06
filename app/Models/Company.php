<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'raison_sociale',
        'type',
        'niu',
        'rccm',
        'regime_fiscal',
        'secteur_activite',
        'devise',
        'pays',
        'ville',
        'siege_social',
        'email',
        'telephone',
        'whatsapp',
        'site_web',
        'logo',
        'active',
        'parent_id'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    public function filiales()
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    public function agencies()
    {
        return $this->hasMany(Agency::class);
    }
    
    public function cashRegisters()
    {
        return $this->morphMany(CashRegister::class, 'entity');
    }
}