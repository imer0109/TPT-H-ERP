<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ChartOfAccountsController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::with(['parent', 'company', 'createdBy']);

        // Filtres
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                  ->orWhere('label', 'like', "%{$request->search}%");
            });
        }

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->aux_type) {
            $query->where('aux_type', $request->aux_type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Tri hiérarchique
        $accounts = $query->orderBy('code')->paginate(50);

        // Données pour les filtres
        $companies = Company::all();
        $accountTypes = ChartOfAccount::ACCOUNT_TYPES;
        $auxTypes = ChartOfAccount::AUX_TYPES;

        return view('accounting.chart-of-accounts.index', compact(
            'accounts', 'companies', 'accountTypes', 'auxTypes'
        ));
    }

    public function tree(Request $request)
    {
        $companyId = $request->company_id;
        
        if (!$companyId) {
            $companies = Company::all();
            return view('accounting.chart-of-accounts.tree', compact('companies'));
        }

        $accountsTree = ChartOfAccount::buildTreeArray($companyId);
        $company = Company::find($companyId);
        $companies = Company::all();

        return view('accounting.chart-of-accounts.tree', compact('accountsTree', 'company', 'companies'));
    }

    public function create(Request $request)
    {
        $companies = Company::all();
        $parentId = $request->parent_id;
        $parent = null;

        if ($parentId) {
            $parent = ChartOfAccount::find($parentId);
        }

        $accounts = ChartOfAccount::where('company_id', $request->company_id ?? $companies->first()->id)
                                  ->orderBy('code')
                                  ->get();

        return view('accounting.chart-of-accounts.create', compact('companies', 'parent', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'code' => 'required|string|max:50',
            'label' => 'required|string|max:255',
            'account_type' => 'required|in:classe,sous_classe,compte,sous_compte',
            'account_nature' => 'required|in:debit,credit',
            'is_auxiliary' => 'boolean',
            'aux_type' => 'nullable|in:client,fournisseur,employe,immobilisation,tva,charges_sociales,banque,caisse',
            'vat_applicable' => 'boolean',
            'description' => 'nullable|string',
            'syscohada_code' => 'nullable|string|max:20',
        ]);

        // Vérification de l'unicité du code par société
        $existingAccount = ChartOfAccount::where('company_id', $validated['company_id'])
                                         ->where('code', $validated['code'])
                                         ->first();

        if ($existingAccount) {
            return back()->withErrors(['code' => 'Ce code existe déjà pour cette société.'])
                        ->withInput();
        }

        // Détermination du niveau
        $level = 1;
        if ($validated['parent_id']) {
            $parent = ChartOfAccount::find($validated['parent_id']);
            $level = $parent->level + 1;
        }

        $account = ChartOfAccount::create([
            'company_id' => $validated['company_id'],
            'parent_id' => $validated['parent_id'],
            'code' => $validated['code'],
            'label' => $validated['label'],
            'level' => $level,
            'account_type' => $validated['account_type'],
            'account_nature' => $validated['account_nature'],
            'is_auxiliary' => $validated['is_auxiliary'] ?? false,
            'aux_type' => $validated['aux_type'],
            'vat_applicable' => $validated['vat_applicable'] ?? false,
            'description' => $validated['description'],
            'syscohada_code' => $validated['syscohada_code'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('accounting.chart-of-accounts.show', $account)
            ->with('success', 'Compte créé avec succès.');
    }

    public function show(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->load(['parent', 'children', 'company', 'createdBy']);
        
        // Statistiques du compte
        $stats = [
            'children_count' => $chartOfAccount->children()->count(),
            'entries_count' => $chartOfAccount->accountingEntries()->count(),
            'total_debit' => $chartOfAccount->debitEntries()->sum('debit_amount'),
            'total_credit' => $chartOfAccount->creditEntries()->sum('credit_amount'),
        ];

        // Entrées récentes
        $recent_entries = $chartOfAccount->accountingEntries()
            ->with(['journal', 'debitAccount', 'creditAccount'])
            ->orderBy('entry_date', 'desc')
            ->limit(10)
            ->get();

        return view('accounting.chart-of-accounts.show', compact('chartOfAccount', 'stats', 'recent_entries'));
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $companies = Company::all();
        $accounts = ChartOfAccount::where('company_id', $chartOfAccount->company_id)
                                  ->where('id', '!=', $chartOfAccount->id)
                                  ->orderBy('code')
                                  ->get();

        return view('accounting.chart-of-accounts.edit', compact('chartOfAccount', 'companies', 'accounts'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'code' => 'required|string|max:50',
            'label' => 'required|string|max:255',
            'account_type' => 'required|in:classe,sous_classe,compte,sous_compte',
            'account_nature' => 'required|in:debit,credit',
            'is_active' => 'boolean',
            'is_auxiliary' => 'boolean',
            'aux_type' => 'nullable|in:client,fournisseur,employe,immobilisation,tva,charges_sociales,banque,caisse',
            'vat_applicable' => 'boolean',
            'description' => 'nullable|string',
            'syscohada_code' => 'nullable|string|max:20',
        ]);

        // Vérification de l'unicité du code par société (sauf le compte actuel)
        $existingAccount = ChartOfAccount::where('company_id', $chartOfAccount->company_id)
                                         ->where('code', $validated['code'])
                                         ->where('id', '!=', $chartOfAccount->id)
                                         ->first();

        if ($existingAccount) {
            return back()->withErrors(['code' => 'Ce code existe déjà pour cette société.'])
                        ->withInput();
        }

        // Détermination du niveau
        $level = 1;
        if ($validated['parent_id']) {
            $parent = ChartOfAccount::find($validated['parent_id']);
            $level = $parent->level + 1;
        }

        $chartOfAccount->update([
            'parent_id' => $validated['parent_id'],
            'code' => $validated['code'],
            'label' => $validated['label'],
            'level' => $level,
            'account_type' => $validated['account_type'],
            'account_nature' => $validated['account_nature'],
            'is_active' => $validated['is_active'] ?? true,
            'is_auxiliary' => $validated['is_auxiliary'] ?? false,
            'aux_type' => $validated['aux_type'],
            'vat_applicable' => $validated['vat_applicable'] ?? false,
            'description' => $validated['description'],
            'syscohada_code' => $validated['syscohada_code'],
        ]);

        return redirect()->route('accounting.chart-of-accounts.show', $chartOfAccount)
            ->with('success', 'Compte mis à jour avec succès.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if (!$chartOfAccount->canBeDeleted()) {
            return back()->with('error', 'Ce compte ne peut pas être supprimé car il a des comptes enfants ou des écritures comptables.');
        }

        $chartOfAccount->delete();

        return redirect()->route('accounting.chart-of-accounts.index')
            ->with('success', 'Compte supprimé avec succès.');
    }

    public function importForm()
    {
        $companies = Company::all();
        return view('accounting.chart-of-accounts.import', compact('companies'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'has_header' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Ici vous pourriez utiliser Laravel Excel pour importer le fichier
            // Excel::import(new ChartOfAccountsImport($request->company_id), $request->file('file'));

            DB::commit();

            return redirect()->route('accounting.chart-of-accounts.index')
                ->with('success', 'Plan comptable importé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $companyId = $request->company_id;
        $format = $request->format ?? 'excel';

        $accounts = ChartOfAccount::where('company_id', $companyId)
                                  ->orderBy('code')
                                  ->get();

        if ($format === 'excel') {
            // return Excel::download(new ChartOfAccountsExport($accounts), 'plan-comptable.xlsx');
        }

        // Export CSV pour SAGE/EBP
        if ($format === 'sage') {
            return $this->exportSageFormat($accounts);
        }

        return back()->with('error', 'Format d\'export non supporté.');
    }

    public function createSyscohadaPlan(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id'
        ], [
            'company_id.required' => 'Veuillez sélectionner une société pour créer le plan SYSCOHADA.',
            'company_id.exists' => 'La société sélectionnée n\'existe pas.'
        ]);

        $companyId = $validated['company_id'];

        // Plan comptable SYSCOHADA de base
        $syscohadaAccounts = $this->getSyscohadaBasePlan();

        try {
            DB::beginTransaction();

            foreach ($syscohadaAccounts as $accountData) {
                ChartOfAccount::create([
                    'company_id' => $companyId,
                    'code' => $accountData['code'],
                    'label' => $accountData['label'],
                    'account_type' => $accountData['account_type'],
                    'account_nature' => $accountData['account_nature'],
                    'syscohada_code' => $accountData['syscohada_code'],
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('accounting.chart-of-accounts.index', ['company_id' => $companyId])
                ->with('success', 'Plan comptable SYSCOHADA créé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création du plan comptable : ' . $e->getMessage());
        }
    }

    private function exportSageFormat($accounts)
    {
        $csv = "Code;Libellé;Type;Nature\n";
        
        foreach ($accounts as $account) {
            $csv .= sprintf("%s;%s;%s;%s\n",
                $account->code,
                $account->label,
                $account->account_type,
                $account->account_nature
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="plan-comptable-sage.csv"');
    }

    private function getSyscohadaBasePlan()
    {
        return [
            // Classe 1 - Comptes de ressources durables
            ['code' => '1', 'label' => 'COMPTES DE RESSOURCES DURABLES', 'account_type' => 'classe', 'account_nature' => 'credit', 'syscohada_code' => '1'],
            ['code' => '10', 'label' => 'Capital et réserves', 'account_type' => 'sous_classe', 'account_nature' => 'credit', 'syscohada_code' => '10'],
            ['code' => '101', 'label' => 'Capital social', 'account_type' => 'compte', 'account_nature' => 'credit', 'syscohada_code' => '101'],
            
            // Classe 2 - Comptes d'actif immobilisé
            ['code' => '2', 'label' => 'COMPTES D\'ACTIF IMMOBILISE', 'account_type' => 'classe', 'account_nature' => 'debit', 'syscohada_code' => '2'],
            ['code' => '21', 'label' => 'Immobilisations incorporelles', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '21'],
            ['code' => '22', 'label' => 'Terrains', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '22'],
            ['code' => '23', 'label' => 'Bâtiments, installations techniques et agencements', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '23'],
            ['code' => '24', 'label' => 'Matériel, mobilier et actifs biologiques', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '24'],
            
            // Classe 3 - Comptes de stocks
            ['code' => '3', 'label' => 'COMPTES DE STOCKS', 'account_type' => 'classe', 'account_nature' => 'debit', 'syscohada_code' => '3'],
            ['code' => '31', 'label' => 'Marchandises', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '31'],
            
            // Classe 4 - Comptes de tiers
            ['code' => '4', 'label' => 'COMPTES DE TIERS', 'account_type' => 'classe', 'account_nature' => 'debit', 'syscohada_code' => '4'],
            ['code' => '40', 'label' => 'Fournisseurs et comptes rattachés', 'account_type' => 'sous_classe', 'account_nature' => 'credit', 'syscohada_code' => '40'],
            ['code' => '401', 'label' => 'Fournisseurs', 'account_type' => 'compte', 'account_nature' => 'credit', 'syscohada_code' => '401'],
            ['code' => '41', 'label' => 'Clients et comptes rattachés', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '41'],
            ['code' => '411', 'label' => 'Clients', 'account_type' => 'compte', 'account_nature' => 'debit', 'syscohada_code' => '411'],
            
            // Classe 5 - Comptes de trésorerie
            ['code' => '5', 'label' => 'COMPTES DE TRESORERIE', 'account_type' => 'classe', 'account_nature' => 'debit', 'syscohada_code' => '5'],
            ['code' => '52', 'label' => 'Banques, établissements financiers et assimilés', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '52'],
            ['code' => '521', 'label' => 'Banques locales', 'account_type' => 'compte', 'account_nature' => 'debit', 'syscohada_code' => '521'],
            ['code' => '57', 'label' => 'Caisse', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '57'],
            ['code' => '571', 'label' => 'Caisse principale', 'account_type' => 'compte', 'account_nature' => 'debit', 'syscohada_code' => '571'],
            
            // Classe 6 - Comptes de charges
            ['code' => '6', 'label' => 'COMPTES DE CHARGES', 'account_type' => 'classe', 'account_nature' => 'debit', 'syscohada_code' => '6'],
            ['code' => '60', 'label' => 'Achats et variations de stocks', 'account_type' => 'sous_classe', 'account_nature' => 'debit', 'syscohada_code' => '60'],
            ['code' => '601', 'label' => 'Achats de marchandises', 'account_type' => 'compte', 'account_nature' => 'debit', 'syscohada_code' => '601'],
            
            // Classe 7 - Comptes de produits
            ['code' => '7', 'label' => 'COMPTES DE PRODUITS', 'account_type' => 'classe', 'account_nature' => 'credit', 'syscohada_code' => '7'],
            ['code' => '70', 'label' => 'Ventes', 'account_type' => 'sous_classe', 'account_nature' => 'credit', 'syscohada_code' => '70'],
            ['code' => '701', 'label' => 'Ventes de marchandises', 'account_type' => 'compte', 'account_nature' => 'credit', 'syscohada_code' => '701'],
        ];
    }
}