@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">
        <!-- Partie gauche - Marque -->
        <div class="hidden md:block bg-gradient-to-br from-emerald-600 to-teal-700 p-12 text-white">
            <div class="h-full flex flex-col justify-center">
                <h2 class="text-3xl font-bold mb-4">Bienvenue !</h2>
                <p class="text-emerald-100 leading-relaxed">
                    Gérez votre activité agricole en toute simplicité. Suivez vos achats, stocks, ventes et production en temps réel.
                </p>
                <div class="mt-8">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center text-emerald-100 hover:text-white transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>

        <!-- Partie droite - Formulaire de connexion -->
        <div class="p-8 md:p-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Salut !</h2>
            <p class="text-sm text-gray-600 mb-8">Connectez-vous à votre compte</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition pr-12">
                        <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800 focus:outline-none">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
				
				<!-- Lien mot de passe oublié (à activer plus tard si besoin) -->
				<div class="flex items-center justify-between">
					<div class="flex items-center">
						<input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
						<label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
					</div>
					<a href="{{ route('password.request') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Mot de passe oublié ?</a>
				</div>

                <!-- Bouton de connexion -->
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200">
                    Se connecter
                </button>

                <!-- Lien vers l'inscription -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-700">Créer un compte</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Inclusion du script pour l'icône œil --}}
<script src="{{ asset('js/password-toggle.js') }}"></script>