<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\AccountingEntry;
use App\Models\AccountingJournal;
use Carbon\Carbon;

class AccountingReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // In Laravel 11, we should use route-level middleware instead of controller constructor middleware
        // The auth middleware is applied at the route level in web.php
    }

    /**
     * Display the reports dashboard
     */
    public function index()
    {
        return view('accounting.reports.index');
    }

    /**
     * Display the general ledger
     */
    public function generalLedger(Request $request)
    {
        $query = AccountingEntry::with(['debitAccount', 'creditAccount', 'journal', 'company'])
            ->where('status', 'validee')
            ->orderBy('entry_date', 'asc')
            ->orderBy('entry_number');

        // Apply filters
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

        $entries = $query->paginate(50);
        $accounts = ChartOfAccount::orderBy('code')->get();

        return view('accounting.reports.general-ledger', compact('entries', 'accounts'));
    }

    /**
     * Display the trial balance
     */
    public function trialBalance(Request $request)
    {
        $date_end = $request->filled('date_end') ? $request->date_end : now()->format('Y-m-d');
        $company_id = $request->company_id;

        $query = ChartOfAccount::with('entries')
            ->where('level', 'compte')
            ->orderBy('code');

        if ($company_id) {
            $query->whereHas('entries', function($q) use ($company_id, $date_end) {
                $q->where('company_id', $company_id)
                  ->whereDate('entry_date', '<=', $date_end)
                  ->where('status', 'validee');
            });
        }

        $accounts = $query->get();
        $balances = [];

        foreach ($accounts as $account) {
            $entriesQuery = $account->entries()
                ->where('status', 'validee')
                ->whereDate('entry_date', '<=', $date_end);
            
            if ($company_id) {
                $entriesQuery->where('company_id', $company_id);
            }

            $debit = $entriesQuery->sum('debit_amount');
            $credit = $entriesQuery->sum('credit_amount');
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

        return view('accounting.reports.trial-balance', compact('balances', 'date_end'));
    }

    /**
     * Display the income statement
     */
    public function incomeStatement(Request $request)
    {
        $year = $request->filled('year') ? $request->year : now()->year;
        $company_id = $request->company_id;

        // Get revenue accounts (class 7)
        $revenueAccounts = ChartOfAccount::where('code', 'like', '7%')
            ->where('level', 'compte')
            ->with(['entries' => function($q) use ($year, $company_id) {
                $q->whereYear('entry_date', $year)
                  ->where('status', 'validee');
                if ($company_id) {
                    $q->where('company_id', $company_id);
                }
            }])
            ->get();

        // Get expense accounts (class 6)
        $expenseAccounts = ChartOfAccount::where('code', 'like', '6%')
            ->where('level', 'compte')
            ->with(['entries' => function($q) use ($year, $company_id) {
                $q->whereYear('entry_date', $year)
                  ->where('status', 'validee');
                if ($company_id) {
                    $q->where('company_id', $company_id);
                }
            }])
            ->get();

        $totalRevenue = 0;
        $totalExpenses = 0;

        foreach ($revenueAccounts as $account) {
            $totalRevenue += $account->entries->sum('credit_amount') - $account->entries->sum('debit_amount');
        }

        foreach ($expenseAccounts as $account) {
            $totalExpenses += $account->entries->sum('debit_amount') - $account->entries->sum('credit_amount');
        }

        $netIncome = $totalRevenue - $totalExpenses;

        return view('accounting.reports.income-statement', compact(
            'revenueAccounts', 'expenseAccounts', 'totalRevenue', 'totalExpenses', 'netIncome', 'year'
        ));
    }

    /**
     * Display the balance sheet
     */
    public function balanceSheet(Request $request)
    {
        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        $company_id = $request->company_id;

        // Assets (classes 1-5)
        $assetAccounts = ChartOfAccount::whereIn('code', ['1', '2', '3', '4', '5'])
            ->where('level', 'classe')
            ->with(['children.children.children.entries' => function($q) use ($date, $company_id) {
                $q->whereDate('entry_date', '<=', $date)
                  ->where('status', 'validee');
                if ($company_id) {
                    $q->where('company_id', $company_id);
                }
            }])
            ->get();

        // Liabilities and Equity (classes 1 passif, 6, 7)
        $liabilityAccounts = ChartOfAccount::whereIn('code', ['1', '6', '7'])
            ->where('level', 'classe')
            ->with(['children.children.children.entries' => function($q) use ($date, $company_id) {
                $q->whereDate('entry_date', '<=', $date)
                  ->where('status', 'validee');
                if ($company_id) {
                    $q->where('company_id', $company_id);
                }
            }])
            ->get();

        return view('accounting.reports.balance-sheet', compact('assetAccounts', 'liabilityAccounts', 'date'));
    }

    /**
     * Display journal report
     */
    public function journalReport(Request $request)
    {
        $journal_id = $request->journal_id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        $query = AccountingEntry::with(['debitAccount', 'creditAccount', 'journal', 'company'])
            ->where('status', 'validee')
            ->orderBy('entry_date', 'asc')
            ->orderBy('entry_number');

        if ($journal_id) {
            $query->where('journal_id', $journal_id);
        }

        if ($date_start) {
            $query->whereDate('entry_date', '>=', $date_start);
        }

        if ($date_end) {
            $query->whereDate('entry_date', '<=', $date_end);
        }

        $entries = $query->paginate(50);
        $journals = AccountingJournal::orderBy('name')->get();

        return view('accounting.reports.journal', compact('entries', 'journals'));
    }

    /**
     * Generate analytical report by cost center
     */
    public function analyticalReport(Request $request)
    {
        $cost_center_id = $request->cost_center_id;
        $project_id = $request->project_id;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        $query = AccountingEntry::with(['debitAccount', 'creditAccount', 'costCenter', 'project', 'company'])
            ->where('status', 'validee')
            ->orderBy('entry_date', 'desc');

        if ($cost_center_id) {
            $query->where('cost_center_id', $cost_center_id);
        }

        if ($project_id) {
            $query->where('project_id', $project_id);
        }

        if ($date_start) {
            $query->whereDate('entry_date', '>=', $date_start);
        }

        if ($date_end) {
            $query->whereDate('entry_date', '<=', $date_end);
        }

        $entries = $query->paginate(50);

        return view('accounting.reports.analytical', compact('entries'));
    }
}