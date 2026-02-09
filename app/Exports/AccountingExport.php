<?php

namespace App\Exports;

use App\Models\AccountingEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class AccountingExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = AccountingEntry::with(['debitAccount', 'journal', 'company', 'costCenter', 'project'])
            ->where('status', 'validee')
            ->orderBy('entry_date', 'asc')
            ->orderBy('entry_number');

        // Apply filters
        if (isset($this->filters['date_start']) && $this->filters['date_start']) {
            $query->whereDate('entry_date', '>=', $this->filters['date_start']);
        }
        
        if (isset($this->filters['date_end']) && $this->filters['date_end']) {
            $query->whereDate('entry_date', '<=', $this->filters['date_end']);
        }
        
        if (isset($this->filters['company_id']) && $this->filters['company_id']) {
            $query->where('company_id', $this->filters['company_id']);
        }
        
        if (isset($this->filters['account_id']) && $this->filters['account_id']) {
            $query->where(function($q) {
                $q->where('debit_account_id', $this->filters['account_id'])
                  ->orWhere('credit_account_id', $this->filters['account_id']);
            });
        }
        
        if (isset($this->filters['journal_id']) && $this->filters['journal_id']) {
            $query->where('journal_id', $this->filters['journal_id']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Journal',
            'N° Pièce',
            'Compte',
            'Libellé Compte',
            'Description',
            'Référence',
            'Débit',
            'Crédit',
            'Solde',
            'Centre de Coût',
            'Projet',
            'Société',
            'Statut',
            'Créé le',
            'Créé par'
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->entry_date->format('d/m/Y'),
            $entry->journal->code . ' - ' . $entry->journal->name,
            $entry->entry_number,
            $entry->debitAccount->code,
            $entry->debitAccount->name,
            $entry->description,
            $entry->reference_number,
            number_format($entry->debit_amount, 2, ',', ' '),
            number_format($entry->credit_amount, 2, ',', ' '),
            number_format($entry->debit_amount - $entry->credit_amount, 2, ',', ' '),
            $entry->costCenter ? $entry->costCenter->code . ' - ' . $entry->costCenter->name : '',
            $entry->project ? $entry->project->code . ' - ' . $entry->project->name : '',
            $entry->company->name,
            ucfirst($entry->status),
            $entry->created_at->format('d/m/Y H:i'),
            $entry->createdBy ? $entry->createdBy->name : ''
        ];
    }
}