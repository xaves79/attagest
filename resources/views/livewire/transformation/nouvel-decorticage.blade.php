<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('decorticages.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm group">
                    <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span> Décorticages
                </a>
                <div class="h-5 w-px bg-slate-700"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-sm">⚙️</div>
                    <div>
                        <h1 class="text-sm font-bold text-white leading-none">Gestion des décorticages</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Lancement · Suivi · Clôture</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">

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

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- FORMULAIRE LANCEMENT --}}
            <div class="lg:col-span-3 bg-slate-900 border border-purple-700/30 rounded-2xl p-6 space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-700 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-lg">⚙️</div>
                    <div>
                        <h2 class="text-sm font-bold text-white">Lancer un décorticage</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Le riz étuvé sera débité du stock immédiatement</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Lot riz étuvé <span class="text-red-400">*</span></label>
                    <select wire:model.live="lot_riz_etuve_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition">
                        <option value="">— Sélectionner un lot disponible —</option>
                        @foreach($lots as $lot)
                        <option value="{{ $lot->id }}">
                            {{ $lot->code_lot }} — {{ $lot->variete_nom ?? '?' }}
                            · {{ number_format($lot->quantite_restante_kg, 0, ',', ' ') }} kg dispo
                            @if($lot->code_etuvage) ({{ $lot->code_etuvage }}) @endif
                        </option>
                        @endforeach
                    </select>
                    @if($stockDisponible > 0)
                    <p class="text-purple-400 text-xs mt-1.5 flex items-center gap-1">
                        📦 Stock disponible : <span class="font-bold ml-1">{{ number_format($stockDisponible, 0, ',', ' ') }} kg</span>
                    </p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Agent responsable</label>
                        <select wire:model="agent_id"
                                class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition">
                            <option value="">— Sélectionner —</option>
                            @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Quantité à décortiquer (kg) <span class="text-red-400">*</span></label>
                        <input type="number" wire:model="quantite_paddy_entree_kg" min="1" step="0.1"
                               placeholder="ex: 5000"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date & heure début <span class="text-red-400">*</span></label>
                        <input type="datetime-local" wire:model="date_debut_decorticage"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition">
                    </div>
                </div>

                <div class="bg-purple-950/30 border border-purple-700/30 rounded-xl px-4 py-3 flex items-center gap-3">
                    <span class="text-purple-400 text-lg">ℹ️</span>
                    <p class="text-xs text-purple-300">
                        Taux de rendement riz blanc de référence : <span class="font-bold">{{ $tauxRef }}%</span>
                        — Le son sera calculé automatiquement à la clôture.
                    </p>
                </div>

                <button wire:click="lancer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="w-full py-3 bg-purple-600 hover:bg-purple-500 active:scale-95 text-white font-black rounded-xl transition-all shadow-lg shadow-purple-900/30 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="lancer">⚙️ Lancer le décorticage</span>
                    <span wire:loading wire:target="lancer">⏳ Lancement...</span>
                </button>
            </div>

            {{-- DÉCORTICAGES EN COURS --}}
            <div class="lg:col-span-2 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-700 pb-4">
                    <h2 class="text-sm font-bold text-white">⏳ En cours de décorticage</h2>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-purple-900/50 text-purple-300 border border-purple-700/50">
                        {{ $decorticagesEnCours->count() }}
                    </span>
                </div>

                @forelse($decorticagesEnCours as $d)
                <div class="bg-slate-800/60 border border-slate-700/40 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-purple-400 text-xs font-bold">{{ $d->code_decorticage }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                            <span class="text-xs text-purple-300 font-semibold">En cours</span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-400 space-y-1">
                        <p>🌾 {{ $d->code_lot_etuve }} · {{ $d->variete_nom ?? '—' }}</p>
                        <p>📦 {{ number_format($d->quantite_paddy_entree_kg, 0, ',', ' ') }} kg engagés</p>
                        <p>📅 {{ \Carbon\Carbon::parse($d->date_debut_decorticage)->format('d/m/Y H:i') }}</p>
                        @if($d->agent_nom)
                        <p>👤 {{ $d->agent_prenom }} {{ $d->agent_nom }}</p>
                        @endif
                    </div>
                    @php $duree = \Carbon\Carbon::parse($d->date_debut_decorticage)->diffForHumans(now(), true); @endphp
                    <p class="text-xs text-slate-500">⏱️ Démarré il y a {{ $duree }}</p>
                    <button wire:click="ouvrirCloture({{ $d->id }})"
                            class="w-full py-2 bg-green-700 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition active:scale-95">
                        ✅ Clôturer — Saisir les quantités
                    </button>
                </div>
                @empty
                <div class="text-center py-8 text-slate-600">
                    <p class="text-3xl mb-2">⚙️</p>
                    <p class="text-sm">Aucun décorticage en cours</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL CLÔTURE --}}
        @if($showCloture)
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-6 w-full max-w-lg space-y-5 shadow-2xl">

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-500/15 border border-green-500/30 flex items-center justify-center text-lg">⚖️</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Clôturer le décorticage</h3>
                            <p class="text-xs text-green-400 font-mono">{{ $codeDecorticageCloture }}</p>
                        </div>
                    </div>
                    <button wire:click="fermerCloture" class="text-slate-500 hover:text-white transition text-lg">✕</button>
                </div>

                @if($errorMessage)
                <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-3 text-red-300 text-sm flex items-center gap-2">
                    <span>⚠️</span><p>{{ $errorMessage }}</p>
                </div>
                @endif

                @php
                    $dec = \Illuminate\Support\Facades\DB::table('decorticages')->where('id', $decorticageACloturer)->first();
                    $entreeDecort = $dec ? (float)$dec->quantite_paddy_entree_kg : 0;
                    $blanc  = (float)$quantite_riz_blanc_kg;
                    $brise  = (float)$quantite_brise_kg;
                    $rejet  = (float)$quantite_rejet_kg;
                    $son    = max(0, $entreeDecort - $blanc - $brise - $rejet);
                    $rend   = $entreeDecort > 0 ? round($blanc / $entreeDecort * 100, 1) : 0;
                @endphp

                {{-- Récap entrée --}}
                <div class="bg-slate-800/50 border border-slate-700/40 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité engagée</p>
                    <p class="text-xl font-black text-white">{{ number_format($entreeDecort, 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">kg</span></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Riz blanc --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">
                            🍚 Riz blanc obtenu (kg) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" wire:model.lazy="quantite_riz_blanc_kg" min="0" step="0.1"
                               class="w-full bg-slate-800 border border-green-700/40 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition text-right font-bold text-lg">
                    </div>

                    {{-- Brisures --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">💛 Brisures (kg)</label>
                        <input type="number" wire:model.lazy="quantite_brise_kg" min="0" step="0.1"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition text-right">
                    </div>

                    {{-- Rejets --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">🗑️ Rejets (kg)</label>
                        <input type="number" wire:model.lazy="quantite_rejet_kg" min="0" step="0.1"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-purple-500 transition text-right">
                    </div>
                </div>

                {{-- Récap calculé --}}
                <div class="grid grid-cols-4 gap-2">
                    <div class="bg-slate-800 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-500 mb-1">Riz blanc</p>
                        <p class="text-sm font-black text-green-400">{{ number_format($blanc, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-amber-950/40 border border-amber-700/30 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-500 mb-1">Son 🔄</p>
                        <p class="text-sm font-black text-amber-400">{{ number_format($son, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-600 mt-0.5">calculé auto</p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-500 mb-1">Brisures</p>
                        <p class="text-sm font-black text-yellow-400">{{ number_format($brise, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-500 mb-1">Rendement</p>
                        <p class="text-sm font-black {{ $rend >= $tauxRef - 5 ? 'text-green-400' : ($rend >= $tauxRef - 10 ? 'text-amber-400' : 'text-red-400') }}">{{ $rend }}%</p>
                    </div>
                </div>

                @if($rend < $tauxRef - 10 && $rend > 0)
                <p class="text-xs text-red-400 flex items-center gap-1">
                    ⚠️ Rendement inférieur au seuil de référence ({{ $tauxRef }}%)
                </p>
                @endif

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date & heure fin</label>
                    <input type="datetime-local" wire:model="date_fin_decorticage"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
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