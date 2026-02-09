@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold flex items-center space-x-2">
                <i class="fas fa-sliders-h"></i>
                <span>Paramètres Généraux</span>
            </h3>
            <a href="{{ route('accounting.settings.index') }}" 
               class="btn-secondary inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-gray-100 text-gray-700 hover:bg-gray-200">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('accounting.settings.parameters.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Exercice Comptable -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                        <i class="fas fa-calendar-alt"></i> <span>Exercice Comptable</span>
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600">Date de début de l'exercice *</label>
                            <input type="date" name="fiscal_year_start" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                   value="{{ old('fiscal_year_start', date('Y') . '-01-01') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-600">Date de fin de l'exercice *</label>
                            <input type="date" name="fiscal_year_end" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                   value="{{ old('fiscal_year_end', date('Y') . '-12-31') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Paramètres Généraux -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                        <i class="fas fa-cogs"></i> <span>Paramètres Généraux</span>
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600">Devise par défaut *</label>
                            <select name="default_currency" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                                <option value="EUR" {{ old('default_currency', 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="USD" {{ old('default_currency', 'EUR') == 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                                <option value="XOF" {{ old('default_currency', 'EUR') == 'XOF' ? 'selected' : '' }}>Franc CFA (CFA)</option>
                                <option value="XAF" {{ old('default_currency', 'EUR') == 'XAF' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="auto_numbering" name="auto_numbering" class="h-5 w-5 text-primary-600" {{ old('auto_numbering', true) ? 'checked' : '' }}>
                            <label for="auto_numbering" class="text-gray-700">Numérotation automatique des écritures</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="validation_required" name="validation_required" class="h-5 w-5 text-primary-600" {{ old('validation_required', true) ? 'checked' : '' }}>
                            <label for="validation_required" class="text-gray-700">Validation requise pour les écritures</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paramètres par Société -->
            <div class="bg-white shadow rounded-lg p-4">
                <h5 class="font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                    <i class="fas fa-building"></i> <span>Paramètres par Société</span>
                </h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-primary-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-primary-700">Société</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-primary-700">Code Comptable</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-primary-700">Plan Comptable</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-primary-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($companies as $company)
                                <tr>
                                    <td class="px-4 py-2">{{ $company->raison_sociale }}</td>
                                    <td class="px-4 py-2">{{ $company->code_comptable ?? 'Non défini' }}</td>
                                    <td class="px-4 py-2">{{ $company->chartOfAccount ? 'Configuré' : 'Non configuré' }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('companies.edit', $company) }}" 
                                           class="text-primary-600 hover:text-primary-800 font-medium">
                                           <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">Aucune société trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 flex items-center space-x-2">
                    <i class="fas fa-save"></i> <span>Enregistrer les paramètres</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
