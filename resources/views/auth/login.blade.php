<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CONNECTION | TPT-H ERP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <style>

        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200">

    <div class="w-full max-w-md px-6">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <div class="bg-white rounded-2xl shadow-lg p-4">
                <img src="{{ asset('images/logo-tpt.png') }}" alt="Logo TPT" class="h-12 mx-auto">
            </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">
                TPT-H ERP
            </h1>
            <!-- <p class="mt-2 text-sm text-slate-500">
                Accédez à votre espace de gestion sécurisé
            </p> -->
        </div>

        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl px-8 py-10">

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Adresse email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                               focus:border-primary-600 focus:ring-2 focus:ring-primary-500/30
                               transition outline-none"
                        placeholder="exemple@tpt-h.com"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Mot de passe
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm
                               focus:border-primary-600 focus:ring-2 focus:ring-primary-500/30
                               transition outline-none"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                        >
                        Se souvenir de moi
                    </label>

                    <a href="{{ route('password.request') }}"
                       class="text-primary-600 hover:text-primary-700 font-medium transition">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full rounded-xl bg-primary-600 py-3 text-white font-semibold
                           hover:bg-primary-700 transition-all duration-200
                           focus:ring-2 focus:ring-primary-500/40 focus:outline-none
                           active:scale-[0.98]"
                >
                    Se connecter
                </button>
            </form>

            <!-- Divider -->
            <div class="my-8 flex items-center gap-3">
                <div class="h-px w-full bg-slate-200"></div>
                <span class="text-xs text-slate-400">Sécurisé</span>
                <div class="h-px w-full bg-slate-200"></div>
            </div>

            <p class="text-center text-xs text-slate-500">
                Plateforme sécurisée TPT-H ERP
            </p>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-xs text-slate-500">
            © {{ date('Y') }} TPT International — Tous droits réservés
        </p>
    </div>

</body>
</html>