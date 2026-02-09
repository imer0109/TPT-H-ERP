@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Codes de récupération 2FA</h1>
            
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Ces codes de récupération peuvent être utilisés pour accéder à votre compte si vous ne pouvez pas générer un code d'authentification à deux facteurs.
                </p>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">Important :</h3>
                    <ul class="list-disc list-inside text-yellow-700 space-y-1">
                        <li>Conservez ces codes en lieu sûr</li>
                        <li>Chaque code ne peut être utilisé qu'une seule fois</li>
                        <li>Vous pouvez régénérer ces codes à tout moment</li>
                    </ul>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @foreach($recoveryCodes as $code)
                <div class="bg-gray-100 rounded-lg p-3 text-center font-mono text-sm">
                    {{ $code }}
                </div>
                @endforeach
            </div>
            
            <div class="flex flex-wrap gap-4 mb-6">
                <form action="{{ route('2fa.regenerate-recovery') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                        Régénérer les codes
                    </button>
                </form>
                
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Imprimer les codes
                </button>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('2fa.setup') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Retour à la configuration 2FA
                </a>
            </div>
        </div>
    </div>
</div>
@endsection