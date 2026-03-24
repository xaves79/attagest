<div>
    @if (session()->has('message'))
        <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">Comptes</h2>
            <p class="text-gray-400 mt-1">{{ $comptes->total() }} compte{{ $comptes->total() > 1 ? 's' : '' }} trouvé{{ $comptes->total() > 1 ? 's' : '' }}</p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Ajouter un compte
        </button>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.debounce.500ms="search"
                placeholder="Rechercher par code compte ou libellé..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($comptes->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-2xl font-bold mb-2">Aucun compte trouvé</h3>
            <p class="text-gray-400 mb-6">Créez votre premier compte ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter un compte
            </button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Code compte</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Libellé</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Type compte</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Solde débit (FCFA)</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Solde crédit (FCFA)</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($comptes as $c)
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4 font-mono">{{ $c->code_compte }}</td>
                                <td class="px-6 py-4">
                                    {{ $c->libelle }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $c->type_compte }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-400">
                                    {{ number_format($c->solde_debit, 2, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-red-400">
                                    {{ number_format($c->solde_credit, 2, ',', ' ') }} FCFA
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button
                                        wire:click="show({{ $c->id }})"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit({{ $c->id }})"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
										wire:click="delete({{ $c->id }})"
										wire:confirm="Êtes-vous sûr de vouloir supprimer ce compte ?"
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
                {{ ($comptes->currentPage() - 1) * $comptes->perPage() + 1 }} - {{ min($comptes->currentPage() * $comptes->perPage(), $comptes->total()) }} sur {{ $comptes->total() }}
            </div>
            <div>{{ $comptes->links() }}</div>
        </div>
    @endif

    {{-- Modal (formulaire) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $form['id'] ? 'Modifier le compte' : 'Ajouter un compte' }}
                </h2>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Code compte</label>
                            <input
                                type="text"
                                wire:model="form.code_compte"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.code_compte') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Libellé</label>
                            <input
                                type="text"
                                wire:model="form.libelle"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.libelle') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Type compte</label>
                            <select
                                wire:model="form.type_compte"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="actif">Actif</option>
                                <option value="passif">Passif</option>
                                <option value="charges">Charges</option>
                                <option value="produits">Produits</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Solde débit (FCFA)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.solde_debit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Solde crédit (FCFA)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.solde_credit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
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
