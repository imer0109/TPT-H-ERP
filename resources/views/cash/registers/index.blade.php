@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gestion des Caisses</h1>
        <a href="{{ route('cash.registers.create') }}" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded">
            Nouvelle Caisse
        </a>
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

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Entité</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Solde actuel</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Statut</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cashRegisters as $cashRegister)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cashRegister->nom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashRegister->type === 'principale' ? 'bg-primary-100 text-primary-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $cashRegister->type === 'principale' ? 'Principale' : 'Secondaire' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($cashRegister->entity)
                            @if($cashRegister->entity instanceof \App\Models\Company)
                                Société : {{ $cashRegister->entity->raison_sociale }}
                            @elseif($cashRegister->entity instanceof \App\Models\Agency)
                                Agence : {{ $cashRegister->entity->nom }}
                            @else
                                {{ class_basename($cashRegister->entity_type) }} : {{ $cashRegister->entity->nom ?? $cashRegister->entity->raison_sociale ?? 'N/A' }}
                            @endif
                        @else
                            Non assignée
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($cashRegister->solde_actuel, 2, ',', ' ') }} FCFA</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashRegister->est_ouverte ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $cashRegister->est_ouverte ? 'Ouverte' : 'Fermée' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('cash.registers.show', ['cashRegister' => $cashRegister->id]) }}" class="text-primary-600 hover:text-primary-900 mr-3">Détails</a>
                        <a href="{{ route('cash.registers.edit', ['cashRegister' => $cashRegister->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                        <form action="{{ route('cash.registers.destroy', ['cashRegister' => $cashRegister->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette caisse ?')">
                                Supprimer
                            </button> 
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Aucune caisse trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cashRegisters->links() }}
    </div>
</div>
@endsection