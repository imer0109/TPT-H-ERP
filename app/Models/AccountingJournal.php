<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingJournal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'journal_type',
        'description',
        'is_active',
        'requires_validation',
        'auto_numbering',
        'number_prefix',
        'default_debit_account_id',
        'default_credit_account_id',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_validation' => 'boolean',
        'auto_numbering' => 'boolean'
    ];

    const JOURNAL_TYPES = [
        'caisse' => 'Journal de Caisse',
        'banque' => 'Journal de Banque',
        'achat' => 'Journal d\'Achats',
        'vente' => 'Journal de Ventes',
        'salaire' => 'Journal de Paie',
        'general' => 'Journal Général',
        'od' => 'Opérations Diverses',
        'immobilisation' => 'Immobilisations',
        'amortissement' => 'Amortissements',
        'transfert' => 'Transferts Inter-sociétés'
    ];

    // Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function defaultDebitAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'default_debit_account_id');
    }

    public function defaultCreditAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'default_credit_account_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(AccountingEntry::class, 'journal_id');
    }

    // Utility methods
    public function getTotalDebitAmount($startDate = null, $endDate = null): float
    {
        $query = $this->entries()->sum('debit_amount');
        
        if ($startDate && $endDate) {
            $query = $this->entries()
                         ->whereBetween('entry_date', [$startDate, $endDate])
                         ->sum('debit_amount');
        }
        
        return $query;
    }

    public function getTotalCreditAmount($startDate = null, $endDate = null): float
    {
        $query = $this->entries()->sum('credit_amount');
        
        if ($startDate && $endDate) {
            $query = $this->entries()
                         ->whereBetween('entry_date', [$startDate, $endDate])
                         ->sum('credit_amount');
        }
        
        return $query;
    }

    public function getBalance($startDate = null, $endDate = null): float
    {
        return $this->getTotalDebitAmount($startDate, $endDate) - 
               $this->getTotalCreditAmount($startDate, $endDate);
    }

    public function isBalanced($startDate = null, $endDate = null): bool
    {
        return abs($this->getBalance($startDate, $endDate)) < 0.01;
    }

    public function getEntriesCount($status = null, $startDate = null, $endDate = null): int
    {
        $query = $this->entries();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('entry_date', [$startDate, $endDate]);
        }
        
        return $query->count();
    }

    public function getLastEntryNumber(): ?string
    {
        $lastEntry = $this->entries()
                         ->orderBy('entry_number', 'desc')
                         ->first();
        
        return $lastEntry ? $lastEntry->entry_number : null;
    }

    public function generateNextEntryNumber(): string
    {
        if (!$this->auto_numbering) {
            return '';
        }
        
        $year = date('Y');
        $month = date('m');
        
        $lastEntry = $this->entries()
                         ->whereYear('entry_date', $year)
                         ->whereMonth('entry_date', $month)
                         ->orderBy('entry_number', 'desc')
                         ->first();
        
        if ($lastEntry && preg_match('/(\d+)$/', $lastEntry->entry_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        $prefix = $this->number_prefix ?: $this->code;
        
        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $sequence);
    }

    public function canBeDeleted(): bool
    {
        return $this->entries()->count() === 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('journal_type', $type);
    }

    public function scopeRequiringValidation($query)
    {
        return $query->where('requires_validation', true);
    }

    // Static methods
    public static function getDefaultJournals(): array
    {
        return [
            [
                'code' => 'CA',
                'name' => 'Journal de Caisse',
                'journal_type' => 'caisse',
                'requires_validation' => false,
                'auto_numbering' => true,
                'number_prefix' => 'CA'
            ],
            [
                'code' => 'BQ',
                'name' => 'Journal de Banque',
                'journal_type' => 'banque',
                'requires_validation' => true,
                'auto_numbering' => true,
                'number_prefix' => 'BQ'
            ],
            [
                'code' => 'AC',
                'name' => 'Journal d\'Achats',
                'journal_type' => 'achat',
                'requires_validation' => true,
                'auto_numbering' => true,
                'number_prefix' => 'AC'
            ],
            [
                'code' => 'VT',
                'name' => 'Journal de Ventes',
                'journal_type' => 'vente',
                'requires_validation' => false,
                'auto_numbering' => true,
                'number_prefix' => 'VT'
            ],
            [
                'code' => 'PA',
                'name' => 'Journal de Paie',
                'journal_type' => 'salaire',
                'requires_validation' => true,
                'auto_numbering' => true,
                'number_prefix' => 'PA'
            ],
            [
                'code' => 'OD',
                'name' => 'Opérations Diverses',
                'journal_type' => 'od',
                'requires_validation' => true,
                'auto_numbering' => true,
                'number_prefix' => 'OD'
            ]
        ];
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->is_active)) {
                $model->is_active = true;
            }
            
            if (empty($model->auto_numbering)) {
                $model->auto_numbering = true;
            }
        });

        static::deleting(function ($model) {
            if (!$model->canBeDeleted()) {
                throw new \Exception('Cannot delete journal with accounting entries');
            }
        });
    }
}