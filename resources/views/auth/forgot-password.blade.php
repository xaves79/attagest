@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">
        <!-- Partie gauche - Marque -->
        <div class="hidden md:block bg-gradient-to-br from-emerald-600 to-teal-700 p-12 text-white">
            <div class="h-full flex flex-col justify-center">
                <h2 class="text-3xl font-bold mb-4">Mot de passe oublié ?</h2>
                <p class="text-emerald-100 leading-relaxed">
                    Saisissez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
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

        <div class="p-8 md:p-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Réinitialisation</h2>
            <p class="text-sm text-gray-600 mb-8">Entrez votre adresse e-mail</p>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200">
                    Envoyer le lien
                </button>

                <p class="mt-6 text-center text-sm text-gray-600">
                    <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-700">Retour à la connexion</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection