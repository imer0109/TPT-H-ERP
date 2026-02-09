@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Départements</h1>
        <a href="{{ route('departments.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
            Créer un département
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Équipes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Membres</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($departments as $department)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $department->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500">{{ $department->description ?? 'Aucune description' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($department->head)
                        <div class="text-sm text-gray-900">{{ $department->head->name }}</div>
                        @else
                        <div class="text-sm text-gray-500">Aucun responsable</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $department->teams()->count() }} équipes
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $department->users()->count() }} membres
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('departments.show', $department) }}" class="text-primary-600 hover:text-primary-900 mr-3">Voir</a>
                        <a href="{{ route('departments.edit', $department) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                        <form action="{{ route('departments.destroy', $department) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        Aucun département trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</div>
@endsection