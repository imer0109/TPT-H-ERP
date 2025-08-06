@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Modifier la Société</h1>
        <a href="{{ route('companies.index') }}" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
                {{-- Raison sociale --}}
                <div>
                    <label for="raison_sociale" class="block text-sm font-medium text-gray-700">
                        Raison Sociale <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="raison_sociale" id="raison_sociale"
                           value="{{ old('raison_sociale', $company->raison_sociale) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('raison_sociale')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="holding" {{ old('type', $company->type) == 'holding' ? 'selected' : '' }}>Holding</option>
                        <option value="filiale" {{ old('type', $company->type) == 'filiale' ? 'selected' : '' }}>Filiale</option>
                    </select>
                </div>

                {{-- NIU --}}
                <div>
                    <label for="niu" class="block text-sm font-medium text-gray-700">NIU</label>
                    <input type="text" name="niu" id="niu"
                           value="{{ old('niu', $company->niu) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('niu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RCCM --}}
                <div>
                    <label for="rccm" class="block text-sm font-medium text-gray-700">RCCM</label>
                    <input type="text" name="rccm" id="rccm"
                           value="{{ old('rccm', $company->rccm) }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('rccm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Les autres champs sont identiques au create mais avec old(..., $company->champ) --}}
                {{-- ... --}}

                {{-- Société Mère --}}
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Société Mère</label>
                    <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Aucune</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" {{ old('parent_id', $company->parent_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Bouton --}}
            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
