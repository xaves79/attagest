<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-sm">💳</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Paiements traitements</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Encaissement des prestations de décorticage</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

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

        {{-- FILTRES --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="🔍 Code, client..."
                   class="flex-1 bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
            <select wire:model.live="filtreStatut"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                <option value="">Tous statuts</option>
                <option value="en_attente">En attente</option>
                <option value="en_cours">En cours</option>
                <option value="termine">Terminé</option>
            </select>
        </div>

        {{-- TABLEAU --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 bg-slate-800/50 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-6 py-4" style="width:22%">Traitement</th>
                        <th class="text-left px-6 py-4" style="width:20%">Client</th>
                        <th class="text-right px-6 py-4" style="width:12%">Paddy</th>
                        <th class="text-right px-6 py-4" style="width:13%">Montant</th>
                        <th class="text-right px-6 py-4" style="width:10%">Payé</th>
                        <th class="text-right px-6 py-4" style="width:10%">Solde</th>
                        <th class="text-center px-6 py-4" style="width:13%">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($traitements as $t)
                    @php
                        $montant  = (float)$t->montant;
                        $dejaPaye = \Illuminate\Support\Facades\DB::table('paiements_traitements')
                            ->where('traitement_id', $t->id)->where('statut', 'paye')->sum('montant_paye');
                        $solde    = max(0, $montant - $dejaPaye);
                        $pct      = $montant > 0 ? round($dejaPaye / $montant * 100) : 0;
                        $badges   = ['en_attente' => 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50', 'en_cours' => 'bg-blue-900/50 text-blue-300 border-blue-700/50', 'termine' => 'bg-green-900/50 text-green-300 border-green-700/50', 'annule' => 'bg-slate-800 text-slate-500 border-slate-700'];
                    @endphp
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4">
                            <p class="font-mono text-teal-400 text-xs font-bold mb-1">{{ $t->code_traitement }}</p>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-xs border {{ $badges[$t->statut] ?? '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->statut)) }}
                                </span>
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($t->date_reception)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-white font-semibold text-sm">{{ $t->raison_sociale ?: $t->client_nom }}</p>
                        </td>
                        <td class="px-6 py-4 text-right text-slate-400 text-sm">
                            {{ number_format((float)$t->qte_paddy, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-white font-bold text-sm">{{ number_format($montant, 0, ',', ' ') }}</p>
                            <p class="text-xs text-slate-500">FCFA</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-green-400 font-bold text-sm">{{ number_format($dejaPaye, 0, ',', ' ') }}</p>
                            <p class="text-xs text-slate-500">{{ $pct }}%</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-black text-sm {{ $solde > 0 ? 'text-red-400' : 'text-green-400' }}">
                                {{ number_format($solde, 0, ',', ' ') }}
                            </p>
                            <p class="text-xs text-slate-500">FCFA</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @if($solde > 0)
                                <button wire:click="ouvrirPaiement({{ $t->id }})"
                                        class="px-3 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold rounded-lg transition">
                                    💳 Payer
                                </button>
                                @else
                                <span class="px-2 py-1.5 bg-green-900/30 text-green-400 text-xs font-bold rounded-lg">✅</span>
                                @endif
                                @if($t->statut === 'termine' && !$t->facture_client_id)
                                <button wire:click="facturer({{ $t->id }})"
                                        wire:confirm="Générer une facture pour ce traitement ?"
                                        title="Facturer"
                                        class="px-3 py-1.5 bg-green-700/60 hover:bg-green-600 text-white text-xs rounded-lg transition">💰</button>
                                @elseif($t->facture_client_id)
                                <span class="p-1.5 bg-green-900/30 text-green-400 text-xs rounded-lg" title="Facturé">📄</span>
                                @endif
                                <button wire:click="ouvrirHistorique({{ $t->id }})"
                                        title="Historique paiements"
                                        class="p-1.5 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">📜</button>
                                <a href="{{ route('traitements.imprimer', $t->id) }}" target="_blank"
                                   title="Imprimer PDF"
                                   class="p-1.5 bg-orange-700/60 hover:bg-orange-600 text-white rounded-lg transition">🖨️</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">💳</p>
                            <p class="text-sm">Aucun traitement trouvé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($traitements->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">{{ $traitements->links() }}</div>
            @endif
        </div>
    </div>

    {{-- MODAL PAIEMENT --}}
    @if($showPaiement)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-teal-700/40 rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white">💳 Enregistrer un paiement</h3>
                    <p class="text-xs text-teal-400 font-mono mt-0.5">{{ $paiementCode }}</p>
                </div>
                <button wire:click="$set('showPaiement', false)" class="text-slate-500 hover:text-white transition">✕</button>
            </div>

            @if($errorMessage)
            <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-3 text-red-300 text-sm">⚠️ {{ $errorMessage }}</div>
            @endif

            <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-3 text-center">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Solde restant dû</p>
                <p class="text-2xl font-black text-red-400">{{ number_format($soldeDu, 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">FCFA</span></p>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Montant (FCFA) <span class="text-red-400">*</span></label>
                    <input type="text" inputmode="decimal" wire:model="paiementMontant"
                           class="w-full bg-slate-800 border border-teal-700/40 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-teal-500 transition text-right text-lg font-bold">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Mode</label>
                    <select wire:model="paiementMode"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="especes">💵 Espèces</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="cheque">📝 Chèque</option>
                        <option value="virement">🏦 Virement</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Date</label>
                    <input type="date" wire:model="paiementDate"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Description</label>
                    <input type="text" wire:model="paiementDescription" placeholder="Note optionnelle"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>
            </div>

            <div class="flex gap-3">
                <button wire:click="$set('showPaiement', false)"
                        class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">Annuler</button>
                <button wire:click="enregistrerPaiement"
                        wire:loading.attr="disabled"
                        class="flex-1 py-2.5 bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    <span wire:loading.remove wire:target="enregistrerPaiement">✅ Valider</span>
                    <span wire:loading wire:target="enregistrerPaiement">⏳</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL HISTORIQUE --}}
    @if($showHistorique)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">📜 Historique des paiements</h3>
                <button wire:click="$set('showHistorique', false)" class="text-slate-500 hover:text-white transition">✕</button>
            </div>

            @if($historique->isEmpty())
            <div class="text-center py-8 text-slate-600">
                <p class="text-3xl mb-2">📜</p>
                <p class="text-sm">Aucun paiement enregistré.</p>
            </div>
            @else
            <div class="space-y-2">
                @foreach($historique as $p)
                <div class="flex items-center justify-between bg-slate-800 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-sm font-bold text-white">{{ number_format($p->montant_paye, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $p->mode_paiement)) }}
                            @if($p->description) · {{ $p->description }} @endif
                        </p>
                        <p class="text-xs text-slate-600 font-mono">{{ $p->numero_paiement }}</p>
                    </div>
                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</p>
                </div>
                @endforeach
                <div class="flex justify-between px-4 py-2 border-t border-slate-700 text-sm font-bold">
                    <span class="text-slate-400">Total payé</span>
                    <span class="text-green-400">{{ number_format($historique->sum('montant_paye'), 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            @endif

            <button wire:click="$set('showHistorique', false)"
                    class="w-full py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">
                Fermer
            </button>
        </div>
    </div>
    @endif

</div>