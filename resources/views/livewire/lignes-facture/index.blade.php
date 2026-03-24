<div class="bg-slate-900 min-h-screen text-slate-100">
    <div class="py-6">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- En-tête --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <span class="text-3xl">🧾</span> Lignes de facture
                </h2>
                <button
                    wire:click="create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle ligne
                </button>
            </div>

            {{-- Recherche --}}
            <div class="mb-6">
                <div class="relative max-w-md">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher par facture ou article..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    />
                    <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Tableau des lignes --}}
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b border-slate-700">
                            <tr>
                                <th class="px-4 py-4 text-left font-semibold">Facture</th>
                                <th class="px-4 py-4 text-left font-semibold">Article</th>
                                <th class="px-4 py-4 text-right font-semibold">Quantité</th>
                                <th class="px-4 py-4 text-right font-semibold">Prix unitaire</th>
                                <th class="px-4 py-4 text-right font-semibold">Montant</th>
                                <th class="px-4 py-4 text-center font-semibold w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse ($lignes as $ligne)
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 font-mono text-blue-400">{{ $ligne->facture?->numero_facture ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $ligne->article?->nom ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($ligne->quantite, 0, ',', ' ') }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-4 py-3 text-right font-mono text-yellow-400">{{ number_format($ligne->montant, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button
                                                wire:click="show({{ $ligne->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-slate-600 hover:bg-slate-500 text-white rounded-md transition shadow-sm"
                                                title="Voir les détails"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Détails
                                            </button>

                                            <button
                                                wire:click="edit({{ $ligne->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition shadow-sm"
                                                title="Modifier"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Modifier
                                            </button>

                                            <button
                                                wire:click="delete({{ $ligne->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette ligne ?"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium bg-red-600 hover:bg-red-700 text-white rounded-md transition shadow-sm"
                                                title="Supprimer"
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
                                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <span class="text-6xl mb-4 opacity-50">🧾</span>
                                            <span class="text-lg font-medium">Aucune ligne de facture trouvée</span>
                                            <p class="text-sm text-slate-500 mt-1">Commencez par créer une nouvelle ligne</p>
                                            <button wire:click="create" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Nouvelle ligne
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
                    {{ ($lignes->currentPage() - 1) * $lignes->perPage() + 1 }} - {{ min($lignes->currentPage() * $lignes->perPage(), $lignes->total()) }} sur {{ $lignes->total() }}
                </div>
                <div>{{ $lignes->links() }}</div>
            </div>

            {{-- Modal de création/édition/détails (similaire à celui de factures-clients) --}}
            @if ($showModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                    <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white mb-4">
                                {{ $viewMode ? 'Détails de la ligne' : ($form['id'] ? 'Modifier la ligne' : 'Nouvelle ligne') }}
                            </h3>

                            @if($viewMode)
                                {{-- Mode lecture seule --}}
                                <div class="grid grid-cols-1 gap-4 text-sm">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Facture</label>
                                        <p class="mt-1 text-white">{{ $factures->firstWhere('id', $form['facture_id'])?->numero_facture ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Article</label>
                                        <p class="mt-1 text-white">{{ $articles->firstWhere('id', $form['article_id'])?->nom ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Quantité</label>
                                        <p class="mt-1 text-white">{{ number_format($form['quantite'] ?? 0, 0, ',', ' ') }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Prix unitaire</label>
                                        <p class="mt-1 text-white">{{ number_format($form['prix_unitaire'] ?? 0, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400">Montant</label>
                                        <p class="mt-1 text-white">{{ number_format($form['montant'] ?? 0, 0, ',', ' ') }} FCFA</p>
                                    </div>
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
                                <form wire:submit.prevent="save" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Facture</label>
                                        <select
                                            wire:model="form.facture_id"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($factures as $facture)
                                                <option value="{{ $facture->id }}">{{ $facture->numero_facture }} ({{ $facture->client?->nom }})</option>
                                            @endforeach
                                        </select>
                                        @error('form.facture_id') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Article</label>
                                        <select
                                            wire:model="form.article_id"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            required
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($articles as $article)
                                                <option value="{{ $article->id }}">{{ $article->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.article_id') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Quantité</label>
                                            <input
                                                type="number"
                                                wire:model="form.quantite"
                                                step="1"
                                                min="1"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required
                                            />
                                            @error('form.quantite') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Prix unitaire (FCFA)</label>
                                            <input
                                                type="number"
                                                wire:model="form.prix_unitaire"
                                                step="1"
                                                min="1"
                                                class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                required
                                            />
                                            @error('form.prix_unitaire') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Montant (calculé)</label>
                                        <input
                                            type="text"
                                            wire:model="form.montant"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                            readonly
                                        />
                                    </div>

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
        </div>
    </div>
</div>