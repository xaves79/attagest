@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black text-white">
    <!-- Navigation simple -->
    <nav class="container mx-auto px-6 py-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-emerald-400">Attagest</div>
        <div class="space-x-6">
            <a href="#accueil" class="hover:text-emerald-400 transition">Accueil</a>
            <a href="#apropos" class="hover:text-emerald-400 transition">À propos</a>
            <a href="#fonctionnalites" class="hover:text-emerald-400 transition">Fonctionnalités</a>
            <a href="#contact" class="hover:text-emerald-400 transition">Contact</a>
            <a href="{{ route('login') }}" class="bg-emerald-600 px-4 py-2 rounded-lg hover:bg-emerald-700">Connexion</a>
        </div>
    </nav>

    <!-- Section Héro (Accueil) -->
    <section id="accueil" class="container mx-auto px-6 py-24 lg:py-32 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center">
            <div class="lg:pr-12 space-y-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-slate-800/50 border border-slate-700/50">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full mr-3"></span>
                    <span class="text-sm font-medium text-slate-300 uppercase tracking-wider">SaaS Ready</span>
                </div>
                <div class="space-y-6">
                    <h1 class="text-5xl lg:text-7xl font-black bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent leading-tight">
                        Attagest
                        <span class="block bg-gradient-to-r from-emerald-400 via-emerald-500 to-teal-500 bg-clip-text text-transparent mt-4">Gestion d'inventaire</span>
                        <span class="text-3xl lg:text-4xl font-normal text-slate-400 block mt-2 tracking-tight">intelligente & automatisée</span>
                    </h1>
                    <p class="text-xl text-slate-400 leading-relaxed max-w-lg">
                        Transformez votre gestion d'inventaire avec notre solution SaaS moderne.
                        Suivi en temps réel, automatisation avancée et tableaux de bord professionnels.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    <a href="{{ route('login') }}" class="group relative bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold py-4 px-8 rounded-2xl text-lg shadow-2xl shadow-emerald-500/25 hover:shadow-emerald-500/40 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center overflow-hidden">
                        <span class="relative z-10">Commencer gratuitement</span>
                    </a>
                    <a href="#fonctionnalites" class="group flex items-center justify-center font-semibold py-4 px-8 border-2 border-slate-700/50 hover:border-slate-600/80 text-slate-300 hover:text-white rounded-2xl backdrop-blur-sm transition-all duration-300 hover:bg-slate-800/50">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Voir les fonctionnalités
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="relative bg-slate-900/50 backdrop-blur-xl rounded-3xl p-12 border border-slate-800/50 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 group hover:scale-[1.02]">
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="group/stat bg-gradient-to-b from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:scale-105 hover:shadow-emerald-500/20">
                            <div class="text-2xl font-black text-emerald-400 mb-2">247</div>
                            <div class="text-sm text-slate-400 font-medium">Produits stockés</div>
                        </div>
                        <div class="group/stat bg-gradient-to-b from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:scale-105 hover:shadow-emerald-500/20">
                            <div class="text-2xl font-black text-teal-400 mb-2">1.4M€</div>
                            <div class="text-sm text-slate-400 font-medium">Valeur inventaire</div>
                        </div>
                        <div class="group/stat bg-gradient-to-b from-slate-800/50 to-slate-900/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700/50 hover:border-emerald-500/50 transition-all duration-300 hover:scale-105 hover:shadow-emerald-500/20">
                            <div class="text-2xl font-black text-purple-400 mb-2">892</div>
                            <div class="text-sm text-slate-400 font-medium">Ventes ce mois</div>
                        </div>
                        <div class="col-span-2 lg:col-span-3 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 backdrop-blur-sm rounded-2xl p-8 border border-emerald-500/30 text-center group-hover:from-emerald-500/20 group-hover:to-teal-500/20 transition-all duration-300">
                            <div class="text-4xl mb-4">📊</div>
                            <div class="text-lg font-semibold text-white mb-2">Tableaux de bord en temps réel</div>
                            <div class="text-slate-400 text-sm">Suivi instantané de votre activité commerciale</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="apropos" class="bg-slate-800/30 py-24">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white">À propos d'Attagest</h2>
                <div class="w-24 h-1 bg-emerald-500 mx-auto mt-4"></div>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-lg text-slate-300 leading-relaxed">
                        Attagest est une solution complète de gestion agricole conçue pour les acteurs de la filière rizicole. 
                        Elle couvre l'ensemble des processus : achats de paddy, transformation, stocks, ventes, facturation et comptabilité.
                    </p>
                    <p class="text-lg text-slate-300 leading-relaxed mt-4">
                        Notre objectif est de vous offrir une visibilité en temps réel sur votre activité, d'optimiser vos flux et de faciliter la prise de décision grâce à des rapports détaillés.
                    </p>
                </div>
                <div class="bg-slate-900/50 p-8 rounded-3xl border border-slate-700">
                    <h3 class="text-2xl font-semibold text-emerald-400 mb-6">Chiffres clés</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">+50</div>
                            <div class="text-sm text-slate-400">Utilisateurs actifs</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">+1000</div>
                            <div class="text-sm text-slate-400">Transactions/mois</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">99%</div>
                            <div class="text-sm text-slate-400">Satisfaction</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-white">24/7</div>
                            <div class="text-sm text-slate-400">Support</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Fonctionnalités -->
    <section id="fonctionnalites" class="py-24">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white">Fonctionnalités</h2>
                <div class="w-24 h-1 bg-emerald-500 mx-auto mt-4"></div>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">📦</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Gestion des achats</h3>
                    <p class="text-slate-400">Suivi des achats de paddy, gestion des fournisseurs, création automatique de reçus.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">🏭</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Transformation</h3>
                    <p class="text-slate-400">Étuvages, décorticages, suivi de la production et rendements.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Stocks</h3>
                    <p class="text-slate-400">Gestion des stocks de paddy, produits finis, sacs et réservoirs.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Ventes & facturation</h3>
                    <p class="text-slate-400">Création de factures clients, gestion des paiements, suivi des soldes.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">📈</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Bilans & rapports</h3>
                    <p class="text-slate-400">Bilans globaux, rapports d'activité, export PDF et Excel.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur-sm p-8 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition">
                    <div class="text-emerald-400 text-4xl mb-4">👥</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Gestion des utilisateurs</h3>
                    <p class="text-slate-400">Agents, points de vente, rôles et permissions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="bg-slate-800/30 py-24">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white">Contactez-nous</h2>
                <div class="w-24 h-1 bg-emerald-500 mx-auto mt-4"></div>
            </div>
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300">Nom</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md bg-slate-900/50 border border-slate-700 text-white focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md bg-slate-900/50 border border-slate-700 text-white focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-slate-300">Message</label>
                    <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md bg-slate-900/50 border border-slate-700 text-white focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg transition">
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-8">
        <div class="container mx-auto px-6 text-center text-slate-500">
            &copy; {{ date('Y') }} Attagest. Tous droits réservés.
        </div>
    </footer>
</div>
@endsection