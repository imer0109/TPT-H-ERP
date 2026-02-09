@extends('layouts.app')

@section('title', $service->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $service->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $service->description }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('services.edit', $service->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit"></i> Éditer
                    </a>
                    <a href="{{ route('services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Retour
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Contenu du service</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($service->content)) !!}
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Créé le :</span>
                        {{ $service->created_at ? $service->created_at->format('d/m/Y H:i') : 'Non défini' }}
                    </div>
                    <div>
                        <span class="font-semibold">Mis à jour le :</span>
                        {{ $service->updated_at ? $service->updated_at->format('d/m/Y H:i') : 'Non défini' }}
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
                        <i class="fas fa-trash"></i> Supprimer le service
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection