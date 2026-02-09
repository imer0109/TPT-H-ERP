@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Paiements fournisseurs</h1>
        <a href="{{ route('fournisseurs.payments.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">Nouveau paiement</a>
    </div>

    @if($overdueInvoices->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
            <h3 class="text-lg font-medium text-red-800 mb-2">⚠️ Factures en retard</h3>
            <div class="space-y-2">
                @foreach($overdueInvoices as $invoice)
                    <div class="flex justify-between items-center bg-white p-3 rounded border">
                        <div>
                            <span class="font-medium">{{ $invoice->numero_facture }}</span>
                            <span class="text-gray-600">- {{ $invoice->fournisseur->raison_sociale }}</span>
                            <span class="text-sm text-gray-500">(Échéance: {{ $invoice->date_echeance->format('d/m/Y') }})</span>
                        </div>
                        <div class="text-right">
                            <div class="font-medium text-red-600">{{ number_format($invoice->solde, 0, ',', ' ') }} {{ $invoice->devise }}</div>
                            <div class="text-sm text-gray-500">Retard de {{ $invoice->date_echeance->diffInDays(now()) }} jours</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Référence</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->date_paiement->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->fournisseur->raison_sociale }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($payment->invoice)
                                    <span class="text-primary-600">{{ $payment->invoice->numero_facture }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($payment->montant, 0, ',', ' ') }} {{ $payment->devise }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">
                                    {{ ucfirst($payment->mode_paiement) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->reference_paiement ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun paiement enregistré</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection