<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- En-tête --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">🛒 Nouvelle commande</h1>
        <p class="text-slate-400 text-sm mt-1">Vente comptant ou à crédit</p>
    </div>

    {{-- Messages --}}
		{{-- Messages --}}
		@if($successMessage)
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{!! $successMessage !!}
				<button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
			</div>
		@endif

		@if($errorMessage)
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				{!! $errorMessage !!}
				<button type="button" class="btn-close" wire:click="$set('errorMessage', '')"></button>
			</div>
		@endif
		
    {{-- Indicateur d'étapes --}}
    <div class="flex items-center mb-8">
        @foreach([1 => 'Informations', 2 => 'Produits', 3 => 'Confirmation'] as $n => $label)
            <div class="flex items-center {{ !$loop->first ? 'flex-1' : '' }}">
                @if(!$loop->first)
                    <div class="flex-1 h-0.5 {{ $etape > $n - 1 ? 'bg-green-500' : 'bg-slate-600' }}"></div>
                @endif
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                        {{ $etape === $n ? 'bg-green-500 text-white' : ($etape > $n ? 'bg-green-700 text-green-200' : 'bg-slate-700 text-slate-400') }}">
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
    {{-- ÉTAPE 1 — Informations générales                             --}}
    {{-- ============================================================ --}}
    @if($etape === 1)
    <div class="bg-slate-800 border border-slate-600 rounded-xl p-6 space-y-6">

        {{-- Type de vente --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-3">Type de vente</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model.live="type_vente" value="comptant"
                           class="accent-green-500">
                    <span class="text-white font-medium">💵 Comptant</span>
                    <span class="text-slate-400 text-xs">Paiement immédiat</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" wire:model.live="type_vente" value="credit"
                           class="accent-green-500">
                    <span class="text-white font-medium">📋 Crédit</span>
                    <span class="text-slate-400 text-xs">Paiement différé</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Client --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Client <span class="text-red-400">*</span>
                </label>
                <select wire:model="client_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                    <option value="">— Sélectionner un client —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->code_client }} — {{ $client->nom }}
                            {{ $client->prenom ? $client->prenom : '' }}
                            {{ $client->raison_sociale ? '(' . $client->raison_sociale . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('client_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Agent --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Agent <span class="text-red-400">*</span>
                </label>
                <select wire:model="agent_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                    <option value="">— Sélectionner un agent —</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->prenom }} {{ $agent->nom }}</option>
                    @endforeach
                </select>
                @error('agent_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Point de vente --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Point de vente <span class="text-red-400">*</span>
                </label>
                <select wire:model.live="point_vente_id"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                    <option value="">— Sélectionner un point de vente —</option>
                    @foreach($pointsVente as $pv)
                        <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                    @endforeach
                </select>
                @error('point_vente_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date commande --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Date de commande <span class="text-red-400">*</span>
                </label>
                <input type="date" wire:model="date_commande"
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                @error('date_commande') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date livraison prévue --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Date livraison prévue
                    <span class="text-slate-500 text-xs">(optionnel)</span>
                </label>
                <input type="date" wire:model="date_livraison_prevue"
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
            </div>

            {{-- Date échéance (crédit uniquement) --}}
            @if($type_vente === 'credit')
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">
                    Date d'échéance <span class="text-red-400">*</span>
                </label>
                <input type="date" wire:model="date_echeance"
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                @error('date_echeance') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif

        </div>

        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1">Notes</label>
            <textarea wire:model="notes" rows="2"
                      placeholder="Observations, instructions de livraison..."
                      class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500 resize-none"></textarea>
        </div>

        <div class="flex justify-end">
            <button wire:click="allerEtape2"
                    class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg transition">
                Suivant — Ajouter les produits →
            </button>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ÉTAPE 2 — Lignes de produits                                 --}}
    {{-- ============================================================ --}}
    @if($etape === 2)
    <div class="space-y-6">

        {{-- Formulaire ajout ligne --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">➕ Ajouter un produit</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Unité de vente --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Unité</label>
                    <select wire:model.live="ligne_unite"
                            class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                        <option value="sac">Sac</option>
                        <option value="kg">Kg (vrac)</option>
                    </select>
                </div>

                {{-- Sélection sac (si unité = sac) --}}
                @if($ligne_unite === 'sac')
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Sac disponible
                        @if($point_vente_id && empty($stocksDisponibles))
                            <span class="text-yellow-400 text-xs ml-2">⚠ Aucun stock disponible</span>
                        @endif
                    </label>
                    <select wire:model.live="ligne_sac_id"
                            class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                        <option value="">— Sélectionner un sac —</option>
                        @foreach($stocksDisponibles as $stock)
                            <option value="{{ $stock['sac_id'] }}">{{ $stock['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                {{-- Vrac : type produit --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Type produit</label>
                    <select wire:model.live="ligne_type_produit"
                            class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                        <option value="riz_blanc">Riz blanc</option>
                        <option value="son">Son</option>
                        <option value="brisures">Brisures</option>
                    </select>
                </div>
                @endif

                {{-- Quantité --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Quantité ({{ $ligne_unite === 'sac' ? 'sacs' : 'kg' }})
                    </label>
                    <input type="number" wire:model.live="ligne_quantite" min="1"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                </div>

                {{-- Prix unitaire --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Prix unitaire (FCFA)
                    </label>
                    <input type="number"
                           wire:model.live="ligne_prix_unitaire"
                           wire:key="prix-{{ $ligne_sac_id }}"
                           min="0"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                </div>

                {{-- Remise ligne --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Remise (FCFA)
                    </label>
                    <input type="number" wire:model.live="ligne_remise" min="0"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                </div>

                {{-- Sous-total ligne --}}
                <div class="flex items-end">
                    <div class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2">
                        <p class="text-xs text-slate-400">Sous-total</p>
                        <p class="text-white font-bold text-lg">
                            {{ number_format(((int)$ligne_quantite * (int)$ligne_prix_unitaire) - (int)$ligne_remise, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>

            </div>

            <div class="flex justify-end mt-4">
                <button wire:click="ajouterLigne"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg transition">
                    ➕ Ajouter à la commande
                </button>
            </div>
        </div>

        {{-- Tableau des lignes --}}
        @if(!empty($lignes))
        <div class="bg-slate-800 border border-slate-600 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-600">
                <h2 class="text-lg font-semibold text-white">📋 Lignes de commande</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-slate-700 text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-left">Produit</th>
                        <th class="px-4 py-3 text-center">Qté</th>
                        <th class="px-4 py-3 text-right">Prix unit.</th>
                        <th class="px-4 py-3 text-right">Remise</th>
                        <th class="px-4 py-3 text-right">Sous-total</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($lignes as $i => $ligne)
                    <tr class="text-slate-200 hover:bg-slate-700/50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $ligne['code_sac'] }}</p>
                            <p class="text-xs text-slate-400">
                                {{ match($ligne['type_produit']) {
                                    'riz_blanc' => 'Riz blanc',
                                    'son'       => 'Son',
                                    'brisures'  => 'Brisures',
                                    default     => $ligne['type_produit']
                                } }}
                                @if($ligne['poids_sac_kg']) · {{ $ligne['poids_sac_kg'] }} kg @endif
                            </p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            {{ $ligne['quantite'] }} {{ $ligne['unite'] }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            {{ number_format($ligne['prix_unitaire'], 0, ',', ' ') }}
                        </td>
                        <td class="px-4 py-3 text-right text-yellow-400">
                            {{ $ligne['remise'] > 0 ? '- ' . number_format($ligne['remise'], 0, ',', ' ') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-400">
                            {{ number_format($ligne['sous_total'], 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="supprimerLigne({{ $i }})"
                                    class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-900/30 transition">
                                🗑 Suppr.
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-700">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-semibold text-slate-300">
                            Total commande
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-white text-base">
                            {{ number_format($this->getTotalCommande(), 0, ',', ' ') }} FCFA
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="bg-slate-800 border border-dashed border-slate-600 rounded-xl p-12 text-center">
            <p class="text-slate-400 text-4xl mb-3">🛒</p>
            <p class="text-slate-400">Aucune ligne ajoutée. Sélectionnez un produit ci-dessus.</p>
        </div>
        @endif

        {{-- Navigation --}}
        <div class="flex justify-between">
            <button wire:click="retourEtape(1)"
                    class="px-5 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg transition">
                ← Retour
            </button>
            <button wire:click="allerEtape3" @if(empty($lignes)) disabled @endif
                    class="px-6 py-2 bg-green-600 hover:bg-green-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition">
                Suivant — Confirmer →
            </button>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ÉTAPE 3 — Confirmation & Paiement                            --}}
    {{-- ============================================================ --}}
    @if($etape === 3)
    <div class="space-y-6">

        {{-- Récapitulatif --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">📋 Récapitulatif</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-700 rounded-lg p-3">
                    <p class="text-xs text-slate-400">Type</p>
                    <p class="text-white font-semibold capitalize">
                        {{ $type_vente === 'comptant' ? '💵 Comptant' : '📋 Crédit' }}
                    </p>
                </div>
                <div class="bg-slate-700 rounded-lg p-3">
                    <p class="text-xs text-slate-400">Lignes</p>
                    <p class="text-white font-semibold">{{ count($lignes) }} produit(s)</p>
                </div>
                <div class="bg-slate-700 rounded-lg p-3">
                    <p class="text-xs text-slate-400">Sous-total</p>
                    <p class="text-white font-semibold">{{ number_format($this->getTotalCommande(), 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="bg-green-900/50 border border-green-700 rounded-lg p-3">
                    <p class="text-xs text-green-400">Total net</p>
                    <p class="text-green-300 font-bold text-lg">{{ number_format($this->getTotalAvecRemise(), 0, ',', ' ') }} FCFA</p>
                </div>
            </div>

            {{-- Remise globale --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Remise globale (FCFA)
                    </label>
                    <input type="number" wire:model.live="remise_globale" min="0"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                </div>

                {{-- Acompte (crédit uniquement) --}}
                @if($type_vente === 'credit')
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">
                        Acompte à encaisser (FCFA)
                    </label>
                    <input type="number" wire:model.live="montant_acompte"
                           min="0" max="{{ $this->getTotalAvecRemise() }}"
                           class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 focus:outline-none focus:border-green-500">
                    <p class="text-slate-400 text-xs mt-1">
                        Solde restant :
                        <span class="text-yellow-400 font-semibold">
                            {{ number_format($this->getSolde(), 0, ',', ' ') }} FCFA
                        </span>
                        — Échéance : {{ $date_echeance ?: '—' }}
                    </p>
                </div>
                @else
                <div class="bg-green-900/30 border border-green-700 rounded-lg p-3 flex items-center gap-2">
                    <span class="text-green-400 text-xl">✅</span>
                    <div>
                        <p class="text-green-300 font-semibold">Paiement comptant</p>
                        <p class="text-green-400 text-sm">
                            {{ number_format($this->getTotalAvecRemise(), 0, ',', ' ') }} FCFA encaissés
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Options --}}
            <div class="mt-4 flex items-center gap-3">
                <input type="checkbox" wire:model="generer_facture" id="generer_facture"
                       class="w-4 h-4 accent-green-500">
                <label for="generer_facture" class="text-slate-300 text-sm cursor-pointer">
                    📄 Générer une facture client
                    @if($type_vente === 'credit')
                        <span class="text-green-400 text-xs ml-1">(automatique pour crédit)</span>
                    @endif
                </label>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex justify-between">
            <button wire:click="retourEtape(2)"
                    class="px-5 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg transition">
                ← Retour
            </button>
            <button wire:click="enregistrer"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-wait"
                    class="px-8 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-lg transition flex items-center gap-2">
                <span wire:loading.remove wire:target="enregistrer">✅ Enregistrer la commande</span>
                <span wire:loading wire:target="enregistrer">⏳ Enregistrement...</span>
            </button>
        </div>
    </div>
    @endif

</div>