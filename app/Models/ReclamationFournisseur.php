<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReclamationFournisseur extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'reclamation_fournisseurs';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'fournisseur_id',
        'commande_id',
        'livraison_id',
        'type',
        'objet',
        'description',
        'date_reclamation',
        'statut',
        'priorite',
        'date_resolution',
        'resolution',
        'user_id',
        'responsable_id',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_reclamation',
        'date_resolution',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Les types de réclamations valides.
     *
     * @var array
     */
    public static $types = [
        'qualite' => 'Problème de qualité',
        'livraison' => 'Problème de livraison',
        'facturation' => 'Problème de facturation',
        'autre' => 'Autre',
    ];

    /**
     * Les statuts de réclamations valides.
     *
     * @var array
     */
    public static $statuts = [
        'ouverte' => 'Ouverte',
        'en_cours' => 'En cours de traitement',
        'resolue' => 'Résolue',
        'fermee' => 'Fermée sans résolution',
    ];

    /**
     * Les niveaux de priorité valides.
     *
     * @var array
     */
    public static $priorites = [
        'basse' => 'Basse',
        'moyenne' => 'Moyenne',
        'haute' => 'Haute',
        'critique' => 'Critique',
    ];

    /**
     * Relation avec le fournisseur.
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * Relation avec la commande.
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    /**
     * Relation avec la livraison.
     */
    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'livraison_id');
    }

    /**
     * Relation avec l'utilisateur qui a créé la réclamation.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'utilisateur responsable de la réclamation.
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relation avec les documents.
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Relation avec les commentaires.
     */
    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable')->orderBy('created_at', 'desc');
    }

    /**
     * Vérifier si la réclamation est résolue.
     *
     * @return bool
     */
    public function isResolved()
    {
        return $this->statut === 'resolue';
    }

    /**
     * Vérifier si la réclamation est fermée.
     *
     * @return bool
     */
    public function isClosed()
    {
        return in_array($this->statut, ['resolue', 'fermee']);
    }

    /**
     * Scope pour filtrer par statut.
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par priorité.
     */
    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    /**
     * Scope pour filtrer par type.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer les réclamations ouvertes.
     */
    public function scopeOuvertes($query)
    {
        return $query->whereIn('statut', ['ouverte', 'en_cours']);
    }

    /**
     * Scope pour filtrer les réclamations fermées.
     */
    public function scopeFermees($query)
    {
        return $query->whereIn('statut', ['resolue', 'fermee']);
    }

    /**
     * Scope pour rechercher des réclamations.
     */
    public function scopeRecherche($query, $terme)
    {
        if ($terme) {
            return $query->where(function ($q) use ($terme) {
                $q->where('objet', 'like', "%{$terme}%")
                  ->orWhere('description', 'like', "%{$terme}%");
            });
        }
        
        return $query;
    }
}