@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Nouvelle Société</h1>
        <a href="{{ route('companies.index') }}" class="text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-4">
                {{-- Raison sociale --}}
                <div>
                    <label for="raison_sociale" class="block text-sm font-medium text-gray-700">
                        Raison Sociale <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="raison_sociale" id="raison_sociale"
                           value="{{ old('raison_sociale') }}"
                           class="mt-1 focus:ring-red-500  focus:border-red-500 block w-full py-2 shadow-sm sm:text-sm border border-gray-300 rounded-md" required>
                    @error('raison_sociale')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="holding" {{ old('type') == 'holding' ? 'selected' : '' }}>Holding</option>
                        <option value="filiale" {{ old('type') == 'filiale' ? 'selected' : '' }}>Filiale</option>
                    </select>
                </div>

                {{-- NIU --}}
                <div>
                    <label for="niu" class="block text-sm font-medium text-gray-700">NIU</label>
                    <input type="text" name="niu" id="niu" value="{{ old('niu') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full py-2 border shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('niu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RCCM --}}
                <div>
                    <label for="rccm" class="block text-sm font-medium text-gray-700">RCCM</label>
                    <input type="text" name="rccm" id="rccm" value="{{ old('rccm') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('rccm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Régime Fiscal --}}
                <div>
                    <label for="regime_fiscal" class="block text-sm font-medium text-gray-700">Régime Fiscal</label>
                    <input type="text" name="regime_fiscal" id="regime_fiscal" value="{{ old('regime_fiscal') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('regime_fiscal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Secteur d'activité --}}
                <div>
                    <label for="secteur_activite" class="block text-sm font-medium text-gray-700">
                        Secteur d'Activité <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="secteur_activite" id="secteur_activite"
                           value="{{ old('secteur_activite') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('secteur_activite')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Devise --}}
                <div>
                    <label for="devise" class="block text-sm font-medium text-gray-700">
                        Devise <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="devise" id="devise"
                           value="{{ old('devise') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('devise')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pays --}}
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700">
                        Pays <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pays" id="pays"
                           value="{{ old('pays') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('pays')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ville --}}
                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700">
                        Ville <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ville" id="ville"
                           value="{{ old('ville') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                    @error('ville')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Siège social --}}
                <div class="col-span-2">
                    <label for="siege_social" class="block text-sm font-medium text-gray-700">
                        Siège Social <span class="text-red-500">*</span>
                    </label>
                    <textarea name="siege_social" id="siege_social" rows="3"
                              class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('siege_social') }}</textarea>
                    @error('siege_social')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="telephone" id="telephone"
                           value="{{ old('telephone') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('telephone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp"
                           value="{{ old('whatsapp') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Site web --}}
                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700">Site Web</label>
                    <input type="url" name="site_web" id="site_web"
                           value="{{ old('site_web') }}"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 py-2 border block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    @error('site_web')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Société Mère --}}
                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Société Mère</label>
                    <select name="parent_id" id="parent_id"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                        <option value="">Aucune</option>
                        @foreach($holdings as $company)
                            <option value="{{ $company->id }}" {{ old('parent_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Logo --}}
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" id="logo"
                           accept="image/*"
                           onchange="document.getElementById('logo-preview').src = window.URL.createObjectURL(this.files[0])"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <img id="logo-preview" class="mt-3 w-24 h-24 object-contain border" />
                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Visuel --}}
                <div>
                    <label for="visuel" class="block text-sm font-medium text-gray-700">Visuel</label>
                    <input type="file" name="visuel" id="visuel"
                           accept="image/*"
                           onchange="document.getElementById('visuel-preview').src = window.URL.createObjectURL(this.files[0])"
                           class="mt-1 focus:ring-red-500 focus:border-red-500 block py-2 border w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <img id="visuel-preview" class="mt-3 w-24 h-24 object-contain border" />
                    @error('visuel')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                    Créer la société
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
