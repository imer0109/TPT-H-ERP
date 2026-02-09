@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-xl font-semibold mb-4">Nouvelle réclamation / incident</h1>
    <form method="post" action="{{ route('fournisseurs.issues.store') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fournisseur</label>
                <select name="fournisseur_id" class="form-select w-full" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach($fournisseurs as $id => $rs)
                        <option value="{{ $id }}">{{ $rs }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select name="type" class="form-select w-full">
                    <option value="retard">Retard</option>
                    <option value="non_conformite">Non conformité</option>
                    <option value="facturation">Facturation</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium mb-1">Titre</label>
                <input type="text" name="titre" class="form-input w-full" required />
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" class="form-textarea w-full"></textarea>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('fournisseurs.issues.index') }}" class="btn">Annuler</a>
        </div>
    </form>
</div>
@endsection


