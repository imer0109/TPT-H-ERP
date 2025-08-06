<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'fournisseurs';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'code_fournisseur',
        'societe_id',
        'raison_sociale',
        'type',
        'activite',
        'statut',
        'niu',
        'rccm',
        'cnss',
        'adresse',
        'pays',
        'ville',
        'telephone',
        'whatsapp',
        'email',
        'site_web',
        'contact_principal',
        'banque',
        'numero_compte',
        'devise',
        'condition_reglement',
        'delai_paiement',
        'plafond_credit',
        'date_debut_relation',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_debut_relation',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array
     */
    protected $casts = [
        'plafond_credit' => 'float',
        'delai_paiement' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement un code fournisseur lors de la création
        static::creating(function ($fournisseur) {
            if (empty($fournisseur->code_fournisseur)) {
                $fournisseur->code_fournisseur = self::generateFournisseurCode();
            }
        });
    }

    /**
     * Génère un code fournisseur unique.
     *
     * @return string
     */
    public static function generateFournisseurCode()
    {
        $prefix = 'FOUR-';
        $year = date('Y');
        $month = date('m');
        
        // Trouver le dernier code fournisseur pour ce mois
        $lastFournisseur = self::where('code_fournisseur', 'like', $prefix . $year . $month . '%')
            ->orderBy('code_fournisseur', 'desc')
            ->first();
        
        if ($lastFournisseur) {
            // Extraire le numéro séquentiel et l'incrémenter
            $lastCode = $lastFournisseur->code_fournisseur;
            $lastNumber = intval(substr($lastCode, -4));
            $newNumber = $lastNumber + 1;
        } else {
            // Premier fournisseur du mois
            $newNumber = 1;
        }
        
        // Formater le numéro séquentiel avec des zéros à gauche
        $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $month . $formattedNumber;
    }

    /**
     * Relation avec la société.
     */
    public function societe()
    {
        return $this->belongsTo(Societe::class, 'societe_id');
    }

    /**
     * Relation avec les documents.
     */
    public function documents()
    {
        return $this->hasMany(FournisseurDocument::class, 'fournisseur_id');
    }

    /**
     * Relation avec les commandes.
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'fournisseur_id');
    }

    /**
     * Relation avec les livraisons.
     */
    public function livraisons()
    {
        return $this->hasMany(Livraison::class, 'fournisseur_id');
    }

    /**
     * Relation avec les paiements.
     */
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'fournisseur_id');
    }

    /**
     * Relation avec les réclamations.
     */
    public function reclamations()
    {
        return $this->hasMany(ReclamationFournisseur::class, 'fournisseur_id');
    }

    /**
     * Scope pour filtrer les fournisseurs actifs.
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour filtrer les fournisseurs inactifs.
     */
    public function scopeInactifs($query)
    {
        return $query->where('statut', 'inactif');
    }

    /**
     * Scope pour filtrer par activité.
     */
    public function scopeParActivite($query, $activite)
    {
        return $query->where('activite', $activite);
    }

    /**
     * Scope pour rechercher des fournisseurs.
     */
    public function scopeRecherche($query, $terme)
    {
        if ($terme) {
            return $query->where(function ($q) use ($terme) {
                $q->where('raison_sociale', 'like', "%{$terme}%")
                  ->orWhere('code_fournisseur', 'like', "%{$terme}%")
                  ->orWhere('contact_principal', 'like', "%{$terme}%")
                  ->orWhere('telephone', 'like', "%{$terme}%")
                  ->orWhere('email', 'like', "%{$terme}%");
            });
        }
        
        return $query;
    }
}