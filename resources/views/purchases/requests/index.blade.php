@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Demandes d'Achat</h1>
                <p class="text-gray-600 mt-1">Gestion des demandes d'achat (DA)</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('purchases.requests.export') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-download mr-2"></i>Exporter
                </a>
                <a href="{{ route('purchases.requests.create') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Nouvelle Demande
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
                       placeholder="Code, désignation..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Tous les statuts</option>
                    @foreach($statuts as $key => $value)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nature</label>
                <select name="nature_achat" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les natures</option>
                    @foreach($natures as $key => $value)
                        <option value="{{ $key }}" {{ request('nature_achat') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                <select name="agency_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Toutes les agences</option>
                    @foreach($agencies as $agency)
                        <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-primary-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Demandes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $purchaseRequests->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Attente</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $purchaseRequests->where('statut', 'En attente')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Validées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $purchaseRequests->where('statut', 'Validée')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Refusées</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $purchaseRequests->where('statut', 'Refusée')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Désignation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nature</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Demandeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Montant Estimé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchaseRequests as $request)
                        <tr class="hover:bg-primary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $request->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($request->designation, 40) }}</div>
                                <div class="text-sm text-gray-500">{{ $request->items->count() }} article(s)</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $request->nature_achat == 'Bien' ? 'bg-primary-100 text-primary-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $request->nature_achat }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->requestedBy->nom }} {{ $request->requestedBy->prenom }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->company->name }}</div>
                                @if($request->agency)
                                    <div class="text-sm text-gray-500">{{ $request->agency->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($request->prix_estime_total, 0, ',', ' ') }} FCFA</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($request->statut)
                                        @case('Brouillon') bg-gray-100 text-gray-800 @break
                                        @case('En attente') bg-yellow-100 text-yellow-800 @break
                                        @case('Validée') bg-green-100 text-green-800 @break
                                        @case('Refusée') bg-red-100 text-red-800 @break
                                        @case('Convertie en BOC') bg-primary-100 text-primary-800 @break
                                        @case('Annulée') bg-gray-100 text-gray-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $request->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->date_demande->format('d/m/Y') }}</div>
                                @if($request->date_echeance_souhaitee)
                                    <div class="text-sm text-gray-500">Échéance: {{ $request->date_echeance_souhaitee->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('purchases.requests.show', $request) }}" 
                                       class="text-primary-600 hover:text-primary-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($request->canBeEdited())
                                        <a href="{{ route('purchases.requests.edit', $request) }}" 
                                           class="text-green-600 hover:text-green-900" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if($request->canBeConverted())
                                        <a href="{{ route('purchases.requests.convert-to-boc', $request) }}" 
                                           class="text-purple-600 hover:text-purple-900" title="Convertir en BOC">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                Aucune demande d'achat trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($purchaseRequests->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $purchaseRequests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection