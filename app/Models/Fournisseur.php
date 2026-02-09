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
        'agency_id',
        'nom',
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
        'iban',
        'numero_compte',
        'devise',
        'condition_reglement',
        'delai_paiement',
        'plafond_credit',
        'date_debut_relation',
        'date_fin_relation',
        'note_moyenne',
        'nombre_evaluations',
        'derniere_activite',
        'est_actif',
    ];

    /**
     * Les attributs qui doivent être mutés en dates.
     *
     * @var array
     */
    protected $dates = [
        'date_debut_relation',
        'date_fin_relation',
        'derniere_activite',
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
        'note_moyenne' => 'decimal:2',
        'nombre_evaluations' => 'integer',
        'est_actif' => 'boolean',
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
        return $this->belongsTo(Company::class, 'societe_id');
    }

    /**
     * Relation avec l'agence.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    /**
     * Relation avec les documents.
     */
    public function documents()
    {
        return $this->hasMany(FournisseurDocument::class, 'fournisseur_id');
    }

    /**
     * Relation avec les commandes fournisseurs.
     */
    public function supplierOrders()
    {
        return $this->hasMany(SupplierOrder::class, 'fournisseur_id');
    }

    /**
     * Relation avec les livraisons.
     */
    public function supplierDeliveries()
    {
        return $this->hasMany(SupplierDelivery::class, 'fournisseur_id');
    }

    /**
     * Relation avec les paiements.
     */
    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class, 'fournisseur_id');
    }

    /**
     * Relation avec les factures.
     */
    public function supplierInvoices()
    {
        return $this->hasMany(SupplierInvoice::class, 'fournisseur_id');
    }

    /**
     * Relation avec les réclamations.
     */
    public function supplierIssues()
    {
        return $this->hasMany(SupplierIssue::class, 'fournisseur_id');
    }

    /**
     * Relation avec les évaluations.
     */
    public function supplierRatings()
    {
        return $this->hasMany(SupplierRating::class, 'fournisseur_id');
    }

    /**
     * Relation avec les contrats.
     */
    public function supplierContracts()
    {
        return $this->hasMany(SupplierContract::class, 'fournisseur_id');
    }

    /**
     * Relation avec les intégrations.
     */
    public function supplierIntegrations()
    {
        return $this->hasMany(SupplierIntegration::class, 'fournisseur_id');
    }

    /**
     * Obtenir la note moyenne du fournisseur.
     */
    public function getAverageRatingAttribute()
    {
        return $this->supplierRatings()->avg('overall_score');
    }

    /**
     * Obtenir le nombre d'évaluations.
     */
    public function getRatingCountAttribute()
    {
        return $this->supplierRatings()->count();
    }

    /**
     * Obtenir les contrats actifs.
     */
    public function activeContracts()
    {
        return $this->supplierContracts()->where('status', 'active');
    }

    /**
     * Obtenir les contrats expirant bientôt.
     */
    public function expiringContracts()
    {
        return $this->supplierContracts()
            ->where('status', 'active')
            ->where('end_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now());
    }

    /**
     * Obtenir le montant total des commandes.
     */
    public function getTotalOrdersAmountAttribute()
    {
        return $this->supplierOrders()->sum('montant_ttc');
    }

    /**
     * Obtenir le montant total des paiements.
     */
    public function getTotalPaymentsAmountAttribute()
    {
        return $this->supplierPayments()->sum('montant');
    }

    /**
     * Obtenir le solde du fournisseur.
     */
    public function getBalanceAttribute()
    {
        return $this->total_orders_amount - $this->total_payments_amount;
    }

    /**
     * Obtenir le nombre de commandes.
     */
    public function getOrdersCountAttribute()
    {
        return $this->supplierOrders()->count();
    }

    /**
     * Obtenir le nombre de livraisons.
     */
    public function getDeliveriesCountAttribute()
    {
        return $this->supplierDeliveries()->count();
    }

    /**
     * Obtenir le nombre de réclamations.
     */
    public function getIssuesCountAttribute()
    {
        return $this->supplierIssues()->count();
    }

    /**
     * Obtenir le nombre de contrats.
     */
    public function getContractsCountAttribute()
    {
        return $this->supplierContracts()->count();
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
                  ->orWhere('telephone', 'like', "%{$terme}%")
                  ->orWhere('email', 'like', "%{$terme}%");
            });
        }
        return $query;
    }

    /**
     * Scope pour les fournisseurs à risque.
     */
    public function scopeARisque($query)
    {
        return $query->whereHas('supplierIssues', function($q) {
            $q->where('statut', 'open');
        })->orWhereHas('supplierOrders', function($q) {
            $q->where('statut', 'pending')
              ->where('date_livraison_prevue', '<', now());
        });
    }

    /**
     * Accesseur pour le nom de la société.
     */
    public function getNomSocieteAttribute()
    {
        return $this->societe ? $this->societe->raison_sociale : 'N/A';
    }

    public function getRaisonSocialeAttribute()
    {
        return $this->nom;
    }

    /**
     * Accesseur pour le statut formaté.
     */
    public function getStatutFormateAttribute()
    {
        return $this->statut == 'actif' ? 'Actif' : 'Inactif';
    }

    /**
     * Accesseur pour le type formaté.
     */
    public function getTypeFormateAttribute()
    {
        $types = [
            'personne_physique' => 'Personne Physique',
            'entreprise' => 'Entreprise',
            'institution' => 'Institution'
        ];
        return $types[$this->type] ?? $this->type;
    }

    /**
     * Accesseur pour l'activité formatée.
     */
    public function getActiviteFormateeAttribute()
    {
        $activites = [
            'transport' => 'Transport',
            'logistique' => 'Logistique',
            'matieres_premieres' => 'Matières Premières',
            'services' => 'Services',
            'autre' => 'Autre'
        ];
        return $activites[$this->activite] ?? $this->activite;
    }

    /**
     * Accesseur pour la condition de règlement formatée.
     */
    public function getConditionReglementFormateeAttribute()
    {
        $conditions = [
            'comptant' => 'Comptant',
            'credit' => 'À crédit'
        ];
        return $conditions[$this->condition_reglement] ?? $this->condition_reglement;
    }

    /**
     * Accesseur pour la devise formatée.
     */
    public function getDeviseFormateeAttribute()
    {
        $devises = [
            'XAF' => 'Franc CFA',
            'EUR' => 'Euro',
            'USD' => 'Dollar Américain'
        ];
        return $devises[$this->devise] ?? $this->devise;
    }
}