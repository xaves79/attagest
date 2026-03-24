<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">💵 Commandes de vente</h1>
            <p class="text-slate-400 text-sm mt-1">Suivi de toutes les commandes</p>
        </div>
        <a href="{{ route('commandes.nouvelle') }}"
           class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg transition flex items-center gap-2">
            ➕ Nouvelle commande
        </a>
    </div>

    {{-- Messages --}}
    @if($successMessage)
        <div class="mb-4 p-4 bg-green-900/50 border border-green-600 rounded-lg text-green-300 flex items-center gap-3">
            <span>✅</span> {{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div class="mb-4 p-4 bg-red-900/50 border border-red-600 rounded-lg text-red-300 flex items-center gap-3">
            <span>⚠️</span> {{ $errorMessage }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Total commandes</p>
            <p class="text-2xl font-bold text-white">{{ number_format($stats->total) }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">CA total</p>
            <p class="text-xl font-bold text-green-400">
                {{ number_format($stats->ca_total, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">CA livré</p>
            <p class="text-xl font-bold text-blue-400">
                {{ number_format($stats->ca_livre, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Solde en attente</p>
            <p class="text-xl font-bold text-yellow-400">
                {{ number_format($stats->en_attente, 0, ',', ' ') }} <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-slate-800 border border-slate-600 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">

            {{-- Recherche --}}
            <div class="lg:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="🔍 Code commande, client..."
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 placeholder-slate-400">
            </div>

            {{-- Statut --}}
            <div>
                <select wire:model.live="filtreStatut"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous statuts</option>
                    <option value="brouillon">Brouillon</option>
                    <option value="confirmee">Confirmée</option>
                    <option value="en_attente_livraison">En attente livraison</option>
                    <option value="partiellement_livree">Part. livrée</option>
                    <option value="livree">Livrée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            {{-- Type --}}
            <div>
                <select wire:model.live="filtreType"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous types</option>
                    <option value="comptant">Comptant</option>
                    <option value="credit">Crédit</option>
                    <option value="anticipation">Anticipation</option>
                    <option value="gros">Gros</option>
                </select>
            </div>

            {{-- Point de vente --}}
            <div>
                <select wire:model.live="filtrePointVente"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous points</option>
                    @foreach($pointsVente as $pv)
                        <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <div class="flex items-center">
                <button wire:click="resetFiltres"
                        class="w-full px-3 py-2 bg-slate-600 hover:bg-slate-500 text-slate-300 text-sm rounded-lg transition">
                    🔄 Réinitialiser
                </button>
            </div>
        </div>

        {{-- Dates --}}
        <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm whitespace-nowrap">Du</span>
                <input type="date" wire:model.live="filtreDateDu"
                       class="flex-1 bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm whitespace-nowrap">Au</span>
                <input type="date" wire:model.live="filtreDateAu"
                       class="flex-1 bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-slate-800 border border-slate-600 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-700 text-slate-300 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-center">Type</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-center">Date</th>
                        <th class="px-4 py-3 text-right">Montant</th>
                        <th class="px-4 py-3 text-right">Solde</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">

                    @forelse($commandes as $commande)
                    <tr class="text-slate-200 hover:bg-slate-700/40 transition">

                        {{-- Code --}}
                        <td class="px-4 py-3">
                            <p class="font-mono font-semibold text-white text-xs">
                                {{ $commande->code_commande }}
                            </p>
                            <p class="text-slate-400 text-xs">
                                {{ $commande->lignes_count ?? $commande->lignes->count() }} ligne(s)
                            </p>
                        </td>

                        {{-- Client --}}
                        <td class="px-4 py-3">
                            <p class="font-medium text-white">
                                {{ $commande->client->raison_sociale ?? $commande->client->nom . ' ' . $commande->client->prenom }}
                            </p>
                            <p class="text-slate-400 text-xs">
                                {{ $commande->pointVente->nom ?? '—' }}
                            </p>
                        </td>

                        {{-- Type --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $typeLabels = [
                                    'comptant'    => ['label' => 'Comptant',    'class' => 'bg-blue-900/50 text-blue-300 border-blue-700'],
                                    'credit'      => ['label' => 'Crédit',      'class' => 'bg-orange-900/50 text-orange-300 border-orange-700'],
                                    'anticipation'=> ['label' => 'Anticipation','class' => 'bg-purple-900/50 text-purple-300 border-purple-700'],
                                    'gros'        => ['label' => 'Gros',        'class' => 'bg-cyan-900/50 text-cyan-300 border-cyan-700'],
                                ];
                                $t = $typeLabels[$commande->type_vente] ?? ['label' => $commande->type_vente, 'class' => 'bg-slate-700 text-slate-300'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs border {{ $t['class'] }}">
                                {{ $t['label'] }}
                            </span>
                        </td>

                        {{-- Statut --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $statutLabels = [
                                    'brouillon'              => ['label' => '✏ Brouillon',        'class' => 'bg-slate-700 text-slate-300'],
                                    'confirmee'              => ['label' => '✅ Confirmée',         'class' => 'bg-green-900/50 text-green-300'],
                                    'en_attente_livraison'   => ['label' => '⏳ Att. livraison',   'class' => 'bg-yellow-900/50 text-yellow-300'],
                                    'partiellement_livree'   => ['label' => '📦 Part. livrée',     'class' => 'bg-blue-900/50 text-blue-300'],
                                    'livree'                 => ['label' => '🚚 Livrée',           'class' => 'bg-emerald-900/50 text-emerald-300'],
                                    'annulee'                => ['label' => '❌ Annulée',           'class' => 'bg-red-900/50 text-red-300'],
                                ];
                                $s = $statutLabels[$commande->statut] ?? ['label' => $commande->statut, 'class' => 'bg-slate-700 text-slate-300'];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $s['class'] }}">
                                {{ $s['label'] }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="px-4 py-3 text-center text-slate-300 text-xs">
                            <p>{{ $commande->date_commande?->format('d/m/Y') }}</p>
                            @if($commande->date_echeance)
                                <p class="text-yellow-400 mt-0.5">
                                    Éch. {{ $commande->date_echeance->format('d/m/Y') }}
                                </p>
                            @endif
                        </td>

                        {{-- Montant --}}
                        <td class="px-4 py-3 text-right">
                            <p class="font-semibold text-white">
                                {{ number_format($commande->montant_total_fcfa, 0, ',', ' ') }}
                            </p>
                            <p class="text-slate-400 text-xs">FCFA</p>
                        </td>

                        {{-- Solde --}}
                        <td class="px-4 py-3 text-right">
                            @if($commande->montant_solde_fcfa > 0 && $commande->statut !== 'annulee')
                                <p class="font-semibold text-yellow-400">
                                    {{ number_format($commande->montant_solde_fcfa, 0, ',', ' ') }}
                                </p>
                                <p class="text-slate-400 text-xs">FCFA</p>
                            @elseif($commande->statut === 'annulee')
                                <span class="text-slate-500 text-xs">—</span>
                            @else
                                <span class="text-green-400 text-xs font-semibold">✅ Soldé</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                        class="px-3 py-1 bg-slate-600 hover:bg-slate-500 text-white text-xs rounded-lg transition">
                                    Actions ▾
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     x-transition
                                     class="absolute right-0 mt-1 w-52 bg-slate-700 border border-slate-500 rounded-lg shadow-xl z-20">
                                    <div class="py-1">

                                        {{-- Voir détail --}}
                                        <a href="{{ route('commandes.show', $commande->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-200 hover:bg-slate-600">
                                            🔍 Voir le détail
                                        </a>

                                        {{-- Livrer (si confirmée ou part. livrée) --}}
                                        @if(in_array($commande->statut, ['confirmee', 'partiellement_livree', 'en_attente_livraison']))
                                            <a href="{{ route('commandes.livrer', $commande->id) }}"
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-blue-300 hover:bg-slate-600">
                                                🚚 Enregistrer livraison
                                            </a>
                                        @endif

                                        {{-- Imprimer facture --}}
                                        @if($commande->facture_id)
                                            <a href="{{ route('factures.imprimer', $commande->facture_id) }}"
                                               target="_blank"
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-yellow-300 hover:bg-slate-600">
                                                🖨 Imprimer facture
                                            </a>
                                        @endif

                                        {{-- Annuler --}}
                                        @if(!in_array($commande->statut, ['livree', 'annulee']))
                                            <div class="border-t border-slate-600 mt-1 pt-1">
                                                <button wire:click="annulerCommande({{ $commande->id }})"
                                                        wire:confirm="Confirmer l'annulation de {{ $commande->code_commande }} ?"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-900/30 w-full text-left">
                                                    ❌ Annuler la commande
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center text-slate-400">
                            <p class="text-4xl mb-3">📭</p>
                            <p class="font-medium">Aucune commande trouvée</p>
                            @if($search || $filtreStatut || $filtreType)
                                <p class="text-sm mt-1">Essayez de modifier vos filtres</p>
                            @else
                                <a href="{{ route('commandes.nouvelle') }}"
                                   class="inline-block mt-4 px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-sm transition">
                                    ➕ Créer la première commande
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($commandes->hasPages())
        <div class="px-4 py-3 border-t border-slate-600">
            {{ $commandes->links() }}
        </div>
        @endif
    </div>

    {{-- Compteur résultats --}}
    <p class="text-slate-500 text-xs mt-3">
        {{ $commandes->total() }} commande(s) trouvée(s)
        @if($search) pour « {{ $search }} » @endif
    </p>

</div>