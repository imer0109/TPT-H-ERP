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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
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
                    <p class="text-base font-medium">{{ $fournisseur->societe->raison_sociale ?? '-' }}</p>
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
                        <a href="tel:{{ $fournisseur->telephone }}" class="text-primary-600 hover:text-primary-800">
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
                        <a href="mailto:{{ $fournisseur->email }}" class="text-primary-600 hover:text-primary-800">
                            <i class="fas fa-envelope mr-1"></i> {{ $fournisseur->email }}
                        </a>
                    </p>
                </div>
                @endif
                
                @if($fournisseur->site_web)
                <div>
                    <p class="text-sm font-medium text-gray-500">Site web</p>
                    <p class="text-base font-medium">
                        <a href="{{ $fournisseur->site_web }}" target="_blank" class="text-primary-600 hover:text-primary-800">
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
                    <p class="text-sm font-medium text-gray-500">Devise par défaut</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->devise == 'XOF')
                            Franc CFA (XOF)
                        @elseif($fournisseur->devise == 'EUR')
                            Euro (EUR)
                        @elseif($fournisseur->devise == 'USD')
                            Dollar US (USD)
                        @else
                            {{ $fournisseur->devise ?: '-' }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Condition de règlement</p>
                    <p class="text-base font-medium">
                        @if($fournisseur->condition_reglement == 'comptant')
                            Comptant
                        @elseif($fournisseur->condition_reglement == 'credit')
                            Crédit
                        @else
                            {{ $fournisseur->condition_reglement ?: '-' }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Délai de paiement</p>
                    <p class="text-base font-medium">{{ $fournisseur->delai_paiement ? $fournisseur->delai_paiement . ' jours' : '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Plafond de crédit</p>
                    <p class="text-base font-medium">{{ $fournisseur->plafond_credit ? number_format($fournisseur->plafond_credit, 2) . ' ' . ($fournisseur->devise ?: 'XOF') : '-' }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de début de relation</p>
                    <p class="text-base font-medium">{{ $fournisseur->date_debut_relation ? $fournisseur->date_debut_relation->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Documents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents</h2>
            
            <div class="space-y-3">
                @forelse($fournisseur->documents as $document)
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
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Aucun document disponible</p>
                @endforelse
            </div>
        </div>
        
        <!-- Évaluations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Évaluations</h2>
                <a href="{{ route('fournisseurs.ratings.create', $fournisseur) }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouvelle évaluation
                </a>
            </div>
            
            @if($fournisseur->ratings->count() > 0)
                <div class="mb-4">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold mr-4">{{ number_format($fournisseur->average_rating, 1) }}</div>
                        <div>
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if($i <= round($fournisseur->average_rating))
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-sm text-gray-500">{{ $fournisseur->rating_count }} évaluation(s)</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($fournisseur->ratings->sortByDesc('created_at')->take(5) as $rating)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->overall_score)
                                                <i class="fas fa-star text-yellow-400"></i>
                                            @else
                                                <i class="far fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Évalué par {{ $rating->evaluator->name ?? 'Système' }} le {{ $rating->evaluation_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('fournisseurs.ratings.edit', $rating) }}" class="text-primary-600 hover:text-primary-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fournisseurs.ratings.destroy', $rating) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($rating->comments)
                                <div class="mt-2 text-sm text-gray-700">
                                    {{ $rating->comments }}
                                </div>
                            @endif
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-3 text-xs">
                                <div>
                                    <div class="text-gray-500">Qualité</div>
                                    <div class="font-medium">{{ $rating->quality_rating }}/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Livraison</div>
                                    <div class="font-medium">{{ $rating->delivery_rating }}/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Réactivité</div>
                                    <div class="font-medium">{{ $rating->responsiveness_rating }}/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Prix</div>
                                    <div class="font-medium">{{ $rating->pricing_rating }}/5</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-star text-4xl mb-2"></i>
                    <p>Aucune évaluation pour ce fournisseur</p>
                    <a href="{{ route('fournisseurs.ratings.create', $fournisseur) }}" class="mt-2 inline-block bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2 px-4 rounded">
                        Ajouter une évaluation
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Contrats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Contrats</h2>
                <a href="{{ route('fournisseurs.contracts.create', ['fournisseur_id' => $fournisseur->id]) }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouveau contrat
                </a>
            </div>
            
            @if($fournisseur->contracts->count() > 0)
                <div class="space-y-4">
                    @foreach($fournisseur->contracts->sortByDesc('created_at')->take(5) as $contract)
                        <div class="border rounded-lg p-4 {{ $contract->isExpiringSoon() ? 'border-yellow-300 bg-yellow-50' : '' }} {{ $contract->isExpired() ? 'border-red-300 bg-red-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="text-md font-medium text-gray-900">
                                            <a href="{{ route('fournisseurs.contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-800">
                                                {{ $contract->contract_number }}
                                            </a>
                                        </h3>
                                        <div class="ml-2">
                                            {!! $contract->status_badge !!}
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">{{ $contract->contract_type }}</p>
                                    <p class="text-sm text-gray-700 mt-1">{{ Str::limit($contract->description, 100) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $contract->start_date->format('d/m/Y') }} - {{ $contract->end_date->format('d/m/Y') }}
                                    </p>
                                    @if($contract->isExpiringSoon())
                                        <p class="text-xs text-yellow-600 mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Expire dans {{ $contract->days_until_expiry }} jours
                                        </p>
                                    @endif
                                    @if($contract->isExpired())
                                        <p class="text-xs text-red-600 mt-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Expiré
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($contract->value)
                                <div class="mt-2 text-sm text-gray-700">
                                    Valeur: {{ number_format($contract->value, 2, ',', ' ') }} {{ $contract->currency }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @if($fournisseur->contracts->count() > 5)
                        <div class="text-center mt-4">
                            <a href="{{ route('fournisseurs.contracts.index', ['fournisseur_id' => $fournisseur->id]) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                Voir tous les {{ $fournisseur->contracts->count() }} contrats
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-contract text-4xl mb-2"></i>
                    <p>Aucun contrat pour ce fournisseur</p>
                    <a href="{{ route('fournisseurs.contracts.create', ['fournisseur_id' => $fournisseur->id]) }}" class="mt-2 inline-block bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold py-2 px-4 rounded">
                        Ajouter un contrat
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Commandes, Livraisons, Paiements, Réclamations -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Historique des transactions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Commandes -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Commandes</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-600">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center">{{ $commandes->count() }}</p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières commandes</p>
                @if($commandes->count() > 0)
                    <div class="mt-2 space-y-1">
                        @foreach($commandes as $commande)
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="{{ route('achats.orders.show', $commande->id) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $commande->numero_commande }} - {{ number_format($commande->montant_total, 2) }} XOF
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Livraisons -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Livraisons</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-truck"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center">{{ $livraisons->count() }}</p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières livraisons</p>
                @if($livraisons->count() > 0)
                    <div class="mt-2 space-y-1">
                        @foreach($livraisons as $livraison)
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="{{ route('achats.deliveries.show', $livraison->id) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $livraison->numero_livraison }} - {{ $livraison->quantite_recue }} articles
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Paiements -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Paiements</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center">{{ $paiements->count() }}</p>
                <p class="text-xs text-gray-500 text-center mt-1">derniers paiements</p>
                @if($paiements->count() > 0)
                    <div class="mt-2 space-y-1">
                        @foreach($paiements as $paiement)
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="{{ route('achats.payments.show', $paiement->id) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $paiement->numero_paiement }} - {{ number_format($paiement->montant, 2) }} XOF
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Réclamations -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Réclamations</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center">{{ $reclamations->count() }}</p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières réclamations</p>
                @if($reclamations->count() > 0)
                    <div class="mt-2 space-y-1">
                        @foreach($reclamations as $reclamation)
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="{{ route('fournisseurs.issues.show', $reclamation->id) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $reclamation->numero_reclamation }} - {{ ucfirst($reclamation->statut) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection