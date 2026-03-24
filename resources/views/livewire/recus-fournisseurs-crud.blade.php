<div class="bg-slate-900 min-h-screen text-slate-100 p-6">
    <div class="max-w-7xl mx-auto">

        {{-- Messages flash --}}
        @if (session()->has('message'))
            <div class="bg-green-800 border-l-4 border-green-400 text-green-200 px-4 py-3 rounded-lg mb-6 animate-pulse shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-800 border-l-4 border-red-400 text-red-200 px-4 py-3 rounded-lg mb-6 animate-pulse shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- En-tête --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-8 gap-4">
            <h1 class="text-4xl font-extrabold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">
                Reçus fournisseurs
            </h1>
            <button wire:click="create" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 rounded-xl font-bold shadow-lg transition-all">
                ➕ Nouveau reçu
            </button>
        </div>

        {{-- Filtres --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 p-4 bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700">
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Recherche</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="N° reçu, fournisseur..." class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Fournisseur</label>
                <select wire:model.live="filterFournisseur" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
                    <option value="">Tous</option>
                    @foreach($fournisseurs as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Statut</label>
                <select wire:model.live="filterStatut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
                    <option value="">Tous</option>
                    <option value="impayé">Impayé</option>
                    <option value="partiel">Partiel</option>
                    <option value="payé">Payé</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Du</label>
                    <input type="date" wire:model.live="filterDateDebut" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Au</label>
                    <input type="date" wire:model.live="filterDateFin" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white">
                </div>
            </div>
        </div>

        {{-- Tableau --}}
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl border border-slate-700 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-800 border-b border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-400">N° reçu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-400">Fournisseur</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-400">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-400">Montant</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-slate-400">Payé</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($recus as $r)
                            @php
                                $totalPaye = $r->paiements ? $r->paiements->sum('montant') : 0;
                                if ($totalPaye <= 0) {
                                    $statut = 'impayé';
                                } elseif ($totalPaye < $r->montant_total) {
                                    $statut = 'partiel';
                                } else {
                                    $statut = 'payé';
                                }
                                $badgeColor = match($statut) {
                                    'payé' => 'bg-green-900/30 text-green-300 border-green-600/50',
                                    'partiel' => 'bg-yellow-900/30 text-yellow-300 border-yellow-600/50',
                                    default => 'bg-red-900/30 text-red-300 border-red-600/50',
                                };
                                $dotColor = match($statut) {
                                    'payé' => 'bg-green-400',
                                    'partiel' => 'bg-yellow-400',
                                    default => 'bg-red-400',
                                };
                            @endphp
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-blue-400">{{ $r->numero_recu }}</td>
                                <td class="px-6 py-4 text-slate-200">{{ $r->fournisseur->nom ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $r->date_recu->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-yellow-400">{{ number_format($r->montant_total, 0, ',', ' ') }} FCFA</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium border rounded-full {{ $badgeColor }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        {{ ucfirst($statut) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="show({{ $r->id }})" class="p-2 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 rounded-lg transition" title="Voir détails">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $r->id }})" class="p-2 bg-yellow-600/20 hover:bg-yellow-600/40 text-yellow-300 rounded-lg transition" title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $r->id }})" onclick="return confirm('Supprimer ce reçu ?')" class="p-2 bg-red-600/20 hover:bg-red-600/40 text-red-300 rounded-lg transition" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    Aucun reçu trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $recus->links() }}
        </div>

        {{-- Modal de détails --}}
        @if($showDetailModal && $detail)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                <div class="bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold text-white">Détails du reçu {{ $detail->numero_recu }}</h3>
                            <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-white text-3xl">&times;</button>
                        </div>

                        {{-- Infos générales --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-slate-700/30 p-4 rounded-xl mb-4">
                            <div><span class="text-slate-400">Fournisseur :</span> {{ $detail->fournisseur->nom ?? '-' }}</div>
                            <div><span class="text-slate-400">Date :</span> {{ $detail->date_recu->format('d/m/Y') }}</div>
                            <div><span class="text-slate-400">Montant total :</span> {{ number_format($detail->montant_total, 0, ',', ' ') }} FCFA</div>
                            <div><span class="text-slate-400">Total payé :</span> {{ number_format($detail->paiements->sum('montant') ?? 0, 0, ',', ' ') }} FCFA</div>
                            <div><span class="text-slate-400">Statut :</span> 
                                @php
                                    $totalPaye = $detail->paiements->sum('montant');
                                    $statut = $totalPaye <= 0 ? 'impayé' : ($totalPaye < $detail->montant_total ? 'partiel' : 'payé');
                                    $badge = match($statut) {
                                        'payé' => 'bg-green-600/20 text-green-300',
                                        'partiel' => 'bg-yellow-600/20 text-yellow-300',
                                        default => 'bg-red-600/20 text-red-300',
                                    };
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ ucfirst($statut) }}</span>
                            </div>
                            @if($detail->date_limite_paiement)
                                <div><span class="text-slate-400">Échéance :</span> {{ $detail->date_limite_paiement->format('d/m/Y') }}</div>
                            @endif
                        </div>

                        {{-- Lignes du reçu --}}
						<h4 class="text-lg font-semibold text-white mb-2">Lignes du reçu</h4>
						<div class="overflow-x-auto">
							<table class="w-full text-sm">
								<thead class="bg-slate-700/50">
									<tr>
										<th class="px-4 py-2 text-left">Variété</th>
										<th class="px-4 py-2 text-right">Quantité (kg)</th>
										<th class="px-4 py-2 text-right">Prix unitaire</th>
										<th class="px-4 py-2 text-right">Sous-total</th>
									</tr>
								</thead>
								<tbody class="divide-y divide-slate-700">
									@forelse($detail->lignes as $ligne)
										<tr>
											<td class="px-4 py-2">
												{{ $ligne->variete->nom ?? $ligne->variete->libelle ?? 'Variété inconnue' }}
											</td>
											<td class="px-4 py-2 text-right">{{ number_format($ligne->quantite_kg, 1, ',', ' ') }}</td>
											<td class="px-4 py-2 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
											<td class="px-4 py-2 text-right font-semibold text-yellow-400">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
										</tr>
									@empty
										<tr>
											<td colspan="4" class="px-4 py-4 text-center text-slate-400">Aucune ligne enregistrée pour ce reçu.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>

                        {{-- Historique des paiements --}}
                        <h4 class="text-lg font-semibold text-white mt-6 mb-2">Paiements associés</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-700/50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Date</th>
                                        <th class="px-4 py-2 text-right">Montant</th>
                                        <th class="px-4 py-2 text-left">Mode</th>
                                        <th class="px-4 py-2 text-left">Référence</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-700">
                                    @forelse($detail->paiements as $p)
                                        <tr>
                                            <td class="px-4 py-2">{{ $p->date_paiement->format('d/m/Y') }}</td>
                                            <td class="px-4 py-2 text-right font-semibold text-green-400">{{ number_format($p->montant, 0, ',', ' ') }}</td>
                                            <td class="px-4 py-2">{{ ucfirst($p->mode_paiement) }}</td>
                                            <td class="px-4 py-2 font-mono text-xs">{{ $p->reference ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-4 text-center text-slate-400">Aucun paiement</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg transition">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulaire de création/édition (à adapter selon votre code existant) --}}
        @if($showForm)
            {{-- Votre formulaire modal ici --}}
        @endif
    </div>
</div>