<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-700/60 shadow-lg">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between h-16">

            {{-- LOGO --}}
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-attagest.png') }}" alt="Attagest" class="h-8 w-auto">
                    <span class="text-white font-black text-base tracking-tight hidden lg:block">ATTAGEST</span>
                </a>
            </div>

            {{-- NAVIGATION DESKTOP --}}
            <div class="hidden lg:flex items-center gap-1">

                {{-- Dashboards --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>📊</span> Dashboards <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-56 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('dashboard') }}"            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🏠 <span>Dashboard principal</span></a>
                        <a href="{{ route('dashboard.achats') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📥 <span>Dashboard achats</span></a>
                        <a href="{{ route('dashboard.production') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🏭 <span>Dashboard production</span></a>
                        <a href="{{ route('dashboard.stocks') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📦 <span>Dashboard stocks</span></a>
                        <a href="{{ route('dashboard.ventes') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📈 <span>Dashboard ventes</span></a>
                        <a href="{{ route('dashboard.financier') }}"  class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">💰 <span>Dashboard financier</span></a>
                    </div>
                </div>

                {{-- Achats --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>🌾</span> Achats <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-60 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('achats.index') }}"               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-amber-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">🌾 <span>Achats paddy</span></a>
                        <a href="{{ route('achats.nouvelle') }}"            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">➕ <span>Nouvel achat</span></a>
                        <div class="border-t border-slate-700/60 my-1"></div>
                        <a href="{{ route('recus-fournisseurs.crud') }}"    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📄 <span>Reçus fournisseurs</span></a>
                        <a href="{{ route('paiements-fournisseurs.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">💳 <span>Paiements fournisseurs</span></a>
                    </div>
                </div>

                {{-- Transformation --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>⚙️</span> Transformation <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-60 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <div class="px-4 py-1.5 text-xs text-slate-500 uppercase tracking-wider font-semibold">Propre</div>
                        <a href="{{ route('etuvages.index') }}"      class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-blue-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">🔥 <span>Étuvages</span></a>
                        <a href="{{ route('lots-riz-etuve') }}"      class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🍚 <span>Lots riz étuvé</span></a>
                        <a href="{{ route('decorticages.index') }}"  class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">⚙️ <span>Décorticages</span></a>
                        <a href="{{ route('ensachage.index') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-green-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">🎒 <span>Ensachage</span></a>
                        <div class="border-t border-slate-700/60 my-1"></div>
                        <div class="px-4 py-1.5 text-xs text-slate-500 uppercase tracking-wider font-semibold">Clients</div>
                        <a href="{{ route('traitements-clients') }}"   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🧪 <span>Traitements clients</span></a>
                        <a href="{{ route('paiements-traitements') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">💰 <span>Paiements traitements</span></a>
                    </div>
                </div>

                {{-- Stocks --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>📦</span> Stocks <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-60 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('stocks-paddy.index') }}"    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🌾 <span>Stocks paddy</span></a>
                        <a href="{{ route('stocks-produits-finis') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🍚 <span>Produits finis</span></a>
                        <a href="{{ route('sacs-produits-finis') }}"   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🎒 <span>Sacs produits finis</span></a>
                        <a href="{{ route('stocks-sacs') }}"           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🛍️ <span>Stocks sacs / point</span></a>
                        <a href="{{ route('mouvements-sacs') }}"       class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🔄 <span>Mouvements sacs</span></a>
                        <a href="{{ route('mouvements-reservoirs') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🏭 <span>Mouvements réservoirs</span></a>
                    </div>
                </div>

                {{-- Ventes --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>🛒</span> Ventes <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-56 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('commandes.index') }}"   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-green-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">📋 <span>Commandes</span></a>
                        <a href="{{ route('commandes.nouvelle') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">➕ <span>Nouvelle commande</span></a>
                        <div class="border-t border-slate-700/60 my-1"></div>
                        <a href="{{ route('factures-clients') }}"   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📄 <span>Factures clients</span></a>
                        <a href="{{ route('paiements-factures') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">💰 <span>Paiements factures</span></a>
                        <a href="{{ route('ventes') }}"             class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📊 <span>Journal des ventes</span></a>
                    </div>
                </div>

                {{-- Comptabilité --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>📒</span> Comptabilité <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-60 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('comptabilite.index') }}"    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-violet-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">📒 <span>Comptabilité</span></a>
                        <div class="border-t border-slate-700/60 my-1"></div>
                        <a href="{{ route('comptes') }}"               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📒 <span>Plan comptable</span></a>
                        <a href="{{ route('ecritures-comptables') }}"  class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">✍️ <span>Écritures comptables</span></a>
                        <a href="{{ route('pieces-comptables') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📑 <span>Pièces comptables</span></a>
                        <div class="border-t border-slate-700/60 my-1"></div>
                        <a href="{{ route('bilans.globaux') }}"        class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📈 <span>Bilans globaux</span></a>
                        <a href="{{ route('rapports') }}"              class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📑 <span>Rapports</span></a>
                    </div>
                </div>

                {{-- Référentiels --}}
                <div class="relative" x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false">
                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition">
                        <span>🔧</span> Référentiels <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 top-full mt-1 w-56 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <a href="{{ route('clients.index') }}"     class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">👥 <span>Clients</span></a>
                        <a href="{{ route('fournisseurs.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🚚 <span>Fournisseurs</span></a>
                        <a href="{{ route('agents') }}"            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">👤 <span>Agents</span></a>
                        <a href="{{ route('points-vente') }}"      class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🏪 <span>Points de vente</span></a>
                        <a href="{{ route('varietes.index') }}"    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🌾 <span>Variétés</span></a>
                        <a href="{{ route('localites.index') }}"   class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📍 <span>Localités</span></a>
                        <a href="{{ route('entreprises.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🏢 <span>Entreprises</span></a>
                        <a href="{{ route('articles.index') }}"    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">🛒 <span>Articles</span></a>
                        <a href="{{ route('postes.index') }}"      class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">📝 <span>Postes</span></a>
						<a href="{{ route('parametres.prix') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">💰 <span>Gestion des prix</span></a>
						<div class="border-t border-slate-700/60 my-1"></div>
                        <a href="{{ route('parametres.index') }}"  class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-violet-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">⚙️ <span>Paramètres app</span></a>
                        <a href="{{ route('admin.panel') }}"       class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">🛡️ <span>Administration</span></a>
						<div class="border-t border-slate-700/60 my-1"></div>
						<a href="{{ route('journal.intervenants') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-cyan-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">📋 <span>Journal des intervenants</span></a>
						<a href="{{ route('gestion.utilisateurs') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">🛡️ <span>Gestion utilisateurs</span></a>
						<a href="{{ route('documentation') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-blue-300 hover:text-white hover:bg-slate-700/60 transition font-semibold">📚 <span>Documentation</span></a>
					</div>
                </div>

            </div>

            {{-- USER MENU --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('commandes.nouvelle') }}"
                   class="px-3 py-1.5 bg-green-700/80 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition active:scale-95 flex items-center gap-1.5">
                    ➕ Commande
                </a>
                <a href="{{ route('achats.nouvelle') }}"
                   class="px-3 py-1.5 bg-amber-700/80 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition active:scale-95 flex items-center gap-1.5">
                    🌾 Achat
                </a>

                <div class="relative" x-data="{ open: false }" @click.outside="open=false">
                    <button @click="open=!open"
                            class="flex items-center gap-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-sm text-slate-300 hover:text-white transition">
                        <div class="w-6 h-6 rounded-full bg-amber-600 flex items-center justify-center text-xs font-bold text-white">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="max-w-24 truncate text-xs font-medium">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                        <span class="text-slate-600 text-xs">▾</span>
                    </button>
                    <div x-show="open" x-transition
                         class="absolute right-0 top-full mt-1 w-48 bg-slate-800 border border-slate-700/60 rounded-xl shadow-2xl py-1.5 z-50">
                        <div class="px-4 py-2 border-b border-slate-700/60">
                            <p class="text-xs text-white font-semibold truncate">{{ Auth::user()->name ?? '' }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('parametres.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-700/60 transition">⚙️ Paramètres</a>
                        <a href="{{ route('admin.panel') }}"      class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-300 hover:text-white hover:bg-slate-700/60 transition">🛡️ Administration</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:text-white hover:bg-red-900/40 transition">
                                🚪 Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- HAMBURGER --}}
            <div class="flex items-center lg:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden border-t border-slate-700/60 bg-slate-900">
        <div class="px-4 py-4 space-y-1 max-h-screen overflow-y-auto">

            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Dashboards</div>
            <a href="{{ route('dashboard') }}"            class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🏠 Dashboard</a>
            <a href="{{ route('dashboard.achats') }}"     class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📥 Achats</a>
            <a href="{{ route('dashboard.production') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🏭 Production</a>
            <a href="{{ route('dashboard.ventes') }}"     class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📈 Ventes</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Achats</div>
            <a href="{{ route('achats.index') }}"               class="block px-3 py-2 text-sm text-amber-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">🌾 Achats paddy</a>
            <a href="{{ route('recus-fournisseurs.crud') }}"    class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📄 Reçus fournisseurs</a>
            <a href="{{ route('paiements-fournisseurs.index') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">💳 Paiements fournisseurs</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Transformation</div>
            <a href="{{ route('etuvages.index') }}"      class="block px-3 py-2 text-sm text-blue-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">🔥 Étuvages</a>
            <a href="{{ route('lots-riz-etuve') }}"      class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🍚 Lots riz étuvé</a>
            <a href="{{ route('decorticages.index') }}"  class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">⚙️ Décorticages</a>
            <a href="{{ route('ensachage.index') }}"     class="block px-3 py-2 text-sm text-green-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">🎒 Ensachage</a>
            <a href="{{ route('traitements-clients') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🧪 Traitements clients</a>
            <a href="{{ route('paiements-traitements') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">💰 Paiements traitements</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Stocks</div>
            <a href="{{ route('stocks-paddy.index') }}"    class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🌾 Stocks paddy</a>
            <a href="{{ route('stocks-produits-finis') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🍚 Produits finis</a>
            <a href="{{ route('sacs-produits-finis') }}"   class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🎒 Sacs produits finis</a>
            <a href="{{ route('stocks-sacs') }}"           class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🛍️ Stocks sacs</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Ventes</div>
            <a href="{{ route('commandes.index') }}"    class="block px-3 py-2 text-sm text-green-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">📋 Commandes</a>
            <a href="{{ route('commandes.nouvelle') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">➕ Nouvelle commande</a>
            <a href="{{ route('factures-clients') }}"   class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📄 Factures clients</a>
            <a href="{{ route('paiements-factures') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">💰 Paiements factures</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Comptabilité</div>
            <a href="{{ route('comptabilite.index') }}"   class="block px-3 py-2 text-sm text-violet-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">📒 Comptabilité</a>
            <a href="{{ route('bilans.globaux') }}"        class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📈 Bilans globaux</a>
            <a href="{{ route('rapports') }}"              class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">📑 Rapports</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            <div class="text-xs text-slate-500 uppercase tracking-wider font-semibold px-2 py-1">Référentiels</div>
            <a href="{{ route('clients.index') }}"     class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">👥 Clients</a>
            <a href="{{ route('fournisseurs.index') }}" class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">🚚 Fournisseurs</a>
            <a href="{{ route('agents') }}"            class="block px-3 py-2 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition">👤 Agents</a>
            <a href="{{ route('parametres.index') }}"  class="block px-3 py-2 text-sm text-violet-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">⚙️ Paramètres app</a>
            <a href="{{ route('admin.panel') }}"       class="block px-3 py-2 text-sm text-red-300 hover:text-white hover:bg-slate-800 rounded-lg transition font-semibold">🛡️ Administration</a>

            <div class="border-t border-slate-700/60 my-2"></div>
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-400 hover:text-white hover:bg-red-900/40 rounded-lg transition">
                    🚪 Déconnexion
                </button>
            </form>
            @endauth
        </div>
    </div>
</nav>