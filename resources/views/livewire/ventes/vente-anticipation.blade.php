<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- En-tête --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">⏳ Vente par anticipation</h1>
        <p class="text-slate-400 text-sm mt-1">
            Le stock est <strong class="text-purple-400">réservé immédiatement</strong> à la commande.
            La livraison physique intervient à la date prévue.
        </p>
    </div>

    {{-- Messages --}}
    @if($successMessage)
        <div class="mb-6 p-4 bg-green-900/50 border border-green-600 rounded-xl text-green-300 flex items-start gap-3">
            <span class="text-xl">✅</span>
            <div>
                <p class="font-semibold">{{ $successMessage }}</p>
                @if($codeCommande)
                    <p class="text-sm mt-1">Commande : <strong class="font-mono">{{ $codeCommande }}</strong></p>
                @endif
                <div class="flex gap-3 mt-3">
                    <a href="{{ route('commandes.index') }}"
                       class="px-4 py-1.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-lg transition">
                        ← Voir les commandes
                    </a>
                    <button wire:click="$set('successMessage', '')"
                            class="px-4 py-1.5 bg-purple-700 hover:bg-purple-600 text-white text-sm rounded-lg transition">
                        ➕ Nouvelle anticipation
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-6 p-4 bg-red-900/50 border border-red-600 rounded-xl text-red-300 flex items-center gap-3">
            <span>⚠️</span> {{ $errorMessage }}
        </div>
    @endif

    {{-- Indicateur étapes --}}
    <div class="flex items-center mb-8">
        @foreach([1 => 'Informations', 2 => 'Produits', 3 => 'Acompte'] as $n => $label)
            <div class="flex items-center {{ !$loop->first ? 'flex-1' : '' }}">
                @if(!$loop->first)
                    <div class="flex-1 h-0.5 {{ $etape > $n - 1 ? 'bg-purple-500' : 'bg-slate-600' }}"></div>
                @endif
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        {{ $etape === $n ? 'bg-purple-600 text-white' : ($etape > $n ? 'bg-purple-800 text-purple-200' : 'bg-slate-700 text-slate-400') }}">
                        {{ $etape > $n ? '✓' : $n }}
                    </div>
                    <span class="text-sm {{ $etape === $n ? 'text-white font-semibold' : 'text-slate-400' }}">
                        {{ $label }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- ÉTAPE 1 — Informations                                        --}}
    {{-- ============================================================ --}}
    @if($etape === 1)
    <div class="bg-slate-800 border border-slate-600 rounded-xl p-6 space-y-6">

        {{-- Bandeau explicatif --}}
        <div class="bg-purple-900/30 border border-purple-700 rounded-lg p-4 flex items-start gap-3">
            <span class="text-2xl">⏳</span>
            <div class="text-sm">
                <p class="text-purple-300 font-semibold">Comment fonctionne l'anticipation ?</p>
                <p class="text-slate-400 mt-1">
                    Le client paie un acompte aujourd'hui. Le stock est <strong class="text-white">bloqué pour lui</strong>
                    jusqu'à la date de livraison prévue. Le solde est encaissé à la livraison.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Client <span class="text-red-400">*</span></label>
                <select wire:model="client_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                    <option value="">— Sélectionner —</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->code_client }} — {{ $c->nom }} {{ $c->prenom }}</option>
                    @endforeach
                </select>
                @error('client_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Agent <span class="text-red-400">*</span></label>
                <select wire:model="agent_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                    <option value="">— Sélectionner —</option>
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                    @endforeach
                </select>
                @error('agent_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Point de vente <span class="text-red-400">*</span></label>
                <select wire:model.live="point_vente_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                    <option value="">— Sélectionner —</option>
                    @foreach($pointsVente as $pv)
                        <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                    @endforeach
                </select>
                @error('point_vente_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Date de commande <span class="text-red-400">*</span></label>
                <input type="date" wire:model="date_commande"
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Date de livraison prévue <span class="text-red-400">*</span>
                    <span class="text-slate-500 text-xs ml-1">(le stock sera réservé jusqu'à cette date)</span>
                </label>
                <input type="date" wire:model="date_livraison_prevue"
                       class="w-full bg-slate-700 border border-purple-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-400">
                @error('date_livraison_prevue') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-300 mb-1">Notes</label>
                <textarea wire:model="notes" rows="2"
                          class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500 resize-none"></textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="allerEtape2"
                    class="px-6 py-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-lg transition">
                Suivant → Choisir les produits
            </button>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ÉTAPE 2 — Produits                                            --}}
    {{-- ============================================================ --}}
    @if($etape === 2)
    <div class="space-y-6">

        {{-- Formulaire ligne --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">➕ Ajouter un produit à réserver</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1">Sac disponible</label>
                    <select wire:model.live="ligne_sac_id"
                            class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                        <option value="">— Sélectionner un sac —</option>
                        @foreach($stocksDisponibles as $s)
                            <option value="{{ $s['sac_id'] }}">{{ $s['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Quantité (sacs)</label>
                    <input type="number" wire:model="ligne_quantite" min="1"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Prix unitaire (FCFA)</label>
                    <input type="number" wire:model.live="ligne_prix_unitaire" min="0"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button wire:click="ajouterLigne"
                        class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-lg transition">
                    🔒 Réserver ce produit
                </button>
            </div>
        </div>

        {{-- Tableau lignes --}}
        @if(!empty($lignes))
        <div class="bg-slate-800 border border-purple-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-purple-700 flex items-center gap-2">
                <span class="text-purple-400">🔒</span>
                <h2 class="text-base font-semibold text-white">Produits réservés</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-slate-700 text-slate-300 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Produit</th>
                        <th class="px-4 py-3 text-center">Qté</th>
                        <th class="px-4 py-3 text-right">Prix unit.</th>
                        <th class="px-4 py-3 text-right">Sous-total</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($lignes as $i => $l)
                    <tr class="text-slate-200">
                        <td class="px-4 py-3">
                            <p class="font-mono text-xs font-semibold text-white">{{ $l['code_sac'] }}</p>
                            <p class="text-slate-400 text-xs">{{ $l['type_produit'] }} @if($l['poids_sac_kg']) · {{ $l['poids_sac_kg'] }}kg @endif</p>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $l['quantite'] }} sac(s)</td>
                        <td class="px-4 py-3 text-right">{{ number_format($l['prix_unitaire'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-purple-400">
                            {{ number_format($l['sous_total'], 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="supprimerLigne({{ $i }})"
                                    class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-900/30 transition">
                                🗑
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-700">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold text-slate-300">Total à réserver</td>
                        <td class="px-4 py-3 text-right font-bold text-white text-base">
                            {{ number_format($this->getTotalCommande(), 0, ',', ' ') }} FCFA
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="bg-slate-800 border border-dashed border-slate-600 rounded-xl p-12 text-center text-slate-400">
            <p class="text-4xl mb-3">🔒</p>
            <p>Aucun produit ajouté. Sélectionnez un sac à réserver.</p>
        </div>
        @endif

        <div class="flex justify-between">
            <button wire:click="retourEtape(1)"
                    class="px-5 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg transition">← Retour</button>
            <button wire:click="allerEtape3" @if(empty($lignes)) disabled @endif
                    class="px-6 py-2 bg-purple-600 hover:bg-purple-500 disabled:opacity-40 text-white font-semibold rounded-lg transition">
                Suivant → Acompte →
            </button>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ÉTAPE 3 — Acompte                                             --}}
    {{-- ============================================================ --}}
    @if($etape === 3)
    <div class="space-y-6">

        {{-- Récap --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">💰 Acompte de réservation</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">
                            Montant acompte (FCFA) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" wire:model.live="montant_acompte"
                               min="1" max="{{ $this->getTotalCommande() }}"
                               class="w-full bg-slate-700 border border-purple-600 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-400">
                        <p class="text-slate-500 text-xs mt-1">
                            Minimum recommandé : 50% du total
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Mode de paiement</label>
                        <select wire:model="mode_paiement"
                                class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-purple-500">
                            <option value="especes">💵 Espèces</option>
                            <option value="mobile_money">📱 Mobile Money</option>
                            <option value="virement">🏦 Virement</option>
                            <option value="cheque">📝 Chèque</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="generer_facture" class="w-4 h-4 accent-purple-500">
                        <span class="text-slate-300 text-sm">📄 Générer une facture client</span>
                    </label>
                </div>

                {{-- Résumé financier --}}
                <div class="bg-slate-900 border border-slate-600 rounded-xl p-5 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Total commande</span>
                        <span class="text-white font-semibold">
                            {{ number_format($this->getTotalCommande(), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400">Acompte encaissé</span>
                        <span class="text-purple-400 font-semibold">
                            {{ number_format($montant_acompte, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="border-t border-slate-600 pt-3 flex justify-between">
                        <span class="text-slate-300 font-semibold">Solde à la livraison</span>
                        <span class="text-yellow-400 font-bold text-lg">
                            {{ number_format($this->getSolde(), 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="pt-2">
                        <div class="flex justify-between text-xs text-slate-400 mb-1">
                            <span>Acompte</span>
                            <span>
                                {{ $this->getTotalCommande() > 0 ? round(($montant_acompte / $this->getTotalCommande()) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="h-2 rounded-full bg-purple-500 transition-all"
                                 style="width: {{ $this->getTotalCommande() > 0 ? min(100, round(($montant_acompte / $this->getTotalCommande()) * 100)) : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-900/30 border border-purple-700 rounded-lg p-3 text-xs text-purple-300">
                        🔒 Stock réservé jusqu'au
                        <strong>{{ now()->parse($date_livraison_prevue)->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <button wire:click="retourEtape(2)"
                    class="px-5 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg transition">← Retour</button>
            <button wire:click="enregistrer"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-wait"
                    class="px-8 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-lg transition">
                <span wire:loading.remove wire:target="enregistrer">🔒 Confirmer et réserver le stock</span>
                <span wire:loading wire:target="enregistrer">⏳ Enregistrement...</span>
            </button>
        </div>
    </div>
    @endif

</div>