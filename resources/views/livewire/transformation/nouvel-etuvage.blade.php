<div class="min-h-screen bg-slate-950 text-white">

    {{-- BARRE SUPÉRIEURE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('etuvages.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm group">
                    <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span> Étuvages
                </a>
                <div class="h-5 w-px bg-slate-700"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-sm">🔥</div>
                    <div>
                        <h1 class="text-sm font-bold text-white leading-none">Gestion des étuvages</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Lancement · Suivi · Clôture</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">

        {{-- MESSAGES --}}
        @if($successMessage)
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 flex items-center gap-3 text-green-300">
            <span class="text-lg">✅</span>
            <p class="text-sm font-medium">{{ $successMessage }}</p>
        </div>
        @endif
        @if($errorMessage)
        <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm">{{ $errorMessage }}</p>
        </div>
        @endif

        {{-- GRILLE : FORMULAIRE + ÉTUVAGES EN COURS --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- FORMULAIRE LANCEMENT (3/5) --}}
            <div class="lg:col-span-3 bg-slate-900 border border-blue-700/30 rounded-2xl p-6 space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-700 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-lg">🔥</div>
                    <div>
                        <h2 class="text-sm font-bold text-white">Lancer un étuvage</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Le paddy sera débité du stock immédiatement</p>
                    </div>
                </div>

                {{-- Lot paddy --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Lot paddy <span class="text-red-400">*</span></label>
                    <select wire:model.live="lot_paddy_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                        <option value="">— Sélectionner un lot disponible —</option>
                        @foreach($lots as $lot)
                        <option value="{{ $lot->id }}">
                            {{ $lot->code_lot }} — {{ $lot->variete_nom ?? '?' }} · {{ $lot->fournisseur_nom ?? '?' }}
                            · {{ number_format($lot->quantite_restante_kg, 0, ',', ' ') }} kg dispo
                        </option>
                        @endforeach
                    </select>
                    @if($stockDisponible > 0)
                    <p class="text-blue-400 text-xs mt-1.5 flex items-center gap-1">
                        <span>📦</span> Stock disponible : <span class="font-bold ml-1">{{ number_format($stockDisponible, 0, ',', ' ') }} kg</span>
                    </p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Agent --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Agent responsable</label>
                        <select wire:model="agent_id"
                                class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                            <option value="">— Sélectionner —</option>
                            @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quantité --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Quantité à étuver (kg) <span class="text-red-400">*</span></label>
                        <input type="number" wire:model="quantite_paddy_entree_kg" min="1" step="0.1"
                               placeholder="ex: 5000"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    {{-- Date début --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date & heure début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" wire:model="date_debut_etuvage"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    {{-- Température --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Température (°C)</label>
                        <input type="number" wire:model="temperature_etuvage" min="0" step="0.1"
                               placeholder="ex: 80"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    {{-- Durée --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Durée prévue (minutes)</label>
                        <input type="number" wire:model="duree_etuvage_minutes" min="0"
                               placeholder="ex: 120"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                {{-- Info taux --}}
                <div class="bg-blue-950/30 border border-blue-700/30 rounded-xl px-4 py-3 flex items-center gap-3">
                    <span class="text-blue-400 text-lg">ℹ️</span>
                    <p class="text-xs text-blue-300">
                        Taux de rendement de référence : <span class="font-bold">{{ $tauxRef }}%</span>
                        — La masse réelle sera saisie après séchage lors de la clôture.
                    </p>
                </div>

                <button wire:click="lancer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="w-full py-3 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white font-black rounded-xl transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="lancer">🔥 Lancer l'étuvage</span>
                    <span wire:loading wire:target="lancer">⏳ Lancement...</span>
                </button>
            </div>

            {{-- ÉTUVAGES EN COURS (2/5) --}}
            <div class="lg:col-span-2 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-700 pb-4">
                    <h2 class="text-sm font-bold text-white">⏳ En cours de séchage</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-900/50 text-amber-300 border border-amber-700/50">
                        {{ $etuvagesEnCours->count() }}
                    </span>
                </div>

                @forelse($etuvagesEnCours as $e)
                <div class="bg-slate-800/60 border border-slate-700/40 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-blue-400 text-xs font-bold">{{ $e->code_etuvage }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span class="text-xs text-amber-300 font-semibold">En cours</span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-400 space-y-1">
                        <p>🌾 {{ $e->code_lot }} · {{ $e->variete_nom ?? '—' }}</p>
                        <p>📦 {{ number_format($e->quantite_paddy_entree_kg, 0, ',', ' ') }} kg entrés</p>
                        <p>📅 {{ \Carbon\Carbon::parse($e->date_debut_etuvage)->format('d/m/Y H:i') }}</p>
                        @if($e->temperature_etuvage)
                        <p>🌡️ {{ $e->temperature_etuvage }}°C
                            @if($e->duree_etuvage_minutes) · {{ $e->duree_etuvage_minutes }} min @endif
                        </p>
                        @endif
                        @if($e->agent_nom)
                        <p>👤 {{ $e->agent_prenom }} {{ $e->agent_nom }}</p>
                        @endif
                    </div>
                    {{-- Durée écoulée --}}
                    @php
                        $debut = \Carbon\Carbon::parse($e->date_debut_etuvage);
                        $duree = $debut->diffForHumans(now(), true);
                    @endphp
                    <p class="text-xs text-slate-500">⏱️ Démarré il y a {{ $duree }}</p>

                    <button wire:click="ouvrirCloture({{ $e->id }})"
                            class="w-full py-2 bg-green-700 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition active:scale-95 flex items-center justify-center gap-1.5">
                        ✅ Clôturer — Saisir pesée
                    </button>
                </div>
                @empty
                <div class="text-center py-8 text-slate-600">
                    <p class="text-3xl mb-2">🔥</p>
                    <p class="text-sm">Aucun étuvage en cours</p>
                    <p class="text-xs mt-1">Lancez un étuvage pour qu'il apparaisse ici</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL CLÔTURE --}}
        @if($showCloture)
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-6 w-full max-w-md space-y-5 shadow-2xl">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-500/15 border border-green-500/30 flex items-center justify-center text-lg">⚖️</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Clôturer l'étuvage</h3>
                            <p class="text-xs text-green-400 font-mono">{{ $codeEtuvageCloture }}</p>
                        </div>
                    </div>
                    <button wire:click="fermerCloture" class="text-slate-500 hover:text-white transition text-lg">✕</button>
                </div>

                @if($errorMessage)
                <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-3 flex items-center gap-2 text-red-300 text-sm">
                    <span>⚠️</span><p>{{ $errorMessage }}</p>
                </div>
                @endif

                <div class="bg-slate-800/50 border border-green-700/20 rounded-xl p-4 space-y-2 text-xs text-slate-400">
                    <p class="text-white font-semibold text-sm mb-1">📋 Instructions</p>
                    <p>1. Vérifiez que le paddy est bien séché</p>
                    <p>2. Pesez le lot sur la balance</p>
                    <p>3. Saisissez la masse réelle obtenue</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">
                            Masse réelle après séchage (kg) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" wire:model.lazy="masse_apres_kg" min="0.1" step="0.1"
                               class="w-full bg-slate-800 border border-green-700/40 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition text-right text-lg font-bold">
                        @if($masse_apres_kg)
                        @php
                            $etuvage = \Illuminate\Support\Facades\DB::table('etuvages')->where('id', $etuvageACloturer)->first();
                            $entree  = $etuvage ? (float)$etuvage->quantite_paddy_entree_kg : 0;
                            $sortie  = (float)$masse_apres_kg;
                            $rend    = $entree > 0 ? round($sortie / $entree * 100, 1) : 0;
                            $perte   = round($entree - $sortie, 1);
                        @endphp
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            <div class="bg-slate-800 rounded-lg p-2 text-center">
                                <p class="text-xs text-slate-500">Entrée</p>
                                <p class="text-sm font-bold text-white">{{ number_format($entree, 0, ',', ' ') }} kg</p>
                            </div>
                            <div class="bg-slate-800 rounded-lg p-2 text-center">
                                <p class="text-xs text-slate-500">Rendement</p>
                                <p class="text-sm font-bold {{ $rend >= $tauxRef - 5 ? 'text-green-400' : ($rend >= $tauxRef - 10 ? 'text-amber-400' : 'text-red-400') }}">{{ $rend }}%</p>
                            </div>
                            <div class="bg-slate-800 rounded-lg p-2 text-center">
                                <p class="text-xs text-slate-500">Perte</p>
                                <p class="text-sm font-bold text-orange-400">{{ number_format($perte, 0, ',', ' ') }} kg</p>
                            </div>
                        </div>
                        @if($rend < $tauxRef - 10)
                        <p class="text-xs text-red-400 mt-1.5 flex items-center gap-1">
                            ⚠️ Rendement inférieur au seuil de référence ({{ $tauxRef }}%) — vérifiez le séchage
                        </p>
                        @endif
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date & heure fin</label>
                        <input type="datetime-local" wire:model="date_fin_etuvage"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button wire:click="fermerCloture"
                            class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">
                        Annuler
                    </button>
                    <button wire:click="cloturer"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-60 cursor-wait"
                            class="flex-1 py-2.5 bg-green-600 hover:bg-green-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                        <span wire:loading.remove wire:target="cloturer">✅ Valider la clôture</span>
                        <span wire:loading wire:target="cloturer">⏳ Clôture...</span>
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>