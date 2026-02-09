<?php

namespace App\Http\Controllers;

use App\Models\AccountingEntry;
use App\Models\AccountingJournal;
use App\Models\ChartOfAccount;
use App\Models\Company;
use App\Models\Agency;
use App\Models\CostCenter;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function dashboard()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Statistiques de base
        $stats = [
            'total_entries' => AccountingEntry::count(),
            'pending_entries' => AccountingEntry::where('status', 'brouillon')->count(),
            'validated_entries' => AccountingEntry::where('status', 'validee')->count(),
            'monthly_entries' => AccountingEntry::whereYear('entry_date', $currentYear)
                                               ->whereMonth('entry_date', $currentMonth)
                                               ->count(),
            'total_debit' => AccountingEntry::where('status', 'validee')->sum('debit_amount'),
            'total_credit' => AccountingEntry::where('status', 'validee')->sum('credit_amount'),
            'monthly_debit' => AccountingEntry::where('status', 'validee')
                                              ->whereYear('entry_date', $currentYear)
                                              ->whereMonth('entry_date', $currentMonth)
                                              ->sum('debit_amount'),
            'monthly_credit' => AccountingEntry::where('status', 'validee')
                                               ->whereYear('entry_date', $currentYear)
                                               ->whereMonth('entry_date', $currentMonth)
                                               ->sum('credit_amount'),
        ];

        // Journaux avec statistiques
        $journals = AccountingJournal::with(['entries' => function($query) {
            $query->where('status', 'validee');
        }])->withCount(['entries as total_entries', 'entries as pending_entries' => function($query) {
            $query->where('status', 'brouillon');
        }])->get();

        // Entries récentes
        $recent_entries = AccountingEntry::with(['debitAccount', 'creditAccount', 'journal', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Alertes
        $alerts = [
            'unbalanced_journals' => $this->getUnbalancedJournals(),
            'pending_validations' => AccountingEntry::where('status', 'brouillon')->count(),
            'negative_balances' => $this->getNegativeBalanceAccounts(),
        ];

        // Données pour les graphiques
        $chartData = [
            'monthly_evolution' => $this->getMonthlyEvolution($currentYear),
            'journal_distribution' => $this->getJournalDistribution(),
            'account_types' => $this->getAccountTypesDistribution(),
        ];

        return view('accounting.dashboard', compact('stats', 'journals', 'recent_entries', 'alerts', 'chartData'));
    }

    public function entries(Request $request)
    {
        $query = AccountingEntry::with(['debitAccount', 'creditAccount', 'journal', 'company', 'createdBy']);

        // Filtres
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('entry_number', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('reference_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->journal_id) {
            $query->where('journal_id', $request->journal_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('entry_date', [$request->date_from, $request->date_to]);
        }

        if ($request->account_id) {
            $query->where(function($q) use ($request) {
                $q->where('debit_account_id', $request->account_id)
                  ->orWhere('credit_account_id', $request->account_id);
            });
        }

        $entries = $query->orderBy('entry_date', 'desc')->paginate(20);

        // Données pour les filtres
        $journals = AccountingJournal::active()->get();
        $companies = Company::all();
        $accounts = ChartOfAccount::active()->orderBy('code')->get();

        return view('accounting.entries.index', compact(
            'entries', 'journals', 'companies', 'accounts'
        ));
    }

    public function createEntry()
    {
        $journals = AccountingJournal::active()->get();
        $companies = Company::all();
        $agencies = Agency::all();
        $accounts = ChartOfAccount::active()->orderBy('code')->get();
        $costCenters = CostCenter::active()->get();
        $projects = Project::active()->get();

        return view('accounting.entries.create', compact(
            'journals', 'companies', 'agencies', 'accounts', 'costCenters', 'projects'
        ));
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'agency_id' => 'nullable|exists:agencies,id',
            'journal_id' => 'required|exists:accounting_journals,id',
            'entry_date' => 'required|date',
            'description' => 'required|string|max:255',
            'debit_account_id' => 'required|exists:chart_of_accounts,id',
            'credit_account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'project_id' => 'nullable|exists:projects,id',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $vat_amount = 0;
        if ($request->vat_rate) {
            $vat_amount = ($validated['amount'] * $validated['vat_rate']) / 100;
        }

        $entry = AccountingEntry::create([
            'company_id' => $validated['company_id'],
            'agency_id' => $validated['agency_id'],
            'journal_id' => $validated['journal_id'],
            'entry_date' => $validated['entry_date'],
            'reference_type' => 'manuel',
            'reference_number' => $validated['reference_number'],
            'description' => $validated['description'],
            'debit_account_id' => $validated['debit_account_id'],
            'credit_account_id' => $validated['credit_account_id'],
            'debit_amount' => $validated['amount'],
            'credit_amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'cost_center_id' => $validated['cost_center_id'],
            'project_id' => $validated['project_id'],
            'vat_amount' => $vat_amount,
            'vat_rate' => $validated['vat_rate'] ?? 0,
            'created_by' => Auth::id(),
            'notes' => $validated['notes'],
            'status' => $request->has('validate') ? 'validee' : 'brouillon'
        ]);

        return redirect()->route('accounting.entries.show', $entry)
            ->with('success', 'Écriture comptable créée avec succès.');
    }

    public function showEntry(AccountingEntry $entry)
    {
        $entry->load([
            'company', 'agency', 'journal', 'debitAccount', 'creditAccount', 
            'costCenter', 'project', 'createdBy', 'validatedBy'
        ]);

        return view('accounting.entries.show', compact('entry'));
    }

    public function validateEntry(AccountingEntry $entry)
    {
        if (!$entry->canBeValidated()) {
            return back()->with('error', 'Cette écriture ne peut pas être validée.');
        }

        $entry->update([
            'status' => 'validee',
            'validated_by' => Auth::id(),
            'validated_at' => now()
        ]);

        return back()->with('success', 'Écriture validée avec succès.');
    }

    public function balance(Request $request)
    {
        $company_id = $request->company_id;
        $start_date = $request->start_date ?? Carbon::now()->startOfYear();
        $end_date = $request->end_date ?? Carbon::now()->endOfYear();

        $query = AccountingEntry::with(['debitAccount', 'creditAccount'])
            ->where('status', 'validee')
            ->whereBetween('entry_date', [$start_date, $end_date]);

        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        // Calcul de la balance
        $accounts_balance = [];
        
        // Débits
        $debits = $query->clone()
            ->select('debit_account_id as account_id', DB::raw('SUM(debit_amount) as total'))
            ->groupBy('debit_account_id')
            ->get();

        foreach ($debits as $debit) {
            $accounts_balance[$debit->account_id]['debit'] = $debit->total;
        }

        // Crédits
        $credits = $query->clone()
            ->select('credit_account_id as account_id', DB::raw('SUM(credit_amount) as total'))
            ->groupBy('credit_account_id')
            ->get();

        foreach ($credits as $credit) {
            $accounts_balance[$credit->account_id]['credit'] = $credit->total;
        }

        // Récupération des comptes et calcul des soldes
        $balance_data = [];
        $account_ids = array_keys($accounts_balance);
        $accounts = ChartOfAccount::whereIn('id', $account_ids)->get()->keyBy('id');

        foreach ($accounts_balance as $account_id => $amounts) {
            $account = $accounts[$account_id];
            $debit = $amounts['debit'] ?? 0;
            $credit = $amounts['credit'] ?? 0;
            $balance = $debit - $credit;

            $balance_data[] = [
                'account' => $account,
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $balance
            ];
        }

        // Tri par code de compte
        usort($balance_data, function($a, $b) {
            return strcmp($a['account']->code, $b['account']->code);
        });

        $companies = Company::all();

        return view('accounting.balance', compact('balance_data', 'companies', 'start_date', 'end_date', 'company_id'));
    }

    public function trialBalance(Request $request)
    {
        // Implémentation de la balance générale
        return $this->balance($request);
    }

    public function generalLedger(Request $request)
    {
        $account_id = $request->account_id;
        $start_date = $request->start_date ?? Carbon::now()->startOfYear();
        $end_date = $request->end_date ?? Carbon::now()->endOfYear();

        if (!$account_id) {
            $accounts = ChartOfAccount::active()->orderBy('code')->get();
            return view('accounting.general-ledger', compact('accounts'));
        }

        $account = ChartOfAccount::findOrFail($account_id);
        
        $entries = AccountingEntry::with(['journal', 'debitAccount', 'creditAccount'])
            ->where('status', 'validee')
            ->where(function($query) use ($account_id) {
                $query->where('debit_account_id', $account_id)
                      ->orWhere('credit_account_id', $account_id);
            })
            ->whereBetween('entry_date', [$start_date, $end_date])
            ->orderBy('entry_date')
            ->get();

        // Calcul du solde courant
        $running_balance = 0;
        foreach ($entries as $entry) {
            if ($entry->debit_account_id == $account_id) {
                $running_balance += $entry->debit_amount;
                $entry->debit_current = $entry->debit_amount;
                $entry->credit_current = 0;
            } else {
                $running_balance -= $entry->credit_amount;
                $entry->debit_current = 0;
                $entry->credit_current = $entry->credit_amount;
            }
            $entry->running_balance = $running_balance;
        }

        $accounts = ChartOfAccount::active()->orderBy('code')->get();

        return view('accounting.general-ledger', compact('account', 'entries', 'accounts', 'start_date', 'end_date'));
    }

    // Méthodes privées pour les statistiques
    private function getUnbalancedJournals()
    {
        $journals = AccountingJournal::all();
        $unbalanced = [];

        foreach ($journals as $journal) {
            if (!$journal->isBalanced()) {
                $unbalanced[] = $journal;
            }
        }

        return $unbalanced;
    }

    private function getNegativeBalanceAccounts()
    {
        // Implémentation pour détecter les comptes avec solde négatif anormal
        return [];
    }

    private function getMonthlyEvolution($year)
    {
        $months = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->format('M');
            $monthlyTotal = AccountingEntry::whereYear('entry_date', $year)
                                          ->whereMonth('entry_date', $i)
                                          ->where('status', 'validee')
                                          ->sum('debit_amount');
            $data[] = $monthlyTotal;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getJournalDistribution()
    {
        $journals = AccountingJournal::withSum('entries', 'debit_amount')->get();
        
        return [
            'labels' => $journals->pluck('name')->toArray(),
            'data' => $journals->pluck('entries_sum_debit_amount')->toArray()
        ];
    }

    private function getAccountTypesDistribution()
    {
        $types = ChartOfAccount::select('account_type', DB::raw('COUNT(*) as count'))
            ->groupBy('account_type')
            ->get();

        return [
            'labels' => $types->pluck('account_type')->toArray(),
            'data' => $types->pluck('count')->toArray()
        ];
    }

    public function monthlyClosing(Request $request)
    {
        // Implémentation de la clôture mensuelle
        return view('accounting.monthly-closing');
    }

    public function yearlyClosing(Request $request)
    {
        // Implémentation de la clôture annuelle
        return view('accounting.yearly-closing');
    }
}
