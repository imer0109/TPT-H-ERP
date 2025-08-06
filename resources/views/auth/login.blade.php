<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CONNEXION - TPT-H ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-gray-100 via-white to-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg space-y-6">
            <!-- Logo et Titre -->
            <div class="text-center">
                <div class="flex items-center justify-center space-x-1">
                    <img class="h-16 w-auto" src="/images/logo-tpt.png" alt="TPT-H ERP">
                    <span class="text-2xl font-bold text-gray-800">TPT-H ERP</span>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">
                    Connexion à votre compte
                </h2>
                <p class="text-gray-500 text-sm mt-1">Veuillez entrer vos informations</p>
            </div>

            <!-- Messages d'erreurs -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire -->
            <form class="space-y-5" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full px-4 py-2 text-gray-800 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                            placeholder="exemple@email.com" value="{{ old('email') }}">
                    </div>
                    <!-- Mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full px-4 py-2 text-gray-800 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm transition"
                            placeholder="Votre mot de passe">
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-red-600 hover:underline font-medium">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Bouton -->
                <div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-red-600 text-white font-medium text-sm rounded-lg shadow hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        <svg class="h-5 w-5 text-red-100" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
