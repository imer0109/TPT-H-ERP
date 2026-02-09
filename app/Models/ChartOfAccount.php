<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'code',
        'label',
        'parent_id',
        'level',
        'account_type',
        'account_nature', // 'debit' or 'credit'
        'is_active',
        'is_auxiliary',
        'aux_type', // 'client', 'fournisseur', 'employe', 'immobilisation', etc.
        'vat_applicable',
        'description',
        'syscohada_code',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_auxiliary' => 'boolean',
        'vat_applicable' => 'boolean'
    ];

    const ACCOUNT_TYPES = [
        'classe' => 'Classe',
        'sous_classe' => 'Sous-classe',
        'compte' => 'Compte',
        'sous_compte' => 'Sous-compte'
    ];

    const ACCOUNT_NATURES = [
        'debit' => 'Débit',
        'credit' => 'Crédit'
    ];

    const AUX_TYPES = [
        'client' => 'Client',
        'fournisseur' => 'Fournisseur',
        'employe' => 'Employé',
        'immobilisation' => 'Immobilisation',
        'tva' => 'TVA',
        'charges_sociales' => 'Charges Sociales',
        'banque' => 'Banque',
        'caisse' => 'Caisse'
    ];

    // Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountingEntries(): HasMany
    {
        return $this->hasMany(AccountingEntry::class, 'account_id');
    }

    public function debitEntries(): HasMany
    {
        return $this->hasMany(AccountingEntry::class, 'debit_account_id');
    }

    public function creditEntries(): HasMany
    {
        return $this->hasMany(AccountingEntry::class, 'credit_account_id');
    }

    // Utility methods
    public function getFullCodeAttribute(): string
    {
        $codes = collect([$this->code]);
        $parent = $this->parent;
        
        while ($parent) {
            $codes->prepend($parent->code);
            $parent = $parent->parent;
        }
        
        return $codes->join('.');
    }

    public function getFullLabelAttribute(): string
    {
        $labels = collect([$this->label]);
        $parent = $this->parent;
        
        while ($parent) {
            $labels->prepend($parent->label);
            $parent = $parent->parent;
        }
        
        return $labels->join(' > ');
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    public function canBeDeleted(): bool
    {
        return $this->children()->count() === 0 && 
               $this->accountingEntries()->count() === 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeAuxiliary($query, $auxType = null)
    {
        $query = $query->where('is_auxiliary', true);
        
        if ($auxType) {
            $query->where('aux_type', $auxType);
        }
        
        return $query;
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeLeaves($query)
    {
        return $query->whereDoesntHave('children');
    }

    // Static methods
    public static function generateNextCode($parentId = null, $companyId = null): string
    {
        if ($parentId) {
            $parent = self::find($parentId);
            $siblings = self::where('parent_id', $parentId)->orderBy('code', 'desc')->first();
            
            if ($siblings) {
                $lastCode = intval($siblings->code);
                return str_pad($lastCode + 1, strlen($siblings->code), '0', STR_PAD_LEFT);
            } else {
                return $parent->code . '01';
            }
        } else {
            // Root account
            $lastRoot = self::whereNull('parent_id')
                          ->where('company_id', $companyId)
                          ->orderBy('code', 'desc')
                          ->first();
            
            if ($lastRoot) {
                return (string)((int)$lastRoot->code + 1);
            } else {
                return '1';
            }
        }
    }

    public static function buildTreeArray($companyId = null, $parentId = null)
    {
        $query = self::with('childrenRecursive');
        
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        
        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }
        
        return $query->orderBy('code')->get();
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->level)) {
                $model->level = $model->parent ? $model->parent->level + 1 : 1;
            }
        });

        static::deleting(function ($model) {
            if (!$model->canBeDeleted()) {
                throw new \Exception('Cannot delete account with children or accounting entries');
            }
        });
    }
}