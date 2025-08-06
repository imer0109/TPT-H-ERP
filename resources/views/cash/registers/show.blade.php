@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Caisse: {{ $cashRegister->nom }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('cash.registers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded">
                    Retour à la liste
                </a>
                <a href="{{ route('cash.registers.edit', $cashRegister) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Modifier
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de la caisse</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Détails et opérations disponibles
                    </p>
                </div>
                <div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashRegister->est_ouverte ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $cashRegister->est_ouverte ? 'Ouverte' : 'Fermée' }}
                    </span>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cashRegister->nom }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $cashRegister->type === 'principale' ? 'Principale' : 'Secondaire' }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Entité</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($cashRegister->entity)
                                {{ class_basename($cashRegister->entity_type) === 'Societe' ? 'Société' : 'Agence' }} : {{ $cashRegister->entity->nom }}
                            @else
                                Non assignée
                            @endif
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Solde actuel</dt>
                        <dd class="mt-1 text-sm font-bold text-gray-900 sm:mt-0 sm:col-span-2">{{ number_format($cashRegister->solde_actuel, 2, ',', ' ') }} FCFA</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Actions de caisse -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Actions</h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    @if(!$cashRegister->est_ouverte)
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Ouvrir la caisse</h4>
                        <form action="{{ route('cash.sessions.open', $cashRegister) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="solde_initial" class="block text-sm font-medium text-gray-700">Solde initial</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="solde_initial" id="solde_initial" step="0.01" min="0" value="{{ old('solde_initial', $cashRegister->solde_actuel) }}" required
                                        class="focus:ring-red-500 border py-2 focus:border-red-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                                <textarea name="commentaire" id="commentaire" rows="2"
                                    class="mt-1 focus:ring-red-500 border focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('commentaire') }}</textarea>
                            </div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Ouvrir la caisse
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="mb-4">
                        <h4 class="text-md font-medium text-gray-700 mb-2">Nouvelle transaction</h4>
                        <a href="{{ route('cash.transactions.create', $cashRegister) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Créer une transaction
                        </a>
                    </div>

                    @php
                        $currentSession = $cashRegister->currentSession();
                    @endphp

                    @if($currentSession)
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-2">Fermer la caisse</h4>
                        <form action="{{ route('cash.sessions.close', [$cashRegister, $currentSession]) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="solde_final" class="block text-sm font-medium text-gray-700">Solde final</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="solde_final" id="solde_final" step="0.01" min="0" value="{{ old('solde_final', $cashRegister->solde_actuel) }}" required
                                        class="focus:ring-red-500 focus:border-red-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire (optionnel)</label>
                                <textarea name="commentaire" id="commentaire" rows="2"
                                    class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('commentaire') }}</textarea>
                            </div>
                            <div>
                                <label for="justificatif" class="block text-sm font-medium text-gray-700">Justificatif (optionnel)</label>
                                <input type="file" name="justificatif" id="justificatif"
                                    class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-xs text-gray-500">Formats acceptés: PDF, JPG, JPEG, PNG (max 2Mo)</p>
                            </div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Êtes-vous sûr de vouloir fermer cette caisse ?')">
                                Fermer la caisse
                            </button>
                        </form>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Résumé des transactions -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Résumé des transactions</h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    @if($cashRegister->est_ouverte && $cashRegister->currentSession())
                    @php
                        $session = $cashRegister->currentSession();
                        $encaissements = $session->transactions()->where('type', 'encaissement')->sum('montant');
                        $decaissements = $session->transactions()->where('type', 'decaissement')->sum('montant');
                        $balance = $encaissements - $decaissements;
                    @endphp
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Session en cours</h4>
                            <p class="text-sm text-gray-900">Ouverte le {{ $session->date_ouverture->format('d/m/Y à H:i') }} par {{ $session->user->name }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-green-800">Encaissements</p>
                                <p class="text-xl font-bold text-green-600">{{ number_format($encaissements, 2, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-red-800">Décaissements</p>
                                <p class="text-xl font-bold text-red-600">{{ number_format($decaissements, 2, ',', ' ') }} FCFA</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-blue-800">Balance</p>
                                <p class="text-xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">{{ number_format($balance, 2, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('cash.transactions.index', ['cash_session_id' => $session->id]) }}" class="text-indigo-600 hover:text-indigo-900">
                                Voir toutes les transactions de cette session →
                            </a>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500 italic">La caisse est actuellement fermée. Aucune session active.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique des sessions -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Historique des sessions</h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'ouverture</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de fermeture</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caissier</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde initial</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solde final</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($cashRegister->sessions()->latest('date_ouverture')->get() as $session)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->date_ouverture->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $session->date_fermeture ? $session->date_fermeture->format('d/m/Y H:i') : 'En cours' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $session->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($session->solde_initial, 2, ',', ' ') }} FCFA</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $session->solde_final ? number_format($session->solde_final, 2, ',', ' ') . ' FCFA' : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('cash.sessions.report', $session) }}" class="text-indigo-600 hover:text-indigo-900">Rapport</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucune session trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection