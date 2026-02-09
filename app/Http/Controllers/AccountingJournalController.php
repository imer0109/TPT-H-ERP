<?php

namespace App\Http\Controllers;

use App\Models\AccountingJournal;
use App\Models\Company;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingJournalController extends Controller
{
    public function index()
    {
        $journals = AccountingJournal::with(['company', 'createdBy'])
            ->withCount(['entries as total_entries', 'entries as pending_entries' => function($query) {
                $query->where('status', 'brouillon');
            }])
            ->orderBy('code')
            ->paginate(20);

        return view('accounting.journals.index', compact('journals'));
    }

    public function create()
    {
        $companies = Company::all();
        $accounts = ChartOfAccount::active()->orderBy('code')->get();
        $journalTypes = AccountingJournal::JOURNAL_TYPES;

        return view('accounting.journals.create', compact('companies', 'accounts', 'journalTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'code' => 'required|string|max:10|unique:accounting_journals,code',
            'name' => 'required|string|max:255',
            'journal_type' => 'required|in:' . implode(',', array_keys(AccountingJournal::JOURNAL_TYPES)),
            'description' => 'nullable|string',
            'requires_validation' => 'boolean',
            'auto_numbering' => 'boolean',
            'number_prefix' => 'nullable|string|max:10',
            'default_debit_account_id' => 'nullable|exists:chart_of_accounts,id',
            'default_credit_account_id' => 'nullable|exists:chart_of_accounts,id',
        ]);

        $journal = AccountingJournal::create([
            ...$validated,
            'created_by' => Auth::id(),
            'requires_validation' => $validated['requires_validation'] ?? true,
            'auto_numbering' => $validated['auto_numbering'] ?? true,
        ]);

        return redirect()->route('accounting.journals.show', $journal)
            ->with('success', 'Journal créé avec succès.');
    }

    public function show(AccountingJournal $journal)
    {
        $journal->load(['company', 'defaultDebitAccount', 'defaultCreditAccount', 'createdBy']);
        
        $stats = [
            'total_entries' => $journal->entries()->count(),
            'pending_entries' => $journal->entries()->where('status', 'brouillon')->count(),
            'validated_entries' => $journal->entries()->where('status', 'validee')->count(),
            'total_debit' => $journal->getTotalDebitAmount(),
            'total_credit' => $journal->getTotalCreditAmount(),
            'balance' => $journal->getBalance(),
            'is_balanced' => $journal->isBalanced(),
        ];

        $recent_entries = $journal->entries()
            ->with(['debitAccount', 'creditAccount', 'createdBy'])
            ->orderBy('entry_date', 'desc')
            ->limit(10)
            ->get();

        return view('accounting.journals.show', compact('journal', 'stats', 'recent_entries'));
    }

    public function edit(AccountingJournal $journal)
    {
        $companies = Company::all();
        $accounts = ChartOfAccount::where('company_id', $journal->company_id)
                                  ->active()
                                  ->orderBy('code')
                                  ->get();
        $journalTypes = AccountingJournal::JOURNAL_TYPES;

        return view('accounting.journals.edit', compact('journal', 'companies', 'accounts', 'journalTypes'));
    }

    public function update(Request $request, AccountingJournal $journal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'requires_validation' => 'boolean',
            'auto_numbering' => 'boolean',
            'number_prefix' => 'nullable|string|max:10',
            'default_debit_account_id' => 'nullable|exists:chart_of_accounts,id',
            'default_credit_account_id' => 'nullable|exists:chart_of_accounts,id',
        ]);

        $journal->update([
            ...$validated,
            'is_active' => $validated['is_active'] ?? true,
            'requires_validation' => $validated['requires_validation'] ?? true,
            'auto_numbering' => $validated['auto_numbering'] ?? true,
        ]);

        return redirect()->route('accounting.journals.show', $journal)
            ->with('success', 'Journal mis à jour avec succès.');
    }

    public function destroy(AccountingJournal $journal)
    {
        if (!$journal->canBeDeleted()) {
            return back()->with('error', 'Ce journal ne peut pas être supprimé car il contient des écritures.');
        }

        $journal->delete();

        return redirect()->route('accounting.journals.index')
            ->with('success', 'Journal supprimé avec succès.');
    }

    public function balance(AccountingJournal $journal, Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $balance = $journal->getBalance($startDate, $endDate);
        $debitTotal = $journal->getTotalDebitAmount($startDate, $endDate);
        $creditTotal = $journal->getTotalCreditAmount($startDate, $endDate);
        $isBalanced = $journal->isBalanced($startDate, $endDate);

        $entries = $journal->entries()
            ->with(['debitAccount', 'creditAccount'])
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->orderBy('entry_date')
            ->get();

        return view('accounting.journals.balance', compact(
            'journal', 'balance', 'debitTotal', 'creditTotal', 'isBalanced', 
            'entries', 'startDate', 'endDate'
        ));
    }
}