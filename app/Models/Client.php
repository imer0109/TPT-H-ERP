<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'code_client',
        'company_id',
        'agency_id',
        'nom_raison_sociale',
        'type_client', // particulier, entreprise, administration, distributeur
        'telephone',
        'whatsapp',
        'email',
        'adresse',
        'ville',
        'contact_principal',
        'canal_acquisition', // commerce direct, web, recommandé, etc.
        'referent_commercial_id', // utilisateur interne
        'type_relation', // client comptant, client à crédit, client VIP
        'delai_paiement',
        'plafond_credit',
        'mode_paiement_prefere',
        'statut', // actif, inactif, suspendu
        'categorie', // Or, Argent, Bronze
        'site_web',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'owner');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function referentCommercial()
    {
        return $this->belongsTo(User::class, 'referent_commercial_id');
    }

    public function transactions()
    {
        return $this->morphMany(CashTransaction::class, 'tiers');
    }

    public function reclamations()
    {
        return $this->hasMany(ClientReclamation::class);
    }

    public function interactions()
    {
        return $this->hasMany(ClientInteraction::class);
    }

    public function integrations()
    {
        return $this->hasMany(ClientIntegration::class);
    }

    public function loyaltyCard()
    {
        return $this->hasOne(LoyaltyCard::class);
    }

    // Méthode pour générer un code client unique
    public static function generateUniqueCode()
    {
        $prefix = 'CLI';
        $randomPart = strtoupper(substr(uniqid(), -6));
        $code = $prefix . $randomPart;
        
        // Vérifier si le code existe déjà
        while (self::where('code_client', $code)->exists()) {
            $randomPart = strtoupper(substr(uniqid(), -6));
            $code = $prefix . $randomPart;
        }
        
        return $code;
    }

    // Méthode pour calculer l'encours client
    public function getEncours()
    {
        // Calculer le montant total dû par le client
        $totalEncaissements = $this->transactions()->where('type', 'encaissement')->sum('montant');
        $totalDecaissements = $this->transactions()->where('type', 'decaissement')->sum('montant');
        return $totalEncaissements - $totalDecaissements;
    }

    // Méthode pour obtenir le délai moyen de règlement
    public function getDelaiMoyenReglement()
    {
        // Calculer le délai moyen de règlement
        $transactions = $this->transactions()->where('type', 'encaissement')->get();
        
        if ($transactions->isEmpty()) {
            return 0;
        }
        
        $totalDays = 0;
        $count = 0;
        
        foreach ($transactions as $transaction) {
            // Pour simplifier, nous utilisons la date de création comme date d'émission
            // et la date actuelle comme date de paiement
            $days = $transaction->created_at->diffInDays(now());
            $totalDays += $days;
            $count++;
        }
        
        return $count > 0 ? round($totalDays / $count, 2) : 0;
    }

    // Méthode pour obtenir le nombre de factures impayées
    public function getNombreFacturesImpayees()
    {
        // Compter le nombre de transactions impayées (encaissements sans décaissements correspondants)
        // Pour simplifier, nous considérons comme impayée toute transaction de plus de 30 jours
        return $this->transactions()
            ->where('type', 'encaissement')
            ->where('created_at', '<=', now()->subDays(30))
            ->count();
    }
}
