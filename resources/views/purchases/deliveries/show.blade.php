@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Livraison @if($delivery->order) {{ $delivery->order->code }} @endif
                    </h2>
                    <div class="flex space-x-2">
                        @if($delivery->statut == 'received' || $delivery->statut == 'partial')
                            <a href="{{ route('purchases.deliveries.edit', $delivery) }}" 
                               class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </a>
                            
                            @if(!$delivery->isServiceDelivery())
                                <form action="{{ route('purchases.deliveries.validate', $delivery) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir valider cette livraison ?')">
                                        Valider
                                    </button>
                                </form>
                                
                                <form action="{{ route('purchases.deliveries.validate', $delivery) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette livraison ?')">
                                        Rejeter
                                    </button>
                                </form>
                            @endif
                        @endif
                        
                        <a href="{{ route('purchases.deliveries.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Retour
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Informations générales</h3>
                        
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Bon de commande:</span>
                                <div class="text-sm text-gray-900">
                                    @if($delivery->order)
                                        <a href="{{ route('purchases.orders.show', $delivery->order) }}" class="text-red-600 hover:text-red-900">
                                            {{ $delivery->order->code }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Fournisseur:</span>
                                <div class="text-sm text-gray-900">{{ $delivery->fournisseur->raison_sociale ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Entrepôt:</span>
                                <div class="text-sm text-gray-900">{{ $delivery->warehouse->nom ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Numéro BL:</span>
                                <div class="text-sm text-gray-900">{{ $delivery->numero_bl }}</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Date de réception:</span>
                                <div class="text-sm text-gray-900">{{ $delivery->date_reception->format('d/m/Y') }}</div>
                            </div>
                            
                            @if($delivery->condition_emballage)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Condition de l'emballage:</span>
                                    <div class="text-sm text-gray-900">{{ ucfirst($delivery->condition_emballage) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Statut et validation</h3>
                        
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Statut:</span>
                                <div class="text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $delivery->getFormattedStatus()['color'] }}">
                                        {{ $delivery->getFormattedStatus()['text'] }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-500">Reçue par:</span>
                                <div class="text-sm text-gray-900">
                                    {{ $delivery->receivedBy->name ?? 'N/A' }}
                                    @if($delivery->created_at)
                                        le {{ $delivery->created_at->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                            </div>
                            
                            @if($delivery->validated_by)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Validée par:</span>
                                    <div class="text-sm text-gray-900">
                                        {{ $delivery->validatedBy->name ?? 'N/A' }}
                                        @if($delivery->validated_at)
                                            le {{ $delivery->validated_at->format('d/m/Y H:i') }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            @if($delivery->validation_notes)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Notes de validation:</span>
                                    <div class="text-sm text-gray-900">{{ $delivery->validation_notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($delivery->notes)
                    <div class="mb-6 bg-gray-50 p-4 rounded">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                        <div class="text-sm text-gray-900">{{ $delivery->notes }}</div>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        @if($delivery->isServiceDelivery())
                            Détails du service livré
                        @else
                            Articles livrés
                        @endif
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primary-50">
                                <tr>
                                    @if(!$delivery->isServiceDelivery())
                                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Désignation</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-primary-700 uppercase tracking-wider">Quantité commandée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-primary-700 uppercase tracking-wider">Quantité livrée</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-primary-700 uppercase tracking-wider">Écart</th>
                                    @else
                                        <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Service</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-primary-700 uppercase tracking-wider">Satisfaction</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($delivery->items as $item)
                                    <tr>
                                        @if(!$delivery->isServiceDelivery())
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->name ?? $item->orderItem->designation ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900">{{ $item->quantite_commandee }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900">{{ $item->quantite_livree }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($item->hasDiscrepancy())
                                                    <span class="text-sm font-medium {{ $item->ecart > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $item->getDiscrepancyDescription() }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-900">Conforme</span>
                                                @endif
                                            </td>
                                        @else
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">Service rendu</div>
                                                @if($item->compte_rendu)
                                                    <div class="text-sm text-gray-500 mt-1">Compte rendu: {{ $item->compte_rendu }}</div>
                                                @endif
                                                @if($item->preuve_service)
                                                    <div class="text-sm text-gray-500 mt-1">Preuve: {{ $item->preuve_service }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($item->satisfaction)
                                                    <div class="flex items-center justify-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="{{ $i <= $item->satisfaction ? 'text-yellow-400' : 'text-gray-300' }} h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $item->notes ?? '-' }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $delivery->isServiceDelivery() ? 3 : 5 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Aucun article trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
