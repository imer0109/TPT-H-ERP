@extends('layouts.app')

@section('title', 'Détails de la Réclamation')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-xl">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg sm:text-xl font-semibold text-gray-800">Détails de la Réclamation #{{ $reclamation->id }}</h4>
            <a href="{{ route('clients.reclamations.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client</label>
                        <p><a href="{{ route('clients.show', $reclamation->client) }}" class="text-primary-600 hover:text-primary-700 hover:underline">{{ $reclamation->client->nom_raison_sociale }}</a></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de Réclamation</label>
                        @php
                            $type = $reclamation->type_reclamation;
                            $typeColor = $type === 'produit_defectueux' ? 'bg-red-100 text-red-700' :
                                         ($type === 'retard_livraison' ? 'bg-yellow-100 text-yellow-700' :
                                         ($type === 'erreur_facturation' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-700'));
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $typeColor }}">
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        @php
                            $statut = $reclamation->statut;
                            $statutColor = $statut === 'ouverte' ? 'bg-yellow-100 text-yellow-700' :
                                           ($statut === 'en_cours' ? 'bg-sky-100 text-sky-700' : 'bg-green-100 text-green-700');
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold {{ $statutColor }}">
                            {{ ucfirst(str_replace('_', ' ', $statut)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agent Assigné</label>
                        <p>{{ $reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné' }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Création</label>
                        <p>{{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de Résolution</label>
                        <p>{{ $reclamation->date_resolution ? $reclamation->date_resolution->format('d/m/Y H:i') : 'Non résolue' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dernière Mise à Jour</label>
                        <p>{{ $reclamation->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="text-gray-700">{{ $reclamation->description }}</p>
                </div>

                @if($reclamation->solution)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Solution</label>
                    <p class="text-gray-700">{{ $reclamation->solution }}</p>
                </div>
                @endif

                @if($reclamation->commentaires)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commentaires</label>
                    <p class="text-gray-700">{{ $reclamation->commentaires }}</p>
                </div>
                @endif
            </div>

            @if($reclamation->documents->count() > 0)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700">Documents joints</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                    @foreach($reclamation->documents as $document)
                    <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center gap-2 px-3 py-2 border border-primary-300 text-primary-700 rounded-md hover:bg-primary-50">
                        <i class="fas fa-file"></i> {{ $document->nom }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('clients.reclamations.edit', $reclamation) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md">
                    <i class="fas fa-edit"></i> <span>Modifier</span>
                </a>
                <form action="{{ route('clients.reclamations.destroy', $reclamation) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                        <i class="fas fa-trash"></i> <span>Supprimer</span>
                    </button>
                </form>

                <form action="{{ route('clients.reclamations.change-status', $reclamation) }}" method="POST" class="inline-flex items-center gap-2">
                    @csrf
                    <span class="text-gray-600">Changer le statut:</span>
                    <button type="submit" name="statut" value="ouverte" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm">Ouverte</button>
                    <button type="submit" name="statut" value="en_cours" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-md text-sm">En Cours</button>
                    <button type="submit" name="statut" value="resolue" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm">Résolue</button>
                </form>

                <form action="{{ route('clients.reclamations.assign-agent', $reclamation) }}" method="POST" class="inline-flex items-center gap-2">
                    @csrf
                    <span class="text-gray-600">Assigner un agent:</span>
                    <select name="agent_id" class="rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" required>
                        <option value="">Sélectionnez un agent</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->nom }} {{ $agent->prenom }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm">Assigner</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
