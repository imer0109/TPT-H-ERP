@extends('layouts.test')

@section('title', 'Test avec Layout Simplifié')

@section('content')
<div class="container">
    <h2>Page de test avec layout simplifié</h2>
    <p>Date: {{ date('Y-m-d H:i:s') }}</p>
    <p>Cette page utilise un layout simplifié sans Alpine.js ni vérifications d'authentification complexes.</p>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection