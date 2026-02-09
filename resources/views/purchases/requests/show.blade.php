@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête de la page -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Demande d'Achat {{ $request->code }}</h1>
                <p class="text-gray-600 mt-1">{{ $request->designation }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('purchases.requests.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                @if($request->canBeEdited())
                    <a href="{{ route('purchases.requests.edit', $request) }}" 
                       class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                @endif
                @if($request->canBeConverted())
                    <form method="POST" action="{{ route('purchases.requests.convert-to-boc', $request) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-exchange-alt mr-2"></i>Convertir en BOC
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la demande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Détails de la demande</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Code</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nature de l'achat</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $request->nature_achat == 'Bien' ? 'bg-primary-100 text-primary-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $request->nature_achat }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Société</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->company->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Agence</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->agency->name ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Demandeur</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->requestedBy->nom }} {{ $request->requestedBy->prenom }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de demande</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->date_demande->format('d/m/Y') }}</p>
                    </div>
                    @if($request->date_echeance_souhaitee)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Échéance souhaitée</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->date_echeance_souhaitee->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($request->fournisseurSuggere)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Fournisseur suggéré</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->fournisseurSuggere->nom }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500">Justification</label>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $request->justification }}</p>
                </div>

                @if($request->notes)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500">Notes</label>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $request->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Articles/Services -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Articles / Services</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Désignation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($request->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->designation }}</div>
                                        @if($item->description)
                                            <div class="text-sm text-gray-500">{{ $item->description }}</div>
                                        @endif
                                        @if($item->product)
                                            <div class="text-xs text-primary-600">Produit: {{ $item->product->libelle }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantite }} {{ $item->unite }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item->prix_unitaire_estime, 0, ',', ' ') }} FCFA</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($item->montant_total_estime, 0, ',', ' ') }} FCFA</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total estimé</td>
                                <td class="px-6 py-3 text-sm font-bold text-gray-900">{{ number_format($request->prix_estime_total, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Fichiers joints -->
            @if($request->files->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Fichiers joints</h2>
                <div class="space-y-3">
                    @foreach($request->files as $file)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $file->filename }}</p>
                                    <p class="text-xs text-gray-500">{{ $file->description ?? 'Aucune description' }} • {{ human_filesize($file->size) }}</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($file->path) }}" target="_blank" 
                               class="text-primary-600 hover:text-primary-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Bons de commande liés -->
            @if($request->supplierOrders->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Bons de commande liés</h2>
                <div class="space-y-3">
                    @foreach($request->supplierOrders as $order)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $order->code }}</p>
                                <p class="text-xs text-gray-500">{{ $order->fournisseur->nom }} • {{ number_format($order->montant_ttc, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($order->statut)
                                        @case('Brouillon') bg-gray-100 text-gray-800 @break
                                        @case('En attente') bg-yellow-100 text-yellow-800 @break
                                        @case('Envoyé') bg-primary-100 text-primary-800 @break
                                        @case('Confirmé') bg-green-100 text-green-800 @break
                                        @case('Livré') bg-purple-100 text-purple-800 @break
                                        @case('Clôturé') bg-gray-100 text-gray-800 @break
                                        @case('Annulé') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ $order->statut }}
                                </span>
                                <a href="{{ route('purchases.orders.show', $order) }}" 
                                   class="text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statut et actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut et actions</h2>
                
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                </div>

                @if($request->canBeValidated())
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('purchases.requests.validate', $request) }}">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <textarea name="commentaires" placeholder="Commentaires (optionnel)" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-check mr-2"></i>Approuver
                            </button>
                        </form>
                        <form method="POST" action="{{ route('purchases.requests.validate', $request) }}">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <textarea name="commentaires" placeholder="Motif du refus" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3"></textarea>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-times mr-2"></i>Refuser
                            </button>
                        </form>
                    </div>
                @endif

                @if($request->validated_by)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600">
                            Validé par {{ $request->validatedBy->nom }} {{ $request->validatedBy->prenom }}
                            le {{ $request->validated_at->format('d/m/Y à H:i') }}
                        </p>
                        @if($request->validation_comments)
                            <p class="text-sm text-gray-800 mt-2">{{ $request->validation_comments }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Historique des validations -->
            @if($request->validations->count() > 0 || ($request->validationRequest && $request->validationRequest->validationSteps->count() > 0))
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Historique des validations</h2>
                
                <!-- Advanced validation workflow steps -->
                @if($request->validationRequest && $request->validationRequest->validationSteps->count() > 0)
                    <div class="space-y-3">
                        @foreach($request->validationRequest->validationSteps as $step)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs
                                        @switch($step->action)
                                            @case('approved') bg-green-100 text-green-800 @break
                                            @case('rejected') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($step->action)
                                            @case('approved') <i class="fas fa-check"></i> @break
                                            @case('rejected') <i class="fas fa-times"></i> @break
                                            @default <i class="fas fa-clock"></i>
                                        @endswitch
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $step->validator->nom }} {{ $step->validator->prenom }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $step->getActionDisplayName() }}
                                        @if($step->validated_at)
                                            • {{ $step->validated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </p>
                                    @if($step->notes)
                                        <p class="text-xs text-gray-700 mt-1">{{ $step->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Original simple validation history -->
                    <div class="space-y-3">
                        @foreach($request->validations as $validation)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs
                                        @switch($validation->statut)
                                            @case('En attente') bg-yellow-100 text-yellow-800 @break
                                            @case('Approuvée') bg-green-100 text-green-800 @break
                                            @case('Rejetée') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($validation->statut)
                                            @case('En attente') <i class="fas fa-clock"></i> @break
                                            @case('Approuvée') <i class="fas fa-check"></i> @break
                                            @case('Rejetée') <i class="fas fa-times"></i> @break
                                        @endswitch
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $validation->validatedBy->nom }} {{ $validation->validatedBy->prenom }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $validation->statut }}
                                        @if($validation->validated_at)
                                            • {{ $validation->validated_at->format('d/m/Y à H:i') }}
                                        @endif
                                    </p>
                                    @if($validation->commentaires)
                                        <p class="text-xs text-gray-700 mt-1">{{ $validation->commentaires }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            <!-- Ajouter un fichier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un fichier</h2>
                <form method="POST" action="{{ route('purchases.requests.upload-file', $request) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <input type="file" name="file" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <input type="text" name="description" placeholder="Description du fichier"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            <i class="fas fa-upload mr-2"></i>Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection