<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\AccountingEntry;
use App\Models\AccountingJournal;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountingExport;
use PDF;

class AccountingExportController extends Controller
{
    public function __construct()
    {
        // Removed middleware call as auth middleware is applied in routes/web.php
    }

    /**
     * Export entries to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        $filename = 'accounting_entries_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new AccountingExport($filters), $filename);
    }

    /**
     * Export to SAGE format
     */
    public function exportSage(Request $request)
    {
        $entries = $this->getFilteredEntries($request);
        
        $content = "Format SAGE Export\n";
        $content .= "Date\tJournal\tCompte\tLibelle\tDebit\tCredit\tPiece\n";
        
        foreach ($entries as $entry) {
            $content .= sprintf(
                "%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\n",
                $entry->date->format('d/m/Y'),
                $entry->journal->code,
                $entry->account->code,
                $entry->description,
                $entry->debit,
                $entry->credit,
                $entry->piece_number
            );
        }
        
        $filename = 'export_sage_' . now()->format('Y-m-d_H-i-s') . '.txt';
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export to EBP format
     */
    public function exportEbp(Request $request)
    {
        $entries = $this->getFilteredEntries($request);
        
        $content = "EBP Export Format\n";
        $content .= "Date;Journal;Compte;Libelle;Debit;Credit;Piece\n";
        
        foreach ($entries as $entry) {
            $content .= sprintf(
                "%s;%s;%s;%s;%.2f;%.2f;%s\n",
                $entry->date->format('d/m/Y'),
                $entry->journal->code,
                $entry->account->code,
                str_replace(';', ',', $entry->description),
                $entry->debit,
                $entry->credit,
                $entry->piece_number
            );
        }
        
        $filename = 'export_ebp_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response($content)
            ->header('Content-Type', 'text/csv') 
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export to FEC format (Fichier des Écritures Comptables)
     */
    public function exportFec(Request $request)
    {
        $year = $request->year ?? now()->year;
        $company_id = $request->company_id;
        
        $entries = AccountingEntry::with(['account', 'journal', 'company'])
            ->where('status', 'validée')
            ->whereYear('date', $year);
            
        if ($company_id) {
            $entries->where('company_id', $company_id);
        }
        
        $entries = $entries->orderBy('date')->orderBy('piece_number')->get();
        
        $content = "";
        foreach ($entries as $entry) {
            $content .= sprintf(
                "%s|%s|%s|%s|%s|%s|%s|%s|%s|%s|%.2f|%.2f|%s|%s|%s|%s|%s|%s\n",
                $entry->journal->code,                    // JournalCode
                $entry->journal->name,                    // JournalLib
                $entry->piece_number,                     // EcritureNum
                $entry->date->format('Ymd'),             // EcritureDate
                $entry->account->code,                    // CompteNum
                $entry->account->name,                    // CompteLib
                '',                                       // CompAuxNum
                '',                                       // CompAuxLib
                $entry->reference ?? '',                 // PieceRef
                $entry->date->format('Ymd'),             // PieceDate
                $entry->debit,                           // Debit
                $entry->credit,                          // Credit
                'EUR',                                   // EcritureLet
                $entry->date->format('Ymd'),             // DateLet
                $entry->date->format('Ymd'),             // ValidDate
                $entry->debit,                           // Montantdevise
                'EUR',                                   // Idevise
                $entry->date->format('Ymd')              // DateRglt
            );
        }
        
        $filename = 'FEC_' . $year . '_' . now()->format('YmdHis') . '.txt';
        
        return response($content)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export chart of accounts to Excel
     */
    public function exportChartOfAccounts(Request $request)
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        
        $data = [];
        $data[] = ['Code', 'Nom', 'Type', 'Niveau', 'Parent', 'Actif'];
        
        foreach ($accounts as $account) {
            $data[] = [
                $account->code,
                $account->name,
                $account->type,
                $account->level,
                $account->parent ? $account->parent->code : '',
                $account->is_active ? 'Oui' : 'Non'
            ];
        }
        
        $filename = 'plan_comptable_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $data;
            
            public function __construct($data) {
                $this->data = $data;
            }
            
            public function array(): array {
                return $this->data;
            }
        }, $filename);
    }

    /**
     * Export trial balance to PDF
     */
    public function exportTrialBalancePdf(Request $request)
    {
        $date_end = $request->filled('date_end') ? $request->date_end : now()->format('Y-m-d');
        $company_id = $request->company_id;

        $query = ChartOfAccount::where('level', 'compte')
            ->orderBy('code');

        if ($company_id) {
            $query->whereHas('debitEntries', function($q) use ($company_id, $date_end) {
                $q->where('company_id', $company_id)
                  ->whereDate('entry_date', '<=', $date_end)
                  ->where('status', 'validee');
            })->orWhereHas('creditEntries', function($q) use ($company_id, $date_end) {
                $q->where('company_id', $company_id)
                  ->whereDate('entry_date', '<=', $date_end)
                  ->where('status', 'validee');
            });
        }

        $accounts = $query->get();
        $balances = [];

        foreach ($accounts as $account) {
            $debitQuery = $account->debitEntries()
                ->where('status', 'validee')
                ->whereDate('entry_date', '<=', $date_end);
            
            $creditQuery = $account->creditEntries()
                ->where('status', 'validee')
                ->whereDate('entry_date', '<=', $date_end);
            
            if ($company_id) {
                $debitQuery->where('company_id', $company_id);
                $creditQuery->where('company_id', $company_id);
            }

            $debit = $debitQuery->sum('debit_amount');
            $credit = $creditQuery->sum('credit_amount');
            
            $balance = $debit - $credit;

            if ($debit > 0 || $credit > 0 || $balance != 0) {
                $balances[] = [
                    'account' => $account,
                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $balance
                ];
            }
        }

        $pdf = PDF::loadView('accounting.exports.trial-balance-pdf', compact('balances', 'date_end'));
        
        return $pdf->download('balance_generale_' . $date_end . '.pdf');
    }

    /**
     * Export general ledger to PDF
     */
    public function exportGeneralLedgerPdf(Request $request)
    {
        $entries = $this->getFilteredEntries($request);
        
        $pdf = PDF::loadView('accounting.exports.general-ledger-pdf', compact('entries'));
        
        return $pdf->download('grand_livre_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Get filtered entries based on request parameters
     */
    private function getFilteredEntries(Request $request)
    {
        $query = AccountingEntry::with(['debitAccount', 'journal', 'company'])
            ->where('status', 'validee')
            ->orderBy('entry_date', 'asc')
            ->orderBy('entry_number');

        if ($request->filled('date_start')) {
            $query->whereDate('entry_date', '>=', $request->date_start);
        }
        
        if ($request->filled('date_end')) {
            $query->whereDate('entry_date', '<=', $request->date_end);
        }
        
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('debit_account_id', $request->account_id)
                  ->orWhere('credit_account_id', $request->account_id);
            });
        }
        
        if ($request->filled('journal_id')) {
            $query->where('journal_id', $request->journal_id);
        }

        return $query->get();
    }
}