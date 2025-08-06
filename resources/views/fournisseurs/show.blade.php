@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fiche Fournisseur</h1>
        <div class="flex space-x-2">
            <a href="{{ route('fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trash-alt mr-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Code fournisseur</p>
                    <p class="text-base font-medium">{{ $fournisseur->code_fournisseur }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Raison sociale</p>
                    <p class="text-base font-medium">{{ $fournisseur->raison_sociale }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Type</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->type == 'personne_physique')
                            Personne physique
                        @elseif($fournisseur->type == 'entreprise')
                            Entreprise
                        @elseif($fournisseur->type == 'institution')
                            Institution
                        @else
                            {{ $fournisseur->type }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Activité</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->activite == 'transport')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-truck mr-1"></i> Transport
                            </span>
                        @elseif($fournisseur->activite == 'logistique')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-warehouse mr-1"></i> Logistique
                            </span>
                        @elseif($fournisseur->activite == 'matieres_premieres')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-boxes mr-1"></i> Matières premières
                            </span>
                        @elseif($fournisseur->activite == 'services')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-concierge-bell mr-1"></i> Services
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-briefcase mr-1"></i> Autre
                            </span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Société / Agence</p>
                    <p class="text-base font-medium">{{ $fournisseur->societe->nom ?? '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->statut == 'actif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Actif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactif
                            </span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">NIU</p>
                    <p class="text-base font-medium">{{ $fournisseur->niu ?: '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">RCCM</p>
                    <p class="text-base font-medium">{{ $fournisseur->rccm ?: '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">CNSS</p>
                    <p class="text-base font-medium">{{ $fournisseur->cnss ?: '-' }}</p>
                </div>
                
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Adresse</p>
                    <p class="text-base font-medium">{{ $fournisseur->adresse }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Pays</p>
                    <p class="text-base font-medium">{{ $fournisseur->pays }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Ville</p>
                    <p class="text-base font-medium">{{ $fournisseur->ville }}</p>
                </div>
            </div>
        </div>
        
        <!-- Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Contact</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Contact principal</p>
                    <p class="text-base font-medium">{{ $fournisseur->contact_principal }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Téléphone</p>
                    <p class="text-base font-medium">
                        <a href="tel:{{ $fournisseur->telephone }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-phone-alt mr-1"></i> {{ $fournisseur->telephone }}
                        </a>
                    </p>
                </div>
                
                @if($fournisseur->whatsapp)
                <div>
                    <p class="text-sm font-medium text-gray-500">WhatsApp</p>
                    <p class="text-base font-medium">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $fournisseur->whatsapp) }}" target="_blank" class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp mr-1"></i> {{ $fournisseur->whatsapp }}
                        </a>
                    </p>
                </div>
                @endif
                
                @if($fournisseur->email)
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-base font-medium">
                        <a href="mailto:{{ $fournisseur->email }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope mr-1"></i> {{ $fournisseur->email }}
                        </a>
                    </p>
                </div>
                @endif
                
                @if($fournisseur->site_web)
                <div>
                    <p class="text-sm font-medium text-gray-500">Site web</p>
                    <p class="text-base font-medium">
                        <a href="{{ $fournisseur->site_web }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-globe mr-1"></i> {{ $fournisseur->site_web }}
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Informations bancaires -->
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations bancaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Banque</p>
                    <p class="text-base font-medium">{{ $fournisseur->banque ?: '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">N° de compte / IBAN</p>
                    <p class="text-base font-medium">{{ $fournisseur->numero_compte ?: '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Devise préférée</p>
                    <p class="text-base font-medium">{{ $fournisseur->devise ?: '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Conditions de règlement</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->condition_reglement == 'comptant')
                            Comptant
                        @elseif($fournisseur->condition_reglement == 'credit')
                            À crédit
                        @else
                            {{ $fournisseur->condition_reglement ?: '-' }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Délai de paiement</p>
                    <p class="text-base font-medium">{{ $fournisseur->delai_paiement ? $fournisseur->delai_paiement.' jours' : '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Plafond de crédit autorisé</p>
                    <p class="text-base font-medium">{{ $fournisseur->plafond_credit ? number_format($fournisseur->plafond_credit, 0, ',', ' ').' '.$fournisseur->devise : '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de début de relation</p>
                    <p class="text-base font-medium">{{ $fournisseur->date_debut_relation ? date('d/m/Y', strtotime($fournisseur->date_debut_relation)) : '-' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Documents administratifs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents administratifs</h2>
            
            <div class="space-y-4">
                @if($fournisseur->documents->count() > 0)
                    @foreach($fournisseur->documents as $document)
                    <div class="flex items-center justify-between p-3 border rounded-md">
                        <div>
                            <p class="font-medium">{{ $document->nom }}</p>
                            <p class="text-xs text-gray-500">Ajouté le {{ date('d/m/Y', strtotime($document->created_at)) }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('fournisseurs.documents.download', $document->id) }}" class="text-blue-600 hover:text-blue-800" title="Télécharger">
                                <i class="fas fa-download"></i>
                            </a>
                            <form action="{{ route('fournisseurs.documents.destroy', $document->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Aucun document disponible</p>
                @endif
                
                <div class="mt-4">
                    <a href="#" onclick="document.getElementById('uploadDocumentForm').classList.toggle('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fas fa-upload mr-2"></i> Ajouter un document
                    </a>
                    
                    <form id="uploadDocumentForm" action="{{ route('fournisseurs.documents.store', $fournisseur->id) }}" method="POST" enctype="multipart/form-data" class="hidden mt-4 p-4 border rounded-md">
                        @csrf
                        <div class="mb-3">
                            <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                            <select name="document_type" id="document_type" required 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <option value="">Sélectionner un type</option>
                                <option value="contrat_cadre">Contrat cadre / Devis / CGV</option>
                                <option value="rccm">RCCM</option>
                                <option value="attestation_fiscale">Attestation fiscale</option>
                                <option value="autre">Autre document</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="document_file" class="block text-sm font-medium text-gray-700 mb-1">Fichier</label>
                            <input type="file" name="document_file" id="document_file" required 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="document.getElementById('uploadDocumentForm').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                                <i class="fas fa-upload mr-2"></i> Téléverser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Onglets: Commandes, Livraisons, Paiements, Réclamations -->
    <div class="mt-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="#commandes" class="border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" onclick="showTab('commandes'); return false;">
                    Commandes
                </a>
                <a href="#livraisons" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" onclick="showTab('livraisons'); return false;">
                    Livraisons
                </a>
                <a href="#paiements" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" onclick="showTab('paiements'); return false;">
                    Paiements
                </a>
                <a href="#reclamations" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" onclick="showTab('reclamations'); return false;">
                    Réclamations
                </a>
            </nav>
        </div>
        
        <!-- Contenu des onglets -->
        <div id="commandes" class="py-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Commandes</h3>
                <a href="{{ route('commandes.create', ['fournisseur_id' => $fournisseur->id]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouvelle commande
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($commandes as $commande)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('commandes.show', $commande->id) }}" class="text-red-600 hover:text-red-800">
                                    {{ $commande->numero_commande }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ date('d/m/Y', strtotime($commande->date_commande)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($commande->montant_total, 0, ',', ' ') }} {{ $commande->devise }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($commande->statut == 'en_attente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> En attente
                                    </span>
                                @elseif($commande->statut == 'validee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-check mr-1"></i> Validée
                                    </span>
                                @elseif($commande->statut == 'livree')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-truck mr-1"></i> Livrée
                                    </span>
                                @elseif($commande->statut == 'annulee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Annulée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('commandes.show', $commande->id) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune commande trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="livraisons" class="py-4 hidden">
            <h3 class="text-lg font-medium mb-4">Livraisons</h3>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 italic">Aucune livraison disponible</p>
            </div>
        </div>
        
        <div id="paiements" class="py-4 hidden">
            <h3 class="text-lg font-medium mb-4">Paiements</h3>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-gray-500 italic">Aucun paiement disponible</p>
            </div>
        </div>
        
        <div id="reclamations" class="py-4 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Réclamations</h3>
                <a href="{{ route('reclamations-fournisseurs.create', ['fournisseur_id' => $fournisseur->id]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouvelle réclamation
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reclamations as $reclamation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('reclamations-fournisseurs.show', $reclamation->id) }}" class="text-red-600 hover:text-red-800">
                                    {{ $reclamation->reference }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ date('d/m/Y', strtotime($reclamation->date_reclamation)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reclamation->objet }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($reclamation->statut == 'ouverte')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Ouverte
                                    </span>
                                @elseif($reclamation->statut == 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-spinner mr-1"></i> En cours
                                    </span>
                                @elseif($reclamation->statut == 'resolue')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Résolue
                                    </span>
                                @elseif($reclamation->statut == 'fermee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i> Fermée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('reclamations-fournisseurs.show', $reclamation->id) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune réclamation trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabId) {
        // Cacher tous les contenus d'onglets
        document.querySelectorAll('#commandes, #livraisons, #paiements, #reclamations').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Afficher le contenu de l'onglet sélectionné
        document.getElementById(tabId).classList.remove('hidden');
        
        // Mettre à jour les classes des liens d'onglets
        document.querySelectorAll('nav a').forEach(link => {
            link.classList.remove('border-red-500', 'text-red-600');
            link.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        // Mettre en évidence l'onglet actif
        document.querySelector(`a[href="#${tabId}"]`).classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        document.querySelector(`a[href="#${tabId}"]`).classList.add('border-red-500', 'text-red-600');
    }
</script>
@endpush

@endsection