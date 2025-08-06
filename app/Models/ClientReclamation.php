<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientReclamation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'type_reclamation', // produit défectueux, retard livraison, erreur facturation, etc.
        'description',
        'statut', // ouverte, en cours, résolue
        'agent_id', // utilisateur assigné à la réclamation
        'date_resolution',
        'solution',
        'commentaires',
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}