<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-500/20 border border-teal-500/30 flex items-center justify-center text-sm">🧪</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Traitements clients</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Décorticage à façon</p>
                </div>
            </div>
            <button wire:click="create"
                    class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouveau traitement
            </button>
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
                <option value="annule">Annulé</option>
            </select>
        </div>

        {{-- TABLEAU --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Code</th>
                        <th class="text-left px-5 py-3">Client</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-right px-5 py-3">Paddy (kg)</th>
                        <th class="text-right px-5 py-3">Montant (FCFA)</th>
                        <th class="text-center px-5 py-3">Statut</th>
                        <th class="text-center px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($traitements as $t)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3 font-mono text-teal-400 text-xs font-bold">{{ $t->code_traitement }}</td>
                        <td class="px-5 py-3 text-slate-300">{{ $t->raison_sociale ?: $t->client_nom }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $t->variete_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-white">
                            {{ number_format((float)$t->qte_paddy, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-amber-400">
                            {{ $t->montant ? number_format((float)$t->montant, 0, ',', ' ') : '—' }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php $badges = ['en_attente' => 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50', 'en_cours' => 'bg-blue-900/50 text-blue-300 border-blue-700/50', 'termine' => 'bg-green-900/50 text-green-300 border-green-700/50', 'annule' => 'bg-slate-800 text-slate-500 border-slate-700']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs border {{ $badges[$t->statut] ?? 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $t->statut)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="show({{ $t->id }})" class="px-2.5 py-1.5 bg-blue-700/60 hover:bg-blue-600 text-white text-xs rounded-lg transition">👁️</button>
                                <button wire:click="edit({{ $t->id }})" class="px-2.5 py-1.5 bg-yellow-700/60 hover:bg-yellow-600 text-white text-xs rounded-lg transition">✏️</button>
                                @if($t->statut === 'termine' && !$t->facture_client_id)
                                <button wire:click="facturer({{ $t->id }})" wire:confirm="Générer une facture ?" class="px-2.5 py-1.5 bg-green-700/60 hover:bg-green-600 text-white text-xs rounded-lg transition">💰</button>
                                @elseif($t->facture_client_id)
                                <span class="px-2.5 py-1.5 bg-green-900/30 text-green-500 text-xs rounded-lg">✅</span>
                                @endif
                                <button wire:click="ouvrirPaiement({{ $t->id }})" class="px-2.5 py-1.5 bg-teal-700/60 hover:bg-teal-600 text-white text-xs rounded-lg transition">💳</button>
                                <button wire:click="delete({{ $t->id }})" wire:confirm="Supprimer ?" class="px-2.5 py-1.5 bg-red-700/60 hover:bg-red-600 text-white text-xs rounded-lg transition">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-16 text-center text-slate-600">
                        <p class="text-4xl mb-3">🧪</p><p class="text-sm">Aucun traitement trouvé.</p>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($traitements->hasPages())
            <div class="px-5 py-4 border-t border-slate-800">{{ $traitements->links() }}</div>
            @endif
        </div>
    </div>

    {{-- MODAL FORMULAIRE --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 w-full max-w-3xl shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-bold text-white">{{ $formId ? 'Modifier' : 'Nouveau' }} traitement</h2>
                <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-white transition text-lg">✕</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Code</label>
                    <input type="text" wire:model="formCode" readonly
                           class="w-full bg-slate-800/50 border border-slate-700 text-slate-400 rounded-xl px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Client <span class="text-red-400">*</span></label>
                    <select wire:model="formClientId"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->raison_sociale ?: $c->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Agent</label>
                    <select wire:model="formAgentId"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($agents as $a)
                        <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Variété</label>
                    <select wire:model="formVarieteId"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($varietes as $v)
                        <option value="{{ $v->id }}">{{ $v->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Localité</label>
                    <select wire:model="formLocaliteId"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($localites as $l)
                        <option value="{{ $l->id }}">{{ $l->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Date réception <span class="text-red-400">*</span></label>
                    <input type="date" wire:model="formDateReception"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>

                {{-- Champs déclencheurs — type TEXT pour éviter formatage navigateur --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Quantité paddy (kg) <span class="text-red-400">*</span></label>
                    <input type="text" inputmode="decimal" wire:model.lazy="formQuantitePaddy"
                           placeholder="ex: 5000"
                           class="w-full bg-slate-800 border border-teal-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Riz blanc obtenu (kg)</label>
                    <input type="text" inputmode="decimal" wire:model.lazy="formQuantiteRizBlanc"
                           placeholder="ex: 4800"
                           class="w-full bg-slate-800 border border-teal-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Prix traitement / kg (FCFA)</label>
                    <input type="text" inputmode="decimal" wire:model.lazy="formPrixParKg"
                           placeholder="ex: 150"
                           class="w-full bg-slate-800 border border-teal-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                </div>

                {{-- Champs calculés automatiquement --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">
                        Son (kg) <span class="text-xs text-blue-400">🔄 auto</span>
                    </label>
                    <input type="text" wire:model="formQuantiteSon" readonly
                           class="w-full bg-slate-800/40 border border-slate-700 text-blue-300 rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">
                        Rendement (%) <span class="text-xs text-blue-400">🔄 auto</span>
                    </label>
                    <input type="text" wire:model="formTauxRendement" readonly
                           class="w-full bg-slate-800/40 border border-slate-700 text-blue-300 rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">
                        Montant (FCFA) <span class="text-xs text-green-400">🔄 auto</span>
                    </label>
                    <input type="text" wire:model="formMontantTraitement" readonly
                           class="w-full bg-slate-800/40 border border-green-700/30 text-green-300 rounded-xl px-4 py-2.5 text-sm font-bold cursor-not-allowed">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Statut</label>
                    <select wire:model="formStatut"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Observations</label>
                    <textarea wire:model="formObservations" rows="2"
                              class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition"></textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="$set('showModal', false)"
                        class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">
                    Annuler
                </button>
                @unless($viewMode)
                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    <span wire:loading.remove wire:target="save">{{ $formId ? 'Mettre à jour' : 'Créer' }}</span>
                    <span wire:loading wire:target="save">⏳</span>
                </button>
                @endunless
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL PAIEMENT --}}
    @if($showPaiement)
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-teal-700/40 rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">💳 Enregistrer un paiement</h3>
                <button wire:click="$set('showPaiement', false)" class="text-slate-500 hover:text-white transition">✕</button>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Montant (FCFA) <span class="text-red-400">*</span></label>
                    <input type="text" inputmode="decimal" wire:model="paiementMontant"
                           placeholder="ex: 50000"
                           class="w-full bg-slate-800 border border-teal-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 transition">
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
                        class="flex-1 py-2.5 bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold rounded-xl transition active:scale-95">✅ Valider</button>
            </div>
        </div>
    </div>
    @endif

</div>