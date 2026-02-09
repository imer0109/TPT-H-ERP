@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Plan Comptable</h1>
                <p class="text-gray-600 mt-1">Gestion du plan comptable par société</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('accounting.chart-of-accounts.tree') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sitemap mr-2"></i>Vue Arbre
                </a>
                <a href="{{ route('accounting.chart-of-accounts.import.form') }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-upload mr-2"></i>Importer
                </a>
                <a href="{{ route('accounting.chart-of-accounts.create') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouveau Compte
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Code ou libellé..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Société</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les sociétés</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="account_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les types</option>
                    @foreach($accountTypes as $key => $value)
                        <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nature</label>
                <select name="aux_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les natures</option>
                    @foreach($auxTypes as $key => $value)
                        <option value="{{ $key }}" {{ request('aux_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les statuts</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des comptes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nature</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accounts as $account)
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $account->code }}</div>
                                @if($account->syscohada_code)
                                    <div class="text-xs text-gray-500">SYSCOHADA: {{ $account->syscohada_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $account->label }}</div>
                                @if($account->parent)
                                    <div class="text-xs text-gray-500">Parent: {{ $account->parent->code }} - {{ $account->parent->label }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($account->account_type)
                                        @case('classe') bg-primary-100 text-primary-800 @break
                                        @case('sous_classe') bg-indigo-100 text-indigo-800 @break
                                        @case('compte') bg-green-100 text-green-800 @break
                                        @case('sous_compte') bg-yellow-100 text-yellow-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $account->account_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $account->account_nature === 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($account->account_nature) }}
                                </span>
                                @if($account->is_auxiliary)
                                    <div class="text-xs text-gray-500 mt-1">{{ ucfirst($account->aux_type) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $account->company->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $account->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $account->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('accounting.chart-of-accounts.show', $account) }}" 
                                       class="text-primary-600 hover:text-primary-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('accounting.chart-of-accounts.edit', $account) }}" 
                                       class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('accounting.chart-of-accounts.create', ['parent_id' => $account->id]) }}" 
                                       class="text-purple-600 hover:text-purple-900" title="Ajouter un sous-compte">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Aucun compte trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($accounts->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $accounts->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Actions en lot -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions en lot</h2>
        <div class="flex space-x-4">
            <form method="POST" action="{{ route('accounting.chart-of-accounts.syscohada') }}" class="inline" id="syscohada-form">
                @csrf
                <input type="hidden" name="company_id" value="{{ request('company_id') }}" id="syscohada-company-id">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition {{ !request('company_id') ? 'opacity-50 cursor-not-allowed' : '' }}"
                        onclick="return confirm('Créer le plan comptable SYSCOHADA de base ?')"
                        {{ !request('company_id') ? 'disabled' : '' }}>
                    <i class="fas fa-magic mr-2"></i>Créer Plan SYSCOHADA
                </button>
            </form>
            <a href="{{ route('accounting.chart-of-accounts.export', ['company_id' => request('company_id')]) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition {{ !request('company_id') ? 'opacity-50 cursor-not-allowed' : '' }}">
                <i class="fas fa-download mr-2"></i>Exporter Excel
            </a>
            <a href="{{ route('accounting.chart-of-accounts.export', ['company_id' => request('company_id'), 'format' => 'sage']) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition {{ !request('company_id') ? 'opacity-50 cursor-not-allowed' : '' }}">
                <i class="fas fa-file-export mr-2"></i>Export SAGE
            </a>
        </div>
        @if(!request('company_id'))
            <div class="mt-4 text-sm text-yellow-600">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Veuillez sélectionner une société dans les filtres ci-dessus pour activer les actions en lot.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update the company_id in the syscohada form when filters are applied
    const filterForm = document.querySelector('form[method="GET"]');
    const syscohadaForm = document.getElementById('syscohada-form');
    const syscohadaCompanyIdInput = document.getElementById('syscohada-company-id');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const companyIdSelect = this.querySelector('select[name="company_id"]');
            if (companyIdSelect && syscohadaCompanyIdInput) {
                syscohadaCompanyIdInput.value = companyIdSelect.value;
            }
        });
    }
    
    // Prevent form submission if no company is selected
    if (syscohadaForm) {
        syscohadaForm.addEventListener('submit', function(e) {
            if (!syscohadaCompanyIdInput.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une société avant de créer le plan SYSCOHADA.');
                return false;
            }
        });
    }
});
</script>
@endsection