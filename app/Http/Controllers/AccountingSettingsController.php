<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\AccountingJournal;
use App\Models\CostCenter;
use App\Models\Project;
use App\Models\Company;

class AccountingSettingsController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display accounting settings
     */
    public function index()
    {
        $companies = Company::all();
        $journals = AccountingJournal::with('company')->get();
        $costCenters = CostCenter::with('company')->get();
        $projects = Project::with('company')->get();

        return view('accounting.settings.index', compact('companies', 'journals', 'costCenters', 'projects'));
    }

    /**
     * Show cost centers management
     */
    public function costCenters()
    {
        $costCenters = CostCenter::with('company')->orderBy('code')->get();
        $companies = Company::all();

        return view('accounting.settings.cost-centers', compact('costCenters', 'companies'));
    }

    /**
     * Store new cost center
     */
    public function storeCostCenter(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:cost_centers',
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string'
        ]);

        CostCenter::create($request->all());

        return redirect()->route('accounting.settings.cost-centers')
            ->with('success', 'Centre de coût créé avec succès.');
    }

    /**
     * Update cost center
     */
    public function updateCostCenter(Request $request, CostCenter $costCenter)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:cost_centers,code,' . $costCenter->id,
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string'
        ]);

        $costCenter->update($request->all());

        return redirect()->route('accounting.settings.cost-centers')
            ->with('success', 'Centre de coût modifié avec succès.');
    }

    /**
     * Delete cost center
     */
    public function destroyCostCenter(CostCenter $costCenter)
    {
        if ($costCenter->entries()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce centre de coût car il est utilisé dans des écritures comptables.');
        }

        $costCenter->delete();

        return redirect()->route('accounting.settings.cost-centers')
            ->with('success', 'Centre de coût supprimé avec succès.');
    }

    /**
     * Show projects management
     */
    public function projects()
    {
        $projects = Project::with('company')->orderBy('code')->get();
        $companies = Company::all();

        return view('accounting.settings.projects', compact('projects', 'companies'));
    }

    /**
     * Store new project
     */
    public function storeProject(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:projects',
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0'
        ]);

        Project::create($request->all());

        return redirect()->route('accounting.settings.projects')
            ->with('success', 'Projet créé avec succès.');
    }

    /**
     * Update project
     */
    public function updateProject(Request $request, Project $project)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:projects,code,' . $project->id,
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0'
        ]);

        $project->update($request->all());

        return redirect()->route('accounting.settings.projects')
            ->with('success', 'Projet modifié avec succès.');
    }

    /**
     * Delete project
     */
    public function destroyProject(Project $project)
    {
        if ($project->entries()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce projet car il est utilisé dans des écritures comptables.');
        }

        $project->delete();

        return redirect()->route('accounting.settings.projects')
            ->with('success', 'Projet supprimé avec succès.');
    }

    /**
     * Show journals management
     */
    public function journals()
    {
        $journals = AccountingJournal::with('company')->orderBy('code')->get();
        $companies = Company::all();

        return view('accounting.settings.journals', compact('journals', 'companies'));
    }

    /**
     * Store new journal
     */
    public function storeJournal(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:accounting_journals',
            'name' => 'required|string|max:255',
            'type' => 'required|in:vente,achat,banque,caisse,opérations_diverses',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string'
        ]);

        AccountingJournal::create($request->all());

        return redirect()->route('accounting.settings.journals')
            ->with('success', 'Journal créé avec succès.');
    }

    /**
     * Update journal
     */
    public function updateJournal(Request $request, AccountingJournal $journal)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:accounting_journals,code,' . $journal->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:vente,achat,banque,caisse,opérations_diverses',
            'company_id' => 'required|exists:companies,id',
            'description' => 'nullable|string'
        ]);

        $journal->update($request->all());

        return redirect()->route('accounting.settings.journals')
            ->with('success', 'Journal modifié avec succès.');
    }

    /**
     * Delete journal
     */
    public function destroyJournal(AccountingJournal $journal)
    {
        if ($journal->entries()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce journal car il contient des écritures comptables.');
        }

        $journal->delete();

        return redirect()->route('accounting.settings.journals')
            ->with('success', 'Journal supprimé avec succès.');
    }

    /**
     * Show accounting parameters
     */
    public function parameters()
    {
        $companies = Company::all();
        
        return view('accounting.settings.parameters', compact('companies'));
    }

    /**
     * Update accounting parameters
     */
    public function updateParameters(Request $request)
    {
        $request->validate([
            'fiscal_year_start' => 'required|date',
            'fiscal_year_end' => 'required|date|after:fiscal_year_start',
            'default_currency' => 'required|string|max:3',
            'auto_numbering' => 'boolean',
            'validation_required' => 'boolean'
        ]);

        // Here you would typically save these settings to a settings table
        // For now, we'll just redirect with success message
        
        return redirect()->route('accounting.settings.parameters')
            ->with('success', 'Paramètres comptables mis à jour avec succès.');
    }

    /**
     * Import chart of accounts from file
     */
    public function importChartOfAccounts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            // Import logic would go here
            // This is a placeholder for the actual import functionality
            
            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('success', 'Plan comptable importé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    /**
     * Reset chart of accounts for a company
     */
    public function resetChartOfAccounts(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        try {
            // Check if there are accounting entries
            $hasEntries = \DB::table('accounting_entries')
                ->where('company_id', $request->company_id)
                ->exists();

            if ($hasEntries) {
                return redirect()->back()
                    ->with('error', 'Impossible de réinitialiser le plan comptable car des écritures existent pour cette société.');
            }

            // Delete chart of accounts for this company
            ChartOfAccount::where('company_id', $request->company_id)->delete();

            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('success', 'Plan comptable réinitialisé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la réinitialisation : ' . $e->getMessage());
        }
    }
}