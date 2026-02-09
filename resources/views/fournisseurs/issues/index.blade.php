@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-xl font-semibold mb-4">Réclamations & incidents</h1>
    <div class="bg-white p-6 rounded shadow">Liste, statuts, échanges (à implémenter)</div>
    <div class="mt-4">
        <a href="{{ route('fournisseurs.index') }}" class="text-primary-600">Retour fournisseurs</a>
    </div>
</div>
@endsection


