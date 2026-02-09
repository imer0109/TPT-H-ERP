<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'company_id',
        'agency_id',
        'requested_by',
        'nature_achat', // 'Bien' ou 'Service'
        'designation',
        'justification',
        'date_demande',
        'date_echeance_souhaitee',
        'statut', // 'Brouillon', 'En attente', 'Validée', 'Refusée', 'Convertie en BOC', 'Annulée'
        'prix_estime_total',
        'fournisseur_suggere_id',
        'validated_by',
        'validated_at',
        'validation_comments',
        'notes',
        'validation_workflow_id', // New field to link to validation workflow
        'validation_request_id'   // New field to link to validation request
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_echeance_souhaitee' => 'date',
        'validated_at' => 'datetime',
        'prix_estime_total' => 'decimal:2'
    ];

    const STATUTS = [
        'Brouillon' => 'Brouillon',
        'En attente' => 'En attente',
        'Validée' => 'Validée',
        'Refusée' => 'Refusée',
        'Convertie en BOC' => 'Convertie en BOC',
        'Annulée' => 'Annulée'
    ];

    const NATURE_ACHATS = [
        'Bien' => 'Bien',
        'Service' => 'Service'
    ];

    // Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function fournisseurSuggere(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_suggere_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function validations(): HasMany
    {
        return $this->hasMany(PurchaseValidation::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'related_id')->where('related_type', 'purchase_request');
    }

    public function supplierOrders(): HasMany
    {
        return $this->hasMany(SupplierOrder::class, 'purchase_request_id');
    }

    // New relations for advanced validation workflow
    public function validationWorkflow(): BelongsTo
    {
        return $this->belongsTo(ValidationWorkflow::class);
    }

    public function validationRequest(): BelongsTo
    {
        return $this->belongsTo(ValidationRequest::class);
    }

    // Méthodes utilitaires
    public function canBeValidated(): bool
    {
        return in_array($this->statut, ['En attente']);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->statut, ['Brouillon', 'Refusée']);
    }

    public function canBeConverted(): bool
    {
        return $this->statut === 'Validée';
    }

    public function generateCode(): string
    {
        $company_code = $this->company->code ?? 'TPT';
        $year = date('Y');
        $sequence = PurchaseRequest::whereYear('created_at', $year)->count() + 1;
        
        return sprintf('DA-%s-%s-%04d', $company_code, $year, $sequence);
    }

    // New method to determine which validation workflow should be used
    public function determineValidationWorkflow()
    {
        // Check if there's already a validation workflow assigned
        if ($this->validation_workflow_id) {
            return ValidationWorkflow::find($this->validation_workflow_id);
        }

        // Get the company-specific validation workflows for purchases
        $workflows = ValidationWorkflow::where('module', 'purchases')
            ->where('entity_type', PurchaseRequest::class)
            ->where('company_id', $this->company_id)
            ->where('is_active', true)
            ->get();

        // Check each workflow to see if its conditions are met
        foreach ($workflows as $workflow) {
            if ($workflow->conditionsAreMet($this)) {
                return $workflow;
            }
        }

        // Return null if no workflow matches
        return null;
    }

    // Method to create a validation request using the advanced workflow system
    public function createAdvancedValidationRequest($requestedBy, $reason = null)
    {
        $workflow = $this->determineValidationWorkflow();
        
        if ($workflow) {
            $validationRequest = $workflow->createValidationRequest($this, $requestedBy, $reason);
            
            // Update the purchase request with the validation workflow and request IDs
            $this->update([
                'validation_workflow_id' => $workflow->id,
                'validation_request_id' => $validationRequest->id
            ]);
            
            return $validationRequest;
        }
        
        return null;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = $model->generateCode();
            }
            if (empty($model->date_demande)) {
                $model->date_demande = now();
            }
            if (empty($model->statut)) {
                $model->statut = 'Brouillon';
            }
        });
    }
}