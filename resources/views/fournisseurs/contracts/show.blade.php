@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Contrat: {{ $contract->contract_number }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('fournisseurs.contracts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="{{ route('fournisseurs.contracts.edit', $contract) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            @if($contract->status == 'active')
                <form action="{{ route('fournisseurs.contracts.terminate', $contract) }}" method="POST" 
                    onsubmit="return confirm('Êtes-vous sûr de vouloir résilier ce contrat ?');">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-times-circle mr-2"></i> Résilier
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contract Details -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Détails du contrat</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Numéro du contrat</p>
                    <p class="text-base font-medium">{{ $contract->contract_number }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Fournisseur</p>
                    <p class="text-base font-medium">
                        <a href="{{ route('fournisseurs.show', $contract->fournisseur) }}" class="text-primary-600 hover:text-primary-800">
                            {{ $contract->fournisseur->raison_sociale }}
                        </a>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Type de contrat</p>
                    <p class="text-base font-medium">{{ $contract->contract_type }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p class="text-base font-medium">{!! $contract->status_badge !!}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de début</p>
                    <p class="text-base font-medium">{{ $contract->start_date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de fin</p>
                    <p class="text-base font-medium">{{ $contract->end_date->format('d/m/Y') }}</p>
                    @if($contract->isExpiringSoon())
                        <p class="text-yellow-600 text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i> 
                            Expire dans {{ $contract->days_until_expiry }} jours
                        </p>
                    @endif
                </div>
                
                @if($contract->renewal_date)
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de renouvellement</p>
                    <p class="text-base font-medium">{{ $contract->renewal_date->format('d/m/Y') }}</p>
                </div>
                @endif
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Renouvellement automatique</p>
                    <p class="text-base font-medium">
                        @if($contract->auto_renewal)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Non
                            </span>
                        @endif
                    </p>
                </div>
                
                @if($contract->value)
                <div>
                    <p class="text-sm font-medium text-gray-500">Valeur du contrat</p>
                    <p class="text-base font-medium">{{ number_format($contract->value, 2, ',', ' ') }} {{ $contract->currency }}</p>
                </div>
                @endif
                
                @if($contract->responsible)
                <div>
                    <p class="text-sm font-medium text-gray-500">Responsable</p>
                    <p class="text-base font-medium">{{ $contract->responsible->name }}</p>
                </div>
                @endif
                
                @if($contract->last_review_date)
                <div>
                    <p class="text-sm font-medium text-gray-500">Dernière révision</p>
                    <p class="text-base font-medium">{{ $contract->last_review_date->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>
            
            @if($contract->description)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Description</p>
                <p class="text-base font-medium">{{ $contract->description }}</p>
            </div>
            @endif
            
            @if($contract->terms)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Conditions générales</p>
                <p class="text-base font-medium">{{ $contract->terms }}</p>
            </div>
            @endif
            
            @if($contract->special_conditions)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Conditions spéciales</p>
                <p class="text-base font-medium">{{ $contract->special_conditions }}</p>
            </div>
            @endif
            
            @if($contract->notes)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Notes internes</p>
                <p class="text-base font-medium">{{ $contract->notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Renew Contract -->
            @if($contract->status == 'active' || $contract->status == 'expired')
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Renouveler le contrat</h2>
                
                <form action="{{ route('fournisseurs.contracts.renew', $contract) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="new_end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Nouvelle date de fin <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="new_end_date" id="new_end_date" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        
                        <div>
                            <label for="new_value" class="block text-sm font-medium text-gray-700 mb-1">
                                Nouvelle valeur
                            </label>
                            <input type="number" name="new_value" id="new_value" step="0.01"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-sync mr-2"></i> Renouveler le contrat
                        </button>
                    </div>
                </form>
            </div>
            @endif
            
            <!-- Documents -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Documents</h2>
                    <!-- Add document upload form here if needed -->
                </div>
                
                @if($contract->documents->count() > 0)
                    <div class="space-y-3">
                        @foreach($contract->documents as $document)
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <div class="flex items-center">
                                    <i class="fas fa-file mr-2 text-gray-500"></i>
                                    <div>
                                        <p class="text-sm font-medium">{{ $document->type_document }}</p>
                                        <p class="text-xs text-gray-500">{{ $document->nom_fichier }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($document->chemin_fichier) }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Aucun document disponible</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
