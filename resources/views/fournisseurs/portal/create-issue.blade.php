@extends('fournisseurs.portal.layout')

@section('title', 'Nouvelle Réclamation')
@section('header', 'Nouvelle Réclamation')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="mx-auto max-w-4xl px-4">

        <!-- CARD -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-6 text-xl font-semibold text-gray-800">
                Créer une nouvelle réclamation
            </h2>

            <form action="{{ route('supplier.portal.store-issue') }}" method="POST">
                @csrf

                <div class="space-y-6">

                    <!-- TYPE -->
                    <div>
                        <label for="type" class="mb-1 block text-sm font-medium text-gray-700">
                            Type de réclamation <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="type"
                            id="type"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm
                                   focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-offset-0"
                        >
                            <option value="">Sélectionnez un type</option>
                            <option value="retard" {{ old('type') === 'retard' ? 'selected' : '' }}>Retard</option>
                            <option value="produit_non_conforme" {{ old('type') === 'produit_non_conforme' ? 'selected' : '' }}>
                                Produit non conforme
                            </option>
                            <option value="erreur_facturation" {{ old('type') === 'erreur_facturation' ? 'selected' : '' }}>
                                Erreur de facturation
                            </option>
                            <option value="autre" {{ old('type') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TITRE -->
                    <div>
                        <label for="titre" class="mb-1 block text-sm font-medium text-gray-700">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="titre"
                            id="titre"
                            value="{{ old('titre') }}"
                            placeholder="Ex : Retard de livraison commande #124"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                        >
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DESCRIPTION -->
                    <div>
                        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">
                            Description détaillée <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="6"
                            placeholder="Décrivez clairement le problème rencontré..."
                            class="block w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm
                                   focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                    <a
                        href="{{ route('supplier.portal.issues') }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5
                               text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                    >
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-primary-600 px-5 py-2.5
                               text-sm font-medium text-white shadow hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Soumettre la réclamation
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
