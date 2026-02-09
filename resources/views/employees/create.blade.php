@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 to-gray-100 py-10 px-4">
    <div class="max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-10 border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4"> Nouvel Employé</h2>

        <form action="{{ route('hr.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                <!-- Informations Personnelles -->
                <div>
                    <h3 class="text-xl font-semibold text-primary-700 mb-5">Informations Personnelles</h3>

                    <div class="space-y-5">
                        <!-- Matricule -->
                        <div>
                            <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule*</label>
                            <input
                                type="text"
                                id="matricule"
                                name="matricule"
                                value="{{ old('matricule') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            @error('matricule')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Nom*</label>
                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom*</label>
                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de Naissance*</label>
                            <input
                                type="date"
                                id="birth_date"
                                name="birth_date"
                                value="{{ old('birth_date') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            @error('birth_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Place -->
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700">Lieu de Naissance</label>
                            <input
                                type="text"
                                id="birth_place"
                                name="birth_place"
                                value="{{ old('birth_place') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            @error('birth_place')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationalité</label>
                            <input
                                type="text"
                                id="nationality"
                                name="nationality"
                                value="{{ old('nationality') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                            >
                            @error('nationality')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Genre*</label>
                            <select
                                id="gender"
                                name="gender"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                                <option value="">Sélectionner le genre</option>
                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID Card Number -->
                        <div>
                            <label for="id_card_number" class="block text-sm font-medium text-gray-700">Numéro de pièce d'identité*</label>
                            <input
                                type="text"
                                id="id_card_number"
                                name="id_card_number"
                                value="{{ old('id_card_number') }}"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                required
                            >
                            @error('id_card_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <textarea
                                id="address"
                                name="address"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200 px-3 py-2 text-gray-800 placeholder-gray-400"
                                rows="3"
                            >{{ old('address') }}</textarea>
                            @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations Professionnelles -->
                <div>
                    <h3 class="text-xl font-semibold text-primary-700 mb-5">Informations Professionnelles</h3>

                    <div class="space-y-5">
                        <!-- Poste -->
                        <div>
                            <label for="current_position_id" class="block text-sm font-medium text-gray-700">Poste*</label>
                            <select id="current_position_id" name="current_position_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner un poste</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('current_position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('current_position_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Professionnel*</label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                   required>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone*</label>
                            <input type="tel" id="phone" name="phone"
                                   value="{{ old('phone') }}"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                   required>
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Embauche -->
                        <div>
                            <label for="date_embauche" class="block text-sm font-medium text-gray-700">Date d'Embauche*</label>
                            <input type="date" id="date_embauche" name="date_embauche"
                                   value="{{ old('date_embauche') }}"
                                   class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                   required>
                            @error('date_embauche')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company -->
                        <div>
                            <label for="current_company_id" class="block text-sm font-medium text-gray-700">Entreprise*</label>
                            <select id="current_company_id" name="current_company_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner une entreprise</option>
                                @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('current_company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                            @error('current_company_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Agency -->
                        <div>
                            <label for="current_agency_id" class="block text-sm font-medium text-gray-700">Agence*</label>
                            <select id="current_agency_id" name="current_agency_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2"
                                required>
                                <option value="">Sélectionner une agence</option>
                                @foreach($agencies as $id => $name)
                                <option value="{{ $id }}" {{ old('current_agency_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                                @endforeach
                            </select>
                            @error('current_agency_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Superviseur -->
                        <div>
                            <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Superviseur</label>
                            <select id="supervisor_id" name="supervisor_id"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 px-3 py-2">
                                <option value="">Sélectionner un superviseur</option>
                                @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->first_name }} {{ $supervisor->last_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('supervisor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-10 flex justify-end gap-4 border-t pt-6">
                <a href="{{ route('hr.employees.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200 shadow-sm">
                   Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition duration-200 shadow-sm">
                        Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
