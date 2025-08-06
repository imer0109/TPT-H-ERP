<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'type_document', // contrat, bon de commande, fiche d'ouverture, RCCM, NIU, etc.
        'chemin_fichier',
        'taille',
        'format',
        'description',
        'user_id', // utilisateur qui a téléchargé le document
        'documentable_id',
        'documentable_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}