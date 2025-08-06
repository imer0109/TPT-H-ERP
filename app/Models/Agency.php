<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'code_unique',
        'adresse',
        'responsable_id',
        'zone_geographique',
        'latitude',
        'longitude',
        'statut',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    
    public function cashRegisters()
    {
        return $this->morphMany(CashRegister::class, 'entity');
    }
}