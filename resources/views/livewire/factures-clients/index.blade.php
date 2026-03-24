<div class="bg-slate-900 min-h-screen text-slate-100">
    <div class="py-6">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <span class="text-3xl">📋</span> Factures clients
                </h2>
                <button
                    wire:click="create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle facture
                </button>
            </div>

            {{-- Filtres et recherche --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Rechercher par n° facture ou client..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <select wire:model.live="filterClient" class="w-full px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model.live="filterStatut" class="w-full px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100">
                        <option value="">Tous les statuts</option>
                        <option value="payee">Payée</option>
                        <option value="credit">Crédit</option>
                        <option value="partiel">Partiel</option>
                        <option value="annulee">Annulée</option>
                    </select>
                </div>
                <div>
                    <input
                        type="date"
                        wire:model.live="filterDate"
                        class="w-full px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100"
                        placeholder="Date"
                    />
                </div>
                <div class="lg:col-span-1 flex justify-end">
                    <button
                        wire:click="resetFilters"
                        class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg transition"
                    >
                        Réinitialiser
                    </button>
                </div>
            </div>

            {{-- Tableau des factures --}}
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b border-slate-700">
                            <tr>
                                <th class="px-4 py-4 text-left font-semibold">N° facture</th>
                                <th class="px-4 py-4 text-left font-semibold">Client</th>
                                <th class="px-4 py-4 text-left font-semibold">Date</th>
                                <th class="px-4 py-4 text-right font-semibold">Montant total</th>
                                <th class="px-4 py-4 text-right font-semibold">Payé</th>
                                <th class="px-4 py-4 text-right font-semibold">Solde</th>
                                <th class="px-4 py-4 text-left font-semibold">Statut</th>
                                <th class="px-4 py-4 text-center font-semibold min-w-[320px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse ($factures as $facture)
                                @php
                                    $solde = $facture->solde_restant;
                                    $statut = $facture->statut;
                                    $badgeColor = match($statut) {
                                        'payee'   => 'bg-green-900/30 text-green-300 border-green-600/50',
                                        'credit'  => 'bg-yellow-900/30 text-yellow-300 border-yellow-600/50',
                                        'partiel' => 'bg-blue-900/30 text-blue-300 border-blue-600/50',
                                        'annulee' => 'bg-red-900/30 text-red-300 border-red-600/50',
                                        default   => 'bg-slate-700 text-slate-300',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 font-mono text-blue-400">{{ $facture->numero_facture }}</td>
                                    <td class="px-4 py-3 font-medium truncate max-w-[200px]" title="{{ $facture->client?->nom ?? 'N/A' }}">
                                        {{ $facture->client?->nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">{{ $facture->date_facture->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-yellow-400">
                                        {{ number_format($facture->montant_total, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono text-green-400">
                                        {{ number_format($facture->montant_paye, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono">
                                        @if($solde > 0)
                                            <span class="text-orange-400">{{ number_format($solde, 0, ',', ' ') }}</span>
                                        @else
                                            <span class="text-green-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium border rounded-full {{ $badgeColor }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statut === 'payee' ? 'bg-green-400' : ($statut === 'credit' ? 'bg-yellow-400' : ($statut === 'partiel' ? 'bg-blue-400' : 'bg-red-400')) }}"></span>
                                            {{ ucfirst($statut) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-end gap-1.5">
                                            {{-- Paiement --}}
                                            @if($solde > 0)
                                                <button
                                                    wire:click="ouvrirModalPaiement({{ $facture->id }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-md transition shadow-sm"
                                                    title="Enregistrer un paiement"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Paiement
                                                </button>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-green-300 bg-green-900/30 border border-green-600/40 rounded-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Soldée
                                                </span>
                                            @endif

                                            {{-- Historique des paiements --}}
                                            <a href="{{ route('paiements-factures', ['facture_id' => $facture->id]) }}"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-md transition shadow-sm"
                                               title="Voir les paiements">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                Historique
                                            </a>

                                            {{-- PDF --}}
                                            <button
                                                wire:click="telechargerPdf({{ $facture->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-purple-600 hover:bg-purple-700 text-white rounded-md transition shadow-sm"
                                                title="Télécharger la facture en PDF">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                PDF
                                            </button>

                                            {{-- Détails --}}
                                            <button
                                                wire:click="show({{ $facture->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-slate-600 hover:bg-slate-500 text-white rounded-md transition shadow-sm"
                                                title="Voir les détails"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Détails
                                            </button>

                                            {{-- Modifier --}}
                                            <button
                                                wire:click="edit({{ $facture->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition shadow-sm"
                                                title="Modifier la facture"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Modifier
                                            </button>

                                            {{-- Supprimer --}}
                                            <button
                                                wire:click="delete({{ $facture->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette facture ?"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-red-600 hover:bg-red-700 text-white rounded-md transition shadow-sm"
                                                title="Supprimer la facture"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <span class="text-6xl mb-4 opacity-50">📄</span>
                                            <span class="text-lg font-medium">Aucune facture trouvée</span>
                                            <p class="text-sm text-slate-500 mt-1">Commencez par créer une nouvelle facture</p>
                                            <button wire:click="create" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Nouvelle facture
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    {{ ($factures->currentPage() - 1) * $factures->perPage() + 1 }} - {{ min($factures->currentPage() * $factures->perPage(), $factures->total()) }} sur {{ $factures->total() }}
                </div>
                <div>{{ $factures->links() }}</div>
            </div>

            {{-- Modal de création / édition / détails --}}
            @if ($showModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                    <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">
                                {{ $viewMode ? 'Détails de la facture' : (isset($form['id']) && $form['id'] ? 'Modifier la facture' : 'Nouvelle facture') }}
                            </h3>

                            @if($viewMode)
                                {{-- Mode lecture seule --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Numéro facture</label>
                                        <p class="mt-1 text-white">{{ $form['numero_facture'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Client</label>
                                        <p class="mt-1 text-white">{{ $clients->firstWhere('id', $form['client_id'])?->nom ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Date facture</label>
                                        <p class="mt-1 text-white">{{ $form['date_facture'] ? \Carbon\Carbon::parse($form['date_facture'])->format('d/m/Y') : '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Montant total</label>
                                        <p class="mt-1 text-white">{{ isset($form['montant_total']) ? number_format($form['montant_total'], 0, ',', ' ') . ' FCFA' : '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Montant payé</label>
                                        <p class="mt-1 text-white">{{ isset($form['montant_paye']) ? number_format($form['montant_paye'], 0, ',', ' ') . ' FCFA' : '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Solde restant</label>
                                        <p class="mt-1 text-white">{{ isset($form['solde_restant']) ? number_format($form['solde_restant'], 0, ',', ' ') . ' FCFA' : '-' }}</p>
                                    </div>
                                    @php
                                        $badgeColor = match($form['statut'] ?? '') {
                                            'payee'   => 'bg-green-900/30 text-green-300 border-green-600/50',
                                            'credit'  => 'bg-yellow-900/30 text-yellow-300 border-yellow-600/50',
                                            'partiel' => 'bg-blue-900/30 text-blue-300 border-blue-600/50',
                                            'annulee' => 'bg-red-900/30 text-red-300 border-red-600/50',
                                            default   => 'bg-slate-700 text-slate-300',
                                        };
                                    @endphp
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Statut</label>
                                        <p class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium border rounded-full {{ $badgeColor }}">
                                                {{ $form['statut'] ?? '-' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Date échéance</label>
                                        <p class="mt-1 text-white">{{ $form['date_echeance'] ? \Carbon\Carbon::parse($form['date_echeance'])->format('d/m/Y') : '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Jours crédit</label>
                                        <p class="mt-1 text-white">{{ $form['jours_credit'] ?? '-' }}</p>
                                    </div>
                                    {{-- Correction : suppression des affichages de point_vente_id et agent_id (car absents du modèle) --}}
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Point de vente</label>
                                        <p class="mt-1 text-white">{{ $form['point_vente_id'] ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Agent</label>
                                        <p class="mt-1 text-white">{{ $form['agent_id'] ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- Lignes de facture --}}
								<div class="mt-6">
									<h4 class="font-medium text-slate-200 mb-3">Lignes de facture</h4>
									<table class="w-full text-sm">
										<thead class="bg-slate-700/50">
											 
												<th class="px-4 py-2 text-left">Article</th>
												<th class="px-4 py-2 text-right">Quantité</th>
												<th class="px-4 py-2 text-right">Prix unitaire</th>
												<th class="px-4 py-2 text-right">Montant</th>
											 
										</thead>
										<tbody class="divide-y divide-slate-700">
											@foreach ($lignes as $l)
												 
													<td class="px-4 py-2">
														{{ $l['description'] ?? ($l['type_produit'] . ' ' . ($l['poids_sac_kg'] ?? '') . ' ' . $l['unite']) }}
													</td>
													<td class="px-4 py-2 text-right">{{ number_format($l['quantite'], 0, ',', ' ') }}</td>
													<td class="px-4 py-2 text-right">{{ number_format($l['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
													<td class="px-4 py-2 text-right">{{ number_format($l['montant'], 0, ',', ' ') }} FCFA</td>
												 
											@endforeach
										</tbody>
									 
								</div>

                                <div class="mt-6 flex justify-end">
                                    <button
                                        type="button"
                                        wire:click="$set('showModal', false)"
                                        class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500"
                                    >
                                        Fermer
                                    </button>
                                </div>

                            @else
                                {{-- Formulaire d'édition / création --}}
                                <form wire:submit.prevent="save" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Numéro facture</label>
                                            <input
                                                type="text"
                                                wire:model="form.numero_facture"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                readonly
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Client</label>
                                            <select
                                                wire:model="form.client_id"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required
                                            >
                                                <option value="">Sélectionner</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('form.client_id') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Date facture</label>
                                            <input
                                                type="date"
                                                wire:model="form.date_facture"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required
                                            />
                                            @error('form.date_facture') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Date échéance</label>
                                            <input
                                                type="date"
                                                wire:model="form.date_echeance"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Jours crédit</label>
                                            <input
                                                type="number"
                                                wire:model="form.jours_credit"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Point de vente</label>
                                            <select
                                                wire:model="form.point_vente_id"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            >
                                                <option value="">Sélectionner</option>
                                                @foreach($pointsVente as $point)
                                                    <option value="{{ $point->id }}">{{ $point->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Agent</label>
                                            <select
                                                wire:model="form.agent_id"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            >
                                                <option value="">Sélectionner</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->prenom }} {{ $agent->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Lignes de facture --}}
                                    <div class="border-t border-slate-700 pt-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-slate-200">Lignes de facture</h4>
                                            <button
                                                type="button"
                                                wire:click="addLigne"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-md transition"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Ajouter une ligne
                                            </button>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach ($lignes as $index => $ligne)
                                                <div wire:key="ligne-{{ $index }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-slate-700/30 p-3 rounded-lg">
                                                    {{-- Article --}}
                                                    <div class="md:col-span-5">
                                                        <label class="block text-xs text-slate-400 mb-1">Article</label>
                                                        <select
                                                            wire:model="lignes.{{ $index }}.article_id"
                                                            wire:change="updatePrixUnitaire({{ $index }}, $event.target.value)"
                                                            class="w-full px-2 py-1.5 text-sm bg-slate-800 border border-slate-600 rounded text-white"
                                                        >
                                                            <option value="">Sélectionner un article</option>
                                                            @foreach($articles as $article)
                                                                <option value="{{ $article->id }}">{{ $article->nom }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    {{-- Quantité --}}
                                                    <div class="md:col-span-2">
                                                        <label class="block text-xs text-slate-400 mb-1">Qté</label>
                                                        <input
                                                            type="number"
                                                            wire:model.live="lignes.{{ $index }}.quantite"
                                                            step="1"
                                                            min="1"
                                                            class="w-full px-2 py-1.5 text-sm bg-slate-800 border border-slate-600 rounded text-white"
                                                        />
                                                    </div>

                                                    {{-- Prix unitaire (caché) --}}
                                                    <input type="hidden" wire:model="lignes.{{ $index }}.prix_unitaire" />

                                                    {{-- Sous‑total --}}
                                                    <div class="md:col-span-3">
                                                        <label class="block text-xs text-slate-400 mb-1">Sous‑total</label>
                                                        <div class="h-9 flex items-center text-sm font-mono text-yellow-400">
                                                            {{ number_format(($ligne['quantite'] ?? 0) * ($ligne['prix_unitaire'] ?? 0), 0, ',', ' ') }} FCFA
                                                        </div>
                                                    </div>

                                                    {{-- Supprimer --}}
                                                    <div class="md:col-span-2 flex items-end justify-end">
                                                        <button
                                                            type="button"
                                                            wire:click="removeLigne({{ $index }})"
                                                            class="p-1.5 text-red-400 hover:text-red-300"
                                                            title="Supprimer cette ligne"
                                                        >
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Boutons du formulaire --}}
                                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-700">
                                        <button
                                            type="button"
                                            wire:click="$set('showModal', false)"
                                            class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500 transition"
                                        >
                                            Annuler
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                        >
                                            {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Modal de paiement --}}
            @if ($showPaiementModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                    <div class="w-full max-w-md bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                💰 Enregistrer un paiement
                            </h3>

                            <form wire:submit="enregistrerPaiement">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-300 mb-1">Montant (FCFA)</label>
                                    <input
                                        type="number"
                                        wire:model="paiement_montant"
                                        step="1"
                                        min="1"
                                        class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:ring-2 focus:ring-green-500"
                                        required
                                    />
                                    @error('paiement_montant')
                                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-slate-300 mb-1">Mode de paiement</label>
                                    <select wire:model="paiement_mode" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white" required>
                                        <option value="espèces">💵 Espèces</option>
                                        <option value="mobile_money">📱 Mobile Money</option>
                                        <option value="chèque">📄 Chèque</option>
                                        <option value="virement">🏦 Virement</option>
                                    </select>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button
                                        type="button"
                                        wire:click="$set('showPaiementModal', false)"
                                        class="px-6 py-2.5 bg-slate-600 hover:bg-slate-500 text-white font-medium rounded-lg"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg"
                                    >
                                        ✅ Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>