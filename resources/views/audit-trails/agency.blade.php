@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Historique des Modifications</h1>
            <p class="text-gray-600">Agence: {{ $agency->nom }}</p>
        </div>
        <a href="{{ route('agencies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Retour aux agences
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Date & Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Description</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($auditTrails as $trail)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $trail->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @switch($trail->action)
                                @case('created')
                                    bg-green-100 text-green-800
                                    @break
                                @case('updated')
                                    bg-primary-100 text-primary-800
                                    @break
                                @case('deleted')
                                    bg-red-100 text-red-800
                                    @break
                                @case('archived')
                                @case('reactivated')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('duplicated')
                                    bg-purple-100 text-purple-800
                                    @break
                            @endswitch">
                            {{ ucfirst($trail->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $trail->user?->name ?? 'Système' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $trail->description }}
                        @if($trail->changes)
                            <div class="mt-1 text-xs">
                                <details>
                                    <summary class="text-primary-600 cursor-pointer">Voir les détails</summary>
                                    <pre class="bg-gray-100 p-2 mt-1 rounded">{{ json_encode($trail->changes, JSON_PRETTY_PRINT) }}</pre>
                                </details>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucun historique trouvé pour cette agence.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-50">
            {{ $auditTrails->links() }}
        </div>
    </div>
</div>
@endsection