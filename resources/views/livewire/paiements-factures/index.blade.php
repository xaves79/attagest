<div>
    @if (session()->has('message'))
        <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">Paiements factures</h2>
            <p class="text-gray-400 mt-1">
                {{ $paiements->total() }} paiement{{ $paiements->total() > 1 ? 's' : '' }} trouvé{{ $paiements->total() > 1 ? 's' : '' }}
            </p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Ajouter un paiement
        </button>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.debounce.500ms="search"
                placeholder="Rechercher par numéro paiement ou facture..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($paiements->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">💳</div>
            <h3 class="text-2xl font-bold mb-2">Aucun paiement trouvé</h3>
            <p class="text-gray-400 mb-6">Créez votre premier paiement ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter un paiement
            </button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Numéro paiement</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Facture</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Montant payé (FCFA)</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Date paiement</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Mode paiement</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Statut</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($paiements as $p)
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4 font-mono">{{ $p->numero_paiement }}</td>
                                <td class="px-6 py-4">
                                    {{ $p->facture?->numero_facture ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-400">
                                    {{ number_format($p->montant_paye, 2, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    {{ $p->date_paiement?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $p->mode_paiement }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $p->statut === 'paye' ? 'bg-green-100 text-green-800' : ($p->statut === 'annule' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $p->statut }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button
                                        wire:click="show({{ $p->id }})"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit({{ $p->id }})"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="delete({{ $p->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce paiement ?"
                                        class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Supprimer"
                                    >
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center text-sm text-gray-400">
            <div>
                {{ ($paiements->currentPage() - 1) * $paiements->perPage() + 1 }} -
                {{ min($paiements->currentPage() * $paiements->perPage(), $paiements->total()) }}
                sur {{ $paiements->total() }}
            </div>
            <div>{{ $paiements->links() }}</div>
        </div>
    @endif

    {{-- Modal (formulaire) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $form['id'] ? 'Modifier le paiement' : 'Ajouter un paiement' }}
                </h2>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Facture</label>
                            <select
                                wire:model="form.facture_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner</option>
                                @foreach($factures as $f)
                                    <option value="{{ $f->id }}">
                                        {{ $f->numero_facture }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.facture_id')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Numéro paiement</label>
                            <input
                                type="text"
                                wire:model="form.numero_paiement"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.numero_paiement')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Montant payé (FCFA)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.montant_paye"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.montant_paye')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Date paiement</label>
                            <input
                                type="date"
                                wire:model="form.date_paiement"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.date_paiement')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Mode paiement</label>
                            <select
                                wire:model="form.mode_paiement"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="espèces">Espèces</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="chèque">Chèque</option>
                                <option value="virement">Virement</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Statut</label>
                            <select
                                wire:model="form.statut"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="paye">Payé</option>
                                <option value="annule">Annulé</option>
                                <option value="reporte">Reporté</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea
                                wire:model="form.description"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                rows="3"
                            ></textarea>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            Annuler
                        </button>
                        @unless($viewMode)
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        @endunless
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
