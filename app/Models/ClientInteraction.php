<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientInteraction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id', // utilisateur qui a enregistré l'interaction
        'type_interaction', // appel téléphonique, visite commerciale, email, etc.
        'description',
        'date_interaction',
        'resultat',
        'suivi_necessaire',
        'date_suivi',
        'campagne_id', // lien optionnel vers une campagne marketing
    ];

    protected $casts = [
        'date_interaction' => 'datetime',
        'date_suivi' => 'datetime',
        'suivi_necessaire' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campagne()
    {
        return $this->belongsTo(Campagne::class, 'campagne_id');
    }
}