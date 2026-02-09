@extends('fournisseurs.portal.layout')

@section('title', 'Paiements Fournisseur')

@section('header', 'Paiements')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex flex-col items-center justify-between md:flex-row">
        <h2 class="text-xl font-bold text-gray-800">Historique des paiements</h2>
        
        <div class="mt-4 flex w-full md:mt-0 md:w-auto">
            <form method="GET" action="{{ route('supplier.portal.payments') }}" class="flex w-full space-x-2 md:w-auto">
                <select name="mode_paiement" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm md:w-auto">
                    <option value="">Tous les modes</option>
                    <option value="virement" {{ request('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="cheque" {{ request('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="espece" {{ request('mode_paiement') == 'espece' ? 'selected' : '' }}>Espèce</option>
                    <option value="mobile_money" {{ request('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                </select>
                
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm md:w-auto">
                
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm md:w-auto">
                
                <button type="submit" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <i class="fas fa-filter mr-1"></i> Filtrer
                </button>
                
                <a href="{{ route('supplier.portal.payments') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <i class="fas fa-times mr-1"></i> Réinitialiser
                </a>
            </form>
        </div>
    </div>
    
    <div class="rounded-lg bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Mode de paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Validé par</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-primary-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->date_paiement->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->invoice?->numero_facture ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->mode_paiement)) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->reference_paiement }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $payment->validatedBy?->name ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <a href="{{ route('supplier.portal.payments.show', $payment) }}" class="text-primary-600 hover:text-primary-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Aucun paiement trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection