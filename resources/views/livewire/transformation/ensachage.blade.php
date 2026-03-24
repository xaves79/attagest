<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-500/20 border border-green-500/30 flex items-center justify-center text-sm">🎒</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Ensachage</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Conditionnement des produits finis en sacs</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8 space-y-6">

        @if($successMessage)
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 flex items-center gap-3 text-green-300">
            <span class="text-lg">✅</span><p class="text-sm font-medium">{{ $successMessage }}</p>
        </div>
        @endif
        @if($errorMessage)
        <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm">{{ $errorMessage }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- FORMULAIRE (3/5) --}}
            <div class="lg:col-span-3 bg-slate-900 border border-green-700/30 rounded-2xl p-6 space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-700 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-green-500/15 border border-green-500/30 flex items-center justify-center text-lg">🎒</div>
                    <div>
                        <h2 class="text-sm font-bold text-white">Créer des sacs</h2>
                        <p class="text-xs text-slate-500">Le stock produit fini sera débité automatiquement</p>
                    </div>
                </div>

                {{-- Stock produit fini --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Stock produit fini <span class="text-red-400">*</span></label>
                    <select wire:model.live="stock_produit_fini_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
                        <option value="">— Sélectionner un stock —</option>
                        @foreach($stocks->groupBy('type_produit') as $type => $items)
                        <optgroup label="{{ strtoupper(str_replace('_', ' ', $type)) }}">
                            @foreach($items as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->code_stock }} — {{ $s->variete_nom ?? '?' }}
                                · {{ number_format($s->quantite_kg, 0, ',', ' ') }} kg dispo
                                @if($s->code_decorticage) ({{ $s->code_decorticage }}) @endif
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    @if($stockDisponible > 0)
                    <p class="text-green-400 text-xs mt-1.5">📦 Disponible : <span class="font-bold">{{ number_format($stockDisponible, 0, ',', ' ') }} kg</span></p>
                    @endif
                </div>

                {{-- Type de sac --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Type de sac <span class="text-red-400">*</span></label>
                    <select wire:model.live="article_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition
                            {{ !$stock_produit_fini_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !$stock_produit_fini_id ? 'disabled' : '' }}>
                        <option value="">— Sélectionner —</option>
                        @foreach($articles as $a)
                        <option value="{{ $a->id }}">{{ $a->nom }} ({{ $a->taille_sac }} kg/sac) — {{ number_format($a->prix_unitaire, 0, ',', ' ') }} FCFA</option>
                        @endforeach
                    </select>
                    @if($poidsArticle > 0 && $nbMax > 0)
                    <p class="text-blue-400 text-xs mt-1.5">Maximum : <span class="font-bold">{{ $nbMax }} sac(s)</span> possible(s) avec ce stock</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Masse à ensacher --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Masse à ensacher (kg) <span class="text-red-400">*</span></label>
                        <input type="number" wire:model.lazy="masse_a_ensacher" min="1" step="0.1"
                               max="{{ $stockDisponible }}"
                               placeholder="ex: 500"
                               class="w-full bg-slate-800 border border-green-700/40 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition font-semibold">
                        @if($stockDisponible > 0)
                        <p class="text-xs text-slate-500 mt-1">Max : {{ number_format($stockDisponible, 0, ',', ' ') }} kg</p>
                        @endif
                    </div>

                    {{-- Nombre de sacs calculé --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Nombre de sacs <span class="text-blue-400">(calculé auto)</span></label>
                        <div class="w-full bg-slate-800/50 border border-blue-700/30 rounded-xl px-4 py-3 text-center">
                            <p class="text-blue-400 font-black text-2xl">{{ $nombre_sacs ?: '—' }}</p>
                            @if($nombre_sacs && $poidsArticle > 0)
                            <p class="text-xs text-slate-500 mt-0.5">× {{ $poidsArticle }} kg = {{ number_format((int)$nombre_sacs * $poidsArticle, 0, ',', ' ') }} kg</p>
                            @endif
                        </div>
                    </div>

                    {{-- Agent --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Agent</label>
                        <select wire:model="agent_id"
                                class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
                            <option value="">— Sélectionner —</option>
                            @foreach($agents as $a)
                            <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date d'emballage</label>
                        <input type="date" wire:model="date_emballage"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
                    </div>
                </div>

                <button wire:click="enregistrer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="w-full py-3 bg-green-600 hover:bg-green-500 active:scale-95 text-white font-black rounded-xl transition-all shadow-lg shadow-green-900/30 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="enregistrer">🎒 Créer les sacs</span>
                    <span wire:loading wire:target="enregistrer">⏳ Création...</span>
                </button>
            </div>

            {{-- HISTORIQUE (2/5) --}}
            <div class="lg:col-span-2 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                <h2 class="text-sm font-bold text-white border-b border-slate-700 pb-3">📋 Derniers ensachages</h2>

                @forelse($historique as $h)
                <div class="bg-slate-800/50 rounded-xl p-3 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-green-400 text-xs font-bold">{{ $h->code_sac }}</span>
                        <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($h->date_emballage)->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm font-semibold text-white">{{ $h->article_nom ?? ucfirst(str_replace('_', ' ', $h->type_sac)) }}</p>
                    <div class="flex items-center gap-3 text-xs text-slate-400">
                        <span>📦 {{ $h->nombre_sacs }} sac(s)</span>
                        <span>⚖️ {{ number_format($h->poids_total_kg, 0, ',', ' ') }} kg</span>
                        @if($h->variete_code)<span>🌾 {{ $h->variete_code }}</span>@endif
                    </div>
                    @if($h->agent_nom)
                    <p class="text-xs text-slate-500">👤 {{ $h->agent_nom }}</p>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-slate-600">
                    <p class="text-3xl mb-2">🎒</p>
                    <p class="text-sm">Aucun ensachage encore</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>