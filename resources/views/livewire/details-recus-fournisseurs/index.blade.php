<div class="bg-slate-900 min-h-screen text-slate-100">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold text-slate-100">Détails des reçus fournisseurs</h2>
            <p class="mt-1 text-sm text-slate-400">
                Gestion des lignes de détails pour chaque reçu fournisseur.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec bouton Ajouter -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <span class="text-sm text-slate-400">
                    {{ $details->count() }} détail{{ $details->count() > 1 ? 's' : '' }} trouvé{{ $details->count() > 1 ? 's' : '' }}
                </span>
            </div>
            <div>
                <button
                    wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-slate-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition ease-in-out duration-150"
                >
                    Ajouter un détail
                </button>
            </div>
        </div>

        <!-- Tableau -->
        <div class="bg-slate-800 shadow overflow-hidden rounded-lg border border-slate-700">
            <table class="min-w-full divide-y divide-slate-700">
                <thead class="bg-slate-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Reçu
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Variété
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Quantité
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse ($details as $detail)
                        <tr>
							<td class="px-6 py-4 text-sm text-slate-200">
								{{ $detail->id }}
							</td>
							<td class="px-6 py-4 text-sm text-slate-200">
								{{ $detail->recu?->numero_recu ?? '-' }}
							</td>
							<td class="px-6 py-4 text-sm text-slate-200">
								{{ $detail->article?->libelle ?? '-' }}
							</td>
							<td class="px-6 py-4 text-sm text-slate-200">
								{{ $detail->variete?->nom ?? '-' }}
							</td>
							<td class="px-6 py-4 text-sm text-slate-200">
								{{ number_format($detail->quantite, 2, ',', ' ') }}
							</td>
							<td class="px-6 py-4 text-sm text-slate-200 space-x-2">
								<button
									wire:click="edit({{ $detail->id }})"
									class="text-blue-400 hover:text-blue-300 text-sm"
								>
									Modifier
								</button>
								<button
									wire:click="delete({{ $detail->id }})"
									wire:confirm="Êtes-vous sûr de vouloir supprimer ce mouvement ?"
									class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
									title="Supprimer"
								>
									🗑️
								</button>
							</td>
						</tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-slate-400 text-center">
                                Aucun détail trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        @if ($showModal)
            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700">
                        <div class="bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-slate-100" id="modal-title">
                                {{ $isEditing ? 'Modifier détail' : 'Nouveau détail' }}
                            </h3>

                            <!-- Formulaire Livewire -->
                            <form wire:submit.prevent="save" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Reçu</label>
                                    <select
                                        wire:model="form.recu_id"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        {{ $viewMode ? 'disabled' : '' }}
                                    >
                                        <option value="">Sélectionner un reçu</option>
                                        @foreach ($recus as $recu)
                                            <option value="{{ $recu->id }}">
                                                {{ $recu->numero_recu }} - {{ $recu->fournisseur?->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('form.recu_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Variété</label>
                                    <select
                                        wire:model="form.variete_rice_id"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        {{ $viewMode ? 'disabled' : '' }}
                                    >
                                        <option value="">Sélectionner une variété</option>
                                        @foreach ($varietes as $variete)
                                            <option value="{{ $variete->id }}">{{ $variete->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.variete_rice_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Description</label>
                                    <input
                                        type="text"
                                        wire:model="form.description"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        {{ $viewMode ? 'disabled' : '' }}
                                    >
                                    @error('form.description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Quantité (kg)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        wire:model="form.quantite"
                                        wire:change="updatedFormQuantite"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        {{ $viewMode ? 'disabled' : '' }}
                                    >
                                    @error('form.quantite') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Prix unitaire</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        wire:model="form.prix_unitaire"
                                        wire:change="updatedFormPrixUnitaire"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        {{ $viewMode ? 'disabled' : '' }}
                                    >
                                    @error('form.prix_unitaire') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-300">Sous‑total</label>
                                    <input
                                        type="number"
                                        step="1"
                                        wire:model="form.sous_total"
                                        class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-slate-100 shadow-sm focus:border-slate-500 focus:ring-slate-500"
                                        readonly
                                    >
                                </div>
                            </form>
                        </div>

                        <div class="bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            @if (! $viewMode)
                                <button
                                    wire:click="save"
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-slate-600 text-base font-medium text-white hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Enregistrer
                                </button>
                            @endif
                            <button
                                wire:click="closeModal"
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-500 shadow-sm px-4 py-2 bg-slate-700 text-base font-medium text-slate-100 hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ $viewMode ? 'Fermer' : 'Annuler' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
