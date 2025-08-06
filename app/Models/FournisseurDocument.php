<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FournisseurDocument extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'fournisseur_documents';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'fournisseur_id',
        'type',
        'nom',
        'chemin',
        'taille',
        'extension',
        'date_expiration',
        'description',
        'uploaded_by',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_expiration',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Les types de documents valides.
     *
     * @var array
     */
    public static $types = [
        'contrat' => 'Contrat',
        'rccm' => 'RCCM',
        'attestation_fiscale' => 'Attestation Fiscale',
        'autre' => 'Autre',
    ];

    /**
     * Relation avec le fournisseur.
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * Relation avec l'utilisateur qui a téléchargé le document.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Obtenir l'URL de téléchargement du document.
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        return route('fournisseurs.documents.download', $this->id);
    }

    /**
     * Obtenir l'URL de visualisation du document.
     *
     * @return string
     */
    public function getViewUrl()
    {
        return route('fournisseurs.documents.view', $this->id);
    }

    /**
     * Vérifier si le document est expiré.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->date_expiration) {
            return false;
        }

        return $this->date_expiration->isPast();
    }

    /**
     * Vérifier si le document expire bientôt (dans les 30 jours).
     *
     * @return bool
     */
    public function isExpiringSoon()
    {
        if (!$this->date_expiration) {
            return false;
        }

        return $this->date_expiration->isFuture() && $this->date_expiration->diffInDays(now()) <= 30;
    }

    /**
     * Supprimer le fichier physique lors de la suppression du modèle.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            if ($document->chemin && Storage::exists($document->chemin)) {
                Storage::delete($document->chemin);
            }
        });
    }

    /**
     * Scope pour filtrer par type de document.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer les documents expirés.
     */
    public function scopeExpires($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    /**
     * Scope pour filtrer les documents qui expirent bientôt.
     */
    public function scopeExpirantBientot($query, $jours = 30)
    {
        return $query->where('date_expiration', '>', now())
                     ->where('date_expiration', '<=', now()->addDays($jours));
    }
}