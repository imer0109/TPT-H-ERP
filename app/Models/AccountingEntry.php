<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'agency_id',
        'journal_id',
        'entry_number',
        'entry_date',
        'reference_type', // 'caisse', 'achat', 'vente', 'salaire', 'transfert', 'manuel'
        'reference_id',
        'reference_number',
        'description',
        'debit_account_id',
        'credit_account_id',
        'debit_amount',
        'credit_amount',
        'currency',
        'exchange_rate',
        'cost_center_id',
        'project_id',
        'vat_amount',
        'vat_rate',
        'status', // 'brouillon', 'validee', 'exportee', 'cloturee'
        'validated_by',
        'validated_at',
        'exported_at',
        'created_by',
        'notes'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'validated_at' => 'datetime',
        'exported_at' => 'datetime',
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'exchange_rate' => 'decimal:4'
    ];

    const REFERENCE_TYPES = [
        'caisse' => 'Caisse',
        'achat' => 'Achat',
        'vente' => 'Vente',
        'salaire' => 'Salaire',
        'transfert' => 'Transfert Inter-sociétés',
        'amortissement' => 'Amortissement',
        'manuel' => 'Écriture Manuelle',
        'banque' => 'Banque',
        'immobilisation' => 'Immobilisation'
    ];

    const STATUTS = [
        'brouillon' => 'Brouillon',
        'validee' => 'Validée',
        'exportee' => 'Exportée',
        'cloturee' => 'Clôturée'
    ];

    const CURRENCIES = [
        'XOF' => 'Franc CFA (XOF)',
        'EUR' => 'Euro (EUR)',
        'USD' => 'Dollar US (USD)'
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

    public function journal(): BelongsTo
    {
        return $this->belongsTo(AccountingJournal::class, 'journal_id');
    }

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'credit_account_id');
    }

    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Polymorphic relations for references
    public function referenceable()
    {
        return $this->morphTo('reference');
    }

    // Utility methods
    public function isBalanced(): bool
    {
        return $this->debit_amount == $this->credit_amount;
    }

    public function canBeValidated(): bool
    {
        return $this->status === 'brouillon' && $this->isBalanced();
    }

    public function canBeModified(): bool
    {
        return in_array($this->status, ['brouillon']);
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->status, ['brouillon']);
    }

    public function getAmountInBaseCurrency(): float
    {
        if ($this->currency === 'XOF' || $this->exchange_rate == 1) {
            return $this->debit_amount ?: $this->credit_amount;
        }
        
        return ($this->debit_amount ?: $this->credit_amount) * $this->exchange_rate;
    }

    public function generateEntryNumber(): string
    {
        $company = $this->company;
        $year = $this->entry_date->year;
        $month = $this->entry_date->format('m');
        
        $lastEntry = self::where('company_id', $this->company_id)
                        ->whereYear('entry_date', $year)
                        ->whereMonth('entry_date', $this->entry_date->month)
                        ->orderBy('entry_number', 'desc')
                        ->first();
        
        if ($lastEntry && preg_match('/(\d+)$/', $lastEntry->entry_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        return sprintf('%s-%s%s-%06d', 
            $company->code ?? 'TPT', 
            $year, 
            $month, 
            $sequence
        );
    }

    // Scopes
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByJournal($query, $journalId)
    {
        return $query->where('journal_id', $journalId);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validee');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'brouillon');
    }

    public function scopeByAccount($query, $accountId)
    {
        return $query->where(function ($q) use ($accountId) {
            $q->where('debit_account_id', $accountId)
              ->orWhere('credit_account_id', $accountId);
        });
    }

    public function scopeDebits($query)
    {
        return $query->where('debit_amount', '>', 0);
    }

    public function scopeCredits($query)
    {
        return $query->where('credit_amount', '>', 0);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->entry_number)) {
                $model->entry_number = $model->generateEntryNumber();
            }
            
            if (empty($model->currency)) {
                $model->currency = 'XOF';
            }
            
            if (empty($model->exchange_rate)) {
                $model->exchange_rate = 1.0;
            }
            
            if (empty($model->status)) {
                $model->status = 'brouillon';
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === 'validee') {
                $model->validated_by = auth()->id();
                $model->validated_at = now();
            }
        });
    }
}