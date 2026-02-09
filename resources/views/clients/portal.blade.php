@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Portail Client: {{ $client->nom_raison_sociale }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('clients.show', $client) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche client
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations générales</h2>
            
            <div class="mb-4 flex items-center">
                <span class="text-3xl mr-3 bg-red-100 text-red-800 p-3 rounded-full">
                    <i class="fas {{ $client->type_client == 'particulier' ? 'fa-user' : 'fa-building' }}"></i>
                </span>
                <div>
                    <p class="text-sm text-gray-500">Code client</p>
                    <p class="font-semibold">{{ $client->code_client }}</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Type</div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $client->type_client == 'particulier' ? 'bg-primary-100 text-primary-800' : '' }}
                            {{ $client->type_client == 'entreprise' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $client->type_client == 'administration' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $client->type_client == 'distributeur' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        ">
                            {{ ucfirst($client->type_client) }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Statut</div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $client->statut == 'actif' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $client->statut == 'inactif' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $client->statut == 'suspendu' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($client->statut) }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Catégorie</div>
                    <div>{{ $client->categorie ? 'Catégorie ' . $client->categorie : 'Non catégorisé' }}</div>
                </div>
                
                @if($client->loyaltyCard)
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Carte de fidélité</div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $client->loyaltyCard->tier == 'bronze' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $client->loyaltyCard->tier == 'silver' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $client->loyaltyCard->tier == 'gold' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $client->loyaltyCard->tier == 'platinum' ? 'bg-primary-100 text-primary-800' : '' }}
                        ">
                            {{ $client->loyaltyCard->points }} points
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Coordonnées</h2>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-primary-100 text-primary-800 p-2 rounded-full">
                        <i class="fas fa-phone"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-semibold">{{ $client->telephone }}</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-green-100 text-green-800 p-2 rounded-full">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold">{{ $client->email ?: 'Non renseigné' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-primary-100 text-primary-800 p-2 rounded-full">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp</p>
                        <p class="font-semibold">{{ $client->whatsapp ?: 'Non renseigné' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-purple-100 text-purple-800 p-2 rounded-full">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Adresse</p>
                        <p class="font-semibold">{{ $client->adresse ?: 'Non renseignée' }}</p>
                        <p class="text-sm text-gray-500">{{ $client->ville ?: '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations financières -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations financières</h2>
            
            <div class="mb-4">
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-gray-500">Encours actuel</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($encours, 0, ',', ' ') }} FCFA</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $nombreFacturesImpayees }} facture(s) impayée(s)</p>
                </div>
            </div>
            
            <div class="space-y-3 mt-6">
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Type de relation</div>
                    <div>{{ ucfirst($client->type_relation ?: 'Non défini') }}</div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Délai de paiement</div>
                    <div>{{ $client->delai_paiement ? $client->delai_paiement . ' jours' : 'Non défini' }}</div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Plafond de crédit</div>
                    <div>{{ $client->plafond_credit ? number_format($client->plafond_credit, 0, ',', ' ') . ' FCFA' : 'Non défini' }}</div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Délai moyen de règlement</div>
                    <div>{{ $delaiMoyenReglement }} jours</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions récentes -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Transactions récentes</h2>
            <a href="{{ route('clients.transactions', $client) }}" class="text-red-600 hover:text-red-800 text-sm font-medium">
                Voir tout l'historique <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Libellé</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->type == 'encaissement' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                                    ">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transaction->libelle }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type == 'encaissement' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type == 'encaissement' ? '+' : '-' }} {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500 italic">Aucune transaction enregistrée</p>
        @endif
    </div>

    <!-- Documents -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Documents</h2>
        </div>
        
        @if($client->documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($client->documents as $document)
                    <div class="border rounded-lg p-4 flex flex-col">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3 {{ $document->format == 'pdf' ? 'text-red-500' : 'text-primary-500' }}">
                                    <i class="fas {{ $document->format == 'pdf' ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                                </span>
                                <div>
                                    <h3 class="font-semibold truncate max-w-xs">{{ $document->nom }}</h3>
                                    <p class="text-xs text-gray-500">{{ $document->created_at->format('d/m/Y') }} - {{ number_format($document->taille / 1024, 2) }} KB</p>
                                </div>
                            </div>
                            <div class="flex">
                                <a href="{{ route('documents.download', $document) }}" class="text-primary-600 hover:text-primary-900 mr-2">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 truncate">{{ $document->description ?: 'Aucune description' }}</p>
                        </div>
                        <div class="mt-3 text-center">
                            @if(in_array($document->format, ['jpg', 'jpeg', 'png', 'gif']))
                                <a href="{{ route('documents.show', $document) }}" class="text-sm text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-eye mr-1"></i> Aperçu
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Aucun document disponible</p>
        @endif
    </div>
</div>
@endsection
