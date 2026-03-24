<div class="min-h-screen bg-slate-950 text-white">

    {{-- ================================================================ --}}
    {{-- BARRE SUPÉRIEURE                                                   --}}
    {{-- ================================================================ --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60 shadow-xl">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('commandes.index') }}"
                   class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm font-medium group">
                    <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span>
                    Retour
                </a>
                <div class="h-5 w-px bg-slate-700"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-500/20 border border-green-500/30 flex items-center justify-center text-sm">🚚</div>
                    <div>
                        <h1 class="text-sm font-bold text-white leading-none">Enregistrer une livraison</h1>
                        @if($commande)
                        <p class="text-xs text-slate-400 mt-0.5">
                            <span class="font-mono text-green-400">{{ $commande->code_commande }}</span>
                            <span class="mx-1 text-slate-600">·</span>
                            {{ $commande->client->raison_sociale ?? ($commande->client->nom . ' ' . $commande->client->prenom) }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @if($commande)
            @php
                $statutColor = match($commande->statut) {
                    'livree'               => 'text-green-400 bg-green-500/10 border-green-500/30',
                    'partiellement_livree' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/30',
                    'confirmee'            => 'text-blue-400 bg-blue-500/10 border-blue-500/30',
                    default                => 'text-slate-400 bg-slate-500/10 border-slate-500/30',
                };
                $statutLabel = match($commande->statut) {
                    'livree'               => '✓ Livrée',
                    'partiellement_livree' => '◑ Part. livrée',
                    'confirmee'            => '● Confirmée',
                    default                => $commande->statut,
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statutColor }}">
                {{ $statutLabel }}
            </span>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MESSAGES                                                           --}}
    {{-- ================================================================ --}}
    @if($successMessage)
    <div class="max-w-7xl mx-auto px-6 pt-6">
        <div class="bg-green-950/60 border border-green-500/40 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-xl flex-shrink-0">✅</div>
            <div class="flex-1">
                <p class="font-semibold text-green-300">{{ $successMessage }}</p>
                <div class="mt-3 flex gap-3">
                    <a href="{{ route('commandes.index') }}"
                       class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white text-sm rounded-lg transition">
                        ← Liste des commandes
                    </a>
                    @if($commande && $commande->statut !== 'livree')
                    <button wire:click="$set('successMessage', '')"
                            class="px-4 py-1.5 bg-blue-600/30 hover:bg-blue-600/50 border border-blue-500/40 text-blue-300 text-sm rounded-lg transition">
                        📦 Nouvelle livraison partielle
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($errorMessage)
    <div class="max-w-7xl mx-auto px-6 pt-6">
        <div class="bg-red-950/60 border border-red-500/40 rounded-2xl p-4 flex items-center gap-3 text-red-300">
            <span class="text-xl">⚠️</span>
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    </div>
    @endif

    @if(!$commande)
    <div class="max-w-7xl mx-auto px-6 pt-12 text-center text-red-400">Commande introuvable.</div>
    @else

    {{-- ================================================================ --}}
    {{-- CORPS PRINCIPAL — Layout 3 colonnes                               --}}
    {{-- ================================================================ --}}
    <div class="max-w-7xl mx-auto px-6 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ============================================================ --}}
        {{-- COLONNE GAUCHE                                                 --}}
        {{-- ============================================================ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- KPIs --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Type</p>
                    <p class="text-white font-bold text-sm">
                        {{ match($commande->type_vente) {
                            'comptant'     => '💵 Comptant',
                            'credit'       => '📋 Crédit',
                            'anticipation' => '⏳ Anticipation',
                            default        => $commande->type_vente
                        } }}
                    </p>
                </div>
                <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Point de vente</p>
                    <p class="text-white font-bold text-xs truncate">{{ $commande->pointVente->nom ?? '—' }}</p>
                </div>
                <div class="bg-slate-900 border border-green-900/50 rounded-xl p-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total commande</p>
                    <p class="text-green-400 font-bold">{{ number_format($commande->montant_total_fcfa, 0, ',', ' ') }}</p>
                    <p class="text-slate-600 text-xs">FCFA</p>
                </div>
                <div class="bg-slate-900 border border-{{ $commande->montant_solde_fcfa > 0 ? 'yellow' : 'green' }}-900/50 rounded-xl p-4">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Solde</p>
                    <p class="font-bold {{ $commande->montant_solde_fcfa > 0 ? 'text-yellow-400' : 'text-green-400' }}">
                        {{ number_format($commande->montant_solde_fcfa, 0, ',', ' ') }}
                    </p>
                    <p class="text-slate-600 text-xs">FCFA</p>
                </div>
            </div>

            {{-- Client --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-3">Client</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-sm font-black flex-shrink-0">
                        {{ strtoupper(substr($commande->client->nom ?? $commande->client->raison_sociale ?? 'C', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-semibold text-sm truncate">
                            {{ $commande->client->raison_sociale ?? ($commande->client->nom . ' ' . $commande->client->prenom) }}
                        </p>
                        <p class="text-slate-500 text-xs">{{ $commande->client->code_client }}</p>
                    </div>
                </div>
                @if($commande->client->telephone)
                <p class="text-slate-500 text-xs mt-3">📞 {{ $commande->client->telephone }}</p>
                @endif
            </div>

            {{-- Livraisons précédentes --}}
            @if($commande->livraisons->isNotEmpty())
            <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-4">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-3">
                    Livraisons précédentes
                    <span class="ml-2 px-1.5 py-0.5 bg-slate-800 rounded text-slate-400">{{ $commande->livraisons->count() }}</span>
                </p>
                <div class="space-y-2">
                    @foreach($commande->livraisons as $liv)
                    <div class="flex items-center justify-between bg-slate-800/50 rounded-lg px-3 py-2.5 border border-slate-700/40">
                        <div>
                            <p class="font-mono text-green-400 text-xs font-semibold">{{ $liv->code_livraison }}</p>
                            <p class="text-slate-600 text-xs mt-0.5">{{ $liv->date_livraison?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white text-xs font-bold">{{ $liv->lignes->sum('quantite_livree') }} sac(s)</p>
                            <span class="text-xs {{ $liv->statut === 'effectuee' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $liv->statut }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ============================================================ --}}
        {{-- COLONNE DROITE — Lignes à livrer                              --}}
        {{-- ============================================================ --}}
        <div class="lg:col-span-2 space-y-4">

            @if(!$estEnregistree && !empty($lignesLivraison))

            {{-- En-tête tableau --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800 bg-slate-800/30">
                    <div>
                        <h2 class="text-base font-bold text-white">Lignes à livrer</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ count($lignesLivraison) }} ligne(s) · saisir les quantités</p>
                    </div>
                    <button wire:click="toutLivrer"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white text-xs font-bold rounded-lg transition-all">
                        ✅ Tout livrer
                    </button>
                </div>

                {{-- Lignes --}}
                <div class="divide-y divide-slate-800/80">
                    @foreach($lignesLivraison as $ligneId => $data)
                    @php $qte = $lignesLivraison[$ligneId]['quantite_a_livrer'] ?? 0; @endphp
                    <div class="px-6 py-5 flex items-center gap-4 {{ $qte > 0 ? 'bg-green-950/20' : 'hover:bg-slate-800/20' }} transition">

                        {{-- Indicateur couleur --}}
                        <div class="w-1 self-stretch rounded-full {{ $qte > 0 ? 'bg-green-500' : 'bg-slate-700' }} flex-shrink-0"></div>

                        {{-- Produit --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-mono font-bold text-white text-sm">{{ $data['code_sac'] }}</p>
                            <p class="text-slate-500 text-xs mt-0.5">
                                {{ match($data['type_produit']) {
                                    'riz_blanc' => 'Riz blanc',
                                    'son'       => 'Son de riz',
                                    'brisures'  => 'Brisures',
                                    default     => $data['type_produit']
                                } }}
                                @if($data['poids_sac_kg']) · {{ $data['poids_sac_kg'] }}kg @endif
                            </p>
                        </div>

                        {{-- Statistiques --}}
                        <div class="hidden sm:flex items-center gap-5">
                            <div class="text-center">
                                <p class="text-slate-600 text-xs uppercase tracking-wider">Cmd</p>
                                <p class="text-slate-300 font-bold text-sm mt-0.5">{{ $data['quantite_cmd'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-600 text-xs uppercase tracking-wider">Livré</p>
                                <p class="{{ $data['quantite_livree'] > 0 ? 'text-green-400' : 'text-slate-600' }} font-bold text-sm mt-0.5">{{ $data['quantite_livree'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-600 text-xs uppercase tracking-wider">Restant</p>
                                <p class="text-yellow-400 font-bold text-sm mt-0.5">{{ $data['quantite_restante'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-slate-600 text-xs uppercase tracking-wider">Stock</p>
                                @if($data['stock_sac_id'])
                                <p class="{{ $data['stock_disponible'] >= $data['quantite_restante'] ? 'text-green-400' : 'text-red-400' }} font-bold text-sm mt-0.5">
                                    {{ $data['stock_disponible'] }}
                                </p>
                                @else
                                <p class="text-slate-600 text-sm mt-0.5">—</p>
                                @endif
                            </div>
                        </div>

                        {{-- Input --}}
                        <div class="flex-shrink-0 flex flex-col items-center gap-1">
                            <p class="text-slate-600 text-xs uppercase tracking-wider">À livrer</p>
                            <input type="number"
                                   wire:model.live="lignesLivraison.{{ $ligneId }}.quantite_a_livrer"
                                   min="0"
                                   max="{{ min($data['quantite_restante'], $data['stock_disponible'] > 0 ? $data['stock_disponible'] : $data['quantite_restante']) }}"
                                   class="w-20 text-center bg-slate-800 border-2 {{ $qte > 0 ? 'border-green-500 text-green-300' : 'border-slate-600 text-white' }} rounded-xl px-2 py-2.5 text-base font-black focus:outline-none focus:border-green-400 transition">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-4">
                <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Notes de livraison</label>
                <textarea wire:model="notes" rows="2"
                          placeholder="Instructions, observations..."
                          class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-green-500 resize-none text-sm placeholder-slate-600"></textarea>
            </div>

            {{-- Barre de confirmation --}}
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-5 flex items-center justify-between gap-4">
                <div>
                    <p class="text-slate-500 text-xs uppercase tracking-wider mb-1">Total à livrer</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-white tabular-nums">{{ collect($lignesLivraison)->sum('quantite_a_livrer') }}</span>
                        <span class="text-slate-400 font-medium">sac(s)</span>
                    </div>
                    <p class="text-slate-600 text-xs mt-1">Le stock sera débité automatiquement.</p>
                </div>
                <button wire:click="enregistrer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="px-8 py-4 bg-green-600 hover:bg-green-500 active:scale-95 text-white font-black rounded-xl transition-all shadow-lg shadow-green-900/40 text-sm flex items-center gap-2">
                    <span wire:loading.remove wire:target="enregistrer">🚚 Confirmer la livraison</span>
                    <span wire:loading wire:target="enregistrer">⏳ Enregistrement...</span>
                </button>
            </div>

            @elseif(empty($lignesLivraison) && !$estEnregistree)
            <div class="bg-slate-900 border border-dashed border-slate-700 rounded-2xl p-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-3xl mx-auto mb-4">✅</div>
                <p class="text-white font-bold text-xl">Toutes les lignes sont livrées</p>
                <p class="text-slate-500 text-sm mt-2">Cette commande est entièrement expédiée.</p>
                <a href="{{ route('commandes.index') }}"
                   class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white rounded-xl text-sm transition">
                    ← Retour à la liste
                </a>
            </div>
            @endif

            @if($estEnregistree)
            <div class="bg-slate-900 border border-dashed border-slate-700 rounded-2xl p-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-3xl mx-auto mb-4">🔒</div>
                <p class="text-white font-bold text-xl">Commande non livrable</p>
                <p class="text-slate-500 text-sm mt-2">Statut : <span class="text-orange-400 font-semibold">{{ $commande->statut }}</span></p>
                <a href="{{ route('commandes.index') }}"
                   class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white rounded-xl text-sm transition">
                    ← Retour à la liste
                </a>
            </div>
            @endif

        </div>
    </div>

    @endif
</div>