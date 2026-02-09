@extends('fournisseurs.portal.layout')

@section('title', 'Contrats Fournisseur')

@section('header', 'Contrats')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Liste des contrats</h2>
    </div>
    
    <div class="rounded-lg bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Numéro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Jours restants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($contracts as $contract)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $contract->contract_number }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $contract->contract_type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($contract->description, 50) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $contract->start_date->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $contract->end_date->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <span class="{{ $contract->days_until_expiry <= 30 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                    {{ $contract->days_until_expiry }} jours
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full bg-{{ $contract->status === 'active' ? 'green' : ($contract->status === 'pending' ? 'yellow' : ($contract->status === 'expired' ? 'red' : 'gray')) }}-100 px-2 text-xs font-semibold leading-5 text-{{ $contract->status === 'active' ? 'green' : ($contract->status === 'pending' ? 'yellow' : ($contract->status === 'expired' ? 'red' : 'gray')) }}-800">
                                    {{ ucfirst($contract->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <a href="{{ route('supplier.portal.contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucun contrat trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $contracts->links() }}
        </div>
    </div>
</div>
@endsection