@extends('layouts.app')

@section('title', 'Services')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Services</h1>
        <a href="{{ route('services.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
            Ajouter un Service
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Titre
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date de création
                    </th>
                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-900">{{ $service->title }}</div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm text-gray-900">{{ Illuminate\Support\Str::limit($service->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="text-sm text-gray-900">{{ $service->created_at ? $service->created_at->format('d/m/Y H:i') : 'Non défini' }}</div>
                        </td>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <div class="flex space-x-2">
                                <a href="{{ route('services.show', $service->id) }}" class="text-primary-600 hover:text-primary-900">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="{{ route('services.edit', $service->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i> Éditer
                                </a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 border-b border-gray-200 text-center text-gray-500">
                            Aucun service trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection