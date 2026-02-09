@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détails du Compte Comptable</h1>
                <p class="text-gray-600 mt-1">Informations détaillées sur le compte {{ $chartOfAccount->code }} - {{ $chartOfAccount->label }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('accounting.chart-of-accounts.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
                <a href="{{ route('accounting.chart-of-accounts.edit', $chartOfAccount) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations du compte</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500">Code</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $chartOfAccount->code }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Libellé</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $chartOfAccount->label }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Société</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $chartOfAccount->company->name }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Type de compte</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($chartOfAccount->account_type)
                            @case('classe') bg-primary-100 text-primary-800 @break
                            @case('sous_classe') bg-indigo-100 text-indigo-800 @break
                            @case('compte') bg-green-100 text-green-800 @break
                            @case('sous_compte') bg-yellow-100 text-yellow-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ \App\Models\ChartOfAccount::ACCOUNT_TYPES[$chartOfAccount->account_type] ?? ucfirst($chartOfAccount->account_type) }}
                    </span>
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Nature du compte</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $chartOfAccount->account_nature === 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ \App\Models\ChartOfAccount::ACCOUNT_NATURES[$chartOfAccount->account_nature] ?? ucfirst($chartOfAccount->account_nature) }}
                    </span>
                </p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500">Statut</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $chartOfAccount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $chartOfAccount->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </p>
            </div>
            
            @if($chartOfAccount->parent)
            <div>
                <label class="block text-sm font-medium text-gray-500">Compte parent</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <a href="{{ route('accounting.chart-of-accounts.show', $chartOfAccount->parent) }}" 
                       class="text-primary-600 hover:text-primary-800">
                        {{ $chartOfAccount->parent->code }} - {{ $chartOfAccount->parent->label }}
                    </a>
                </p>
            </div>
            @endif
            
            @if($chartOfAccount->syscohada_code)
            <div>
                <label class="block text-sm font-medium text-gray-500">Code SYSCOHADA</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $chartOfAccount->syscohada_code }}</p>
            </div>
            @endif
            
            @if($chartOfAccount->is_auxiliary)
            <div>
                <label class="block text-sm font-medium text-gray-500">Compte auxiliaire</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Oui
                    </span>
                    @if($chartOfAccount->aux_type)
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ \App\Models\ChartOfAccount::AUX_TYPES[$chartOfAccount->aux_type] ?? ucfirst($chartOfAccount->aux_type) }}
                        </span>
                    @endif
                </p>
            </div>
            @endif
            
            @if($chartOfAccount->vat_applicable)
            <div>
                <label class="block text-sm font-medium text-gray-500">TVA applicable</label>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                        Oui
                    </span>
                </p>
            </div>
            @endif
            
            @if($chartOfAccount->description)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-500">Description</label>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $chartOfAccount->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-primary-50 rounded-lg p-4">
                <div class="text-primary-800 font-semibold text-lg">{{ $stats['children_count'] }}</div>
                <div class="text-primary-600 text-sm">Sous-comptes</div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-green-800 font-semibold text-lg">{{ $stats['entries_count'] }}</div>
                <div class="text-green-600 text-sm">Écritures comptables</div>
            </div>
            
            <div class="bg-red-50 rounded-lg p-4">
                <div class="text-red-800 font-semibold text-lg">{{ number_format($stats['total_debit'], 2, ',', ' ') }} FCFA</div>
                <div class="text-red-600 text-sm">Total débit</div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4">
                <div class="text-green-800 font-semibold text-lg">{{ number_format($stats['total_credit'], 2, ',', ' ') }} FCFA</div>
                <div class="text-green-600 text-sm">Total crédit</div>
            </div>
        </div>
    </div>

    <!-- Sous-comptes -->
    @if($chartOfAccount->children->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Sous-comptes</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nature</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($chartOfAccount->children as $child)
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $child->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $child->label }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($child->account_type)
                                        @case('classe') bg-primary-100 text-primary-800 @break
                                        @case('sous_classe') bg-indigo-100 text-indigo-800 @break
                                        @case('compte') bg-green-100 text-green-800 @break
                                        @case('sous_compte') bg-yellow-100 text-yellow-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $child->account_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $child->account_nature === 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($child->account_nature) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $child->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('accounting.chart-of-accounts.show', $child) }}" 
                                       class="text-primary-600 hover:text-primary-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('accounting.chart-of-accounts.edit', $child) }}" 
                                       class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Écritures récentes -->
    @if($recent_entries->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Écritures comptables récentes</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Journal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Débit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Crédit</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recent_entries as $entry)
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $entry->entry_date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $entry->journal->code ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $entry->label }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->debit_account_id == $chartOfAccount->id)
                                    <div class="text-sm font-medium text-red-600">{{ number_format($entry->debit_amount, 2, ',', ' ') }} FCFA</div>
                                @else
                                    <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->credit_account_id == $chartOfAccount->id)
                                    <div class="text-sm font-medium text-green-600">{{ number_format($entry->credit_amount, 2, ',', ' ') }} FCFA</div>
                                @else
                                    <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection