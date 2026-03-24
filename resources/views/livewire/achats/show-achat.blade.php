<div class="min-h-screen bg-slate-950 text-white">

    {{-- BARRE SUPÉRIEURE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('achats.index') }}"
                   class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm group">
                    <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span> Achats
                </a>
                <span class="text-slate-700">›</span>
                <span class="font-mono text-amber-400 text-sm font-bold">{{ $lot->code_lot }}</span>
            </div>
			<a href="{{ route('recus.imprimer', $recu->id) }}" target="_blank"
   class="px-3 py-1 bg-amber-700 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition">
    🖨 PDF
</a>
            <div class="flex items-center gap-3">
                @if($lot->statut === 'anticipe')
                <button wire:click="marquerLivre"
                        class="px-4 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition active:scale-95 flex items-center gap-2">
                    ✅ Marquer livré
                </button>
                @endif
                <a href="{{ route('achats.nouvelle') }}"
                   class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    + Nouvel achat
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- MESSAGES --}}
        @if($successMessage)
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 flex items-center gap-3 text-green-300">
            <span>✅</span><p class="text-sm font-medium">{{ $successMessage }}</p>
        </div>
        @endif
        @if($errorMessage)
        <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm">{{ $errorMessage }}</p>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- HERO CARD — LOT                                               --}}
        {{-- ============================================================ --}}
        <div class="relative bg-gradient-to-br from-slate-900 via-slate-900 to-amber-950/20 border border-amber-700/30 rounded-2xl p-6 overflow-hidden">
            {{-- Décoration bg --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>

            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Infos principales --}}
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-2xl flex-shrink-0">🌾</div>
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h1 class="text-2xl font-black text-white font-mono tracking-tight">{{ $lot->code_lot }}</h1>
                                @php
                                    $statutConfig = [
                                        'disponible'    => ['bg-green-900/50 text-green-300 border-green-700/50', '●', ''],
                                        'anticipe'      => ['bg-amber-900/50 text-amber-300 border-amber-700/50', '◌', 'animate-pulse'],
                                        'epuise'        => ['bg-slate-800 text-slate-500 border-slate-700', '○', ''],
                                        'en_traitement' => ['bg-blue-900/50 text-blue-300 border-blue-700/50', '◑', ''],
                                    ];
                                    $sc = $statutConfig[$lot->statut] ?? $statutConfig['disponible'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $sc[0] }}">
                                    <span class="{{ $sc[2] }}">{{ $sc[1] }}</span>
                                    {{ ucfirst(str_replace('_', ' ', $lot->statut)) }}
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm mt-1">
                                {{ $lot->variete_nom ?? '—' }} · {{ $lot->localite_nom ?? 'Localité inconnue' }}
                                · Acheté le {{ \Carbon\Carbon::parse($lot->date_achat)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Fournisseur --}}
                    <div class="flex items-center gap-3 bg-slate-800/50 rounded-xl px-4 py-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-700 flex items-center justify-center text-base flex-shrink-0">
                            {{ $lot->type_personne === 'MORALE' ? '🏢' : '👤' }}
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">
                                {{ $lot->fournisseur_nom }}
                                @if($lot->fournisseur_prenom) {{ $lot->fournisseur_prenom }} @endif
                            </p>
                            <p class="text-slate-500 text-xs">
                                {{ $lot->fournisseur_tel ?? 'Pas de téléphone' }}
                                @if($lot->agent_nom) · Agent : {{ $lot->agent_prenom }} {{ $lot->agent_nom }} @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Métriques --}}
                <div class="space-y-3">
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité achetée</p>
                        <p class="text-2xl font-black text-white">{{ number_format($lot->quantite_achat_kg, 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    </div>
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total</p>
                        <p class="text-xl font-black text-amber-400">{{ number_format($lot->montant_achat_total_fcfa, 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                    </div>
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Prix unitaire</p>
                        <p class="text-lg font-bold text-slate-300">{{ number_format($lot->prix_achat_unitaire_fcfa, 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">FCFA/kg</span></p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- GRILLE : STOCK + REÇU                                         --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- STOCK PADDY (3/5) --}}
            <div class="lg:col-span-3 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">📦 Stock paddy généré</h2>
                    @if($stock)
                    <span class="font-mono text-xs text-slate-500">{{ $stock->code_stock }}</span>
                    @endif
                </div>

                @if($stock)
                {{-- Barre de progression stock --}}
                @php
                    $restant   = (float)$stock->quantite_restante_kg;
                    $total     = (float)$lot->quantite_achat_kg;
                    $pctRestant = $total > 0 ? round($restant / $total * 100) : 0;
                    $barColor  = $pctRestant > 60 ? 'bg-green-500' : ($pctRestant > 25 ? 'bg-amber-500' : 'bg-red-500');
                @endphp

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-800 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Initial</p>
                        <p class="text-xl font-black text-white">{{ number_format($total, 0, ',', ' ') }}<span class="text-xs font-normal text-slate-500 ml-1">kg</span></p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Consommé</p>
                        <p class="text-xl font-black text-orange-400">{{ number_format($pct_consomme, 0) }}<span class="text-xs font-normal text-slate-500 ml-1">%</span></p>
                    </div>
                    <div class="bg-slate-800 border {{ $pctRestant > 25 ? 'border-green-700/30' : 'border-red-700/30' }} rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Restant</p>
                        <p class="text-xl font-black {{ $pctRestant > 60 ? 'text-green-400' : ($pctRestant > 25 ? 'text-amber-400' : 'text-red-400') }}">
                            {{ number_format($restant, 0, ',', ' ') }}<span class="text-xs font-normal text-slate-500 ml-1">kg</span>
                        </p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-2">
                        <span>Stock restant</span>
                        <span>{{ $pctRestant }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                        <div class="{{ $barColor }} h-3 rounded-full transition-all duration-700"
                             style="width: {{ $pctRestant }}%"></div>
                    </div>
                </div>

                @if($stock->emplacement)
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>📍</span>
                    <span>Emplacement : <span class="text-white font-medium">{{ $stock->emplacement }}</span></span>
                </div>
                @endif

                @else
                <div class="text-center py-8 text-slate-600">
                    @if($lot->statut === 'anticipe')
                    <p class="text-4xl mb-3">⏳</p>
                    <p class="text-sm">Le stock sera créé à la livraison du lot.</p>
                    @else
                    <p class="text-4xl mb-3">📦</p>
                    <p class="text-sm">Aucun stock trouvé pour ce lot.</p>
                    @endif
                </div>
                @endif
            </div>

            {{-- REÇU FOURNISSEUR (2/5) --}}
            <div class="lg:col-span-2 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">🧾 Reçu fournisseur</h2>

                @if($recu)
                {{-- Numéro + statut --}}
                <div class="flex items-center justify-between">
                    <span class="font-mono text-amber-400 text-sm font-bold">{{ $recu->numero_recu }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs border {{ $recu->paye ? 'bg-green-900/50 text-green-300 border-green-700/50' : 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50' }}">
                        {{ $recu->paye ? '✓ Soldé' : '⏳ En cours' }}
                    </span>
                </div>

                {{-- Barre paiement --}}
                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-2">
                        <span>Avancement paiement</span>
                        <span>{{ $pct_paye }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                        <div class="{{ $pct_paye >= 100 ? 'bg-green-500' : 'bg-amber-500' }} h-2 rounded-full transition-all duration-700"
                             style="width: {{ $pct_paye }}%"></div>
                    </div>
                </div>

                {{-- Montants --}}
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total</span>
                        <span class="text-white font-semibold">{{ number_format($recu->montant_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Payé</span>
                        <span class="text-green-400 font-semibold">{{ number_format($recu->acompte, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($recu->solde_du > 0)
                    <div class="flex justify-between border-t border-slate-700 pt-2">
                        <span class="text-slate-400">Solde dû</span>
                        <span class="text-yellow-400 font-black">{{ number_format($recu->solde_du, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Mode</span>
                        <span class="text-slate-400 capitalize">{{ str_replace('_', ' ', $recu->mode_paiement) }}</span>
                    </div>
                    @if($recu->date_limite_paiement)
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Échéance</span>
                        <span class="text-slate-400">{{ \Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Historique paiements --}}
                @if($paiements->isNotEmpty())
                <div class="border-t border-slate-700/60 pt-3 space-y-2">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Historique</p>
                    @foreach($paiements as $p)
                    <div class="flex items-center justify-between text-xs bg-slate-800 rounded-lg px-3 py-2">
                        <div>
                            <p class="text-white font-semibold">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</p>
                            <p class="text-slate-500 capitalize">{{ str_replace('_', ' ', $p->mode_paiement) }}</p>
                        </div>
                        <p class="text-slate-500">{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Bouton paiement --}}
                @if(!$recu->paye)
                    @if(!$showPaiementForm)
                    <button wire:click="$set('showPaiementForm', true)"
                            class="w-full py-2.5 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition active:scale-95">
                        + Enregistrer un paiement
                    </button>
                    @else
                    <div class="border-t border-slate-700/60 pt-4 space-y-3">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">💳 Nouveau paiement</p>
                        <input type="number" wire:model="montant_paiement"
                               placeholder="Montant FCFA"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <select wire:model="mode_paiement"
                                class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                            <option value="espece">💵 Espèces</option>
                            <option value="cheque">📝 Chèque</option>
                            <option value="mobile_money">📱 Mobile Money</option>
                            <option value="virement">🏦 Virement</option>
                        </select>
                        <input type="date" wire:model="date_paiement"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <input type="text" wire:model="note_paiement"
                               placeholder="Note (optionnel)"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <div class="flex gap-2">
                            <button wire:click="$set('showPaiementForm', false)"
                                    class="flex-1 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">
                                Annuler
                            </button>
                            <button wire:click="enregistrerPaiement"
                                    class="flex-1 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition">
                                Valider
                            </button>
                        </div>
                    </div>
                    @endif
                @endif

                @else
                <div class="text-center py-6 text-slate-600">
                    <p class="text-3xl mb-2">🧾</p>
                    <p class="text-sm">Aucun reçu généré pour ce lot.</p>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>