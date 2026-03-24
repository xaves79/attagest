<div class="py-6 bg-slate-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-slate-800 border-b border-slate-700">

                <!-- Barre de recherche + bouton Ajouter -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                    <div class="flex-1 max-w-md">
                        <input
                            type="text"
                            wire:model.debounce.500ms="search"
                            placeholder="Rechercher un article..."
                            class="block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Ajouter un article
                    </button>
                </div>

                <!-- Liste des articles -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Type produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-800 divide-y divide-slate-700">
                            @forelse($articles as $a)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $a->nom }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $a->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ number_format($a->prix_unitaire, 0, ',', ' ') }} F CFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $a->stock ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $a->type_produit }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click="edit({{ $a->id }})"
                                            class="text-emerald-400 hover:text-emerald-300 mr-4"
                                        >
                                            Modifier
                                        </button>
                                        <button
											wire:click="delete({{ $a->id }})"
											wire:confirm="Êtes-vous sûr de vouloir supprimer cet article ?"
											class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
											title="Supprimer"
										>
											🗑️
										</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-400">
                                        Aucun article trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>

                <!-- Modal (formulaire) -->
                @if($showModal)
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-75 flex items-center justify-center z-50">
                        <div class="bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full p-6 mx-4 border border-slate-700">
                            <h2 class="text-xl font-semibold text-white mb-4">
                                {{ $form['id'] ? 'Modifier l\'article' : 'Ajouter un article' }}
                            </h2>

                            <form wire:submit.prevent="save">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Nom</label>
                                        <input
                                            type="text"
                                            wire:model="form.nom"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                        @error('form.nom') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Description</label>
                                        <textarea
                                            wire:model="form.description"
                                            rows="3"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Prix unitaire (F CFA)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.prix_unitaire"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                        @error('form.prix_unitaire') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Stock</label>
                                        <input
                                            type="number"
                                            wire:model="form.stock"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Variété (optionnel)</label>
                                        <select
                                            wire:model="form.variete_id"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                            <option value="">Aucune variété</option>
                                            @foreach(\App\Models\VarieteRice::all() as $v)
                                                <option value="{{ $v->id }}">{{ $v->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Type produit</label>
                                        <select
                                            wire:model="form.type_produit"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                            <option value="riz_blanchi">Riz blanchi</option>
                                            <option value="Brisure">Riz brisure</option>
                                            <option value="rejet">Riz rejet</option>
											<option value="son">Son</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Taille du sac</label>
                                        <select
                                            wire:model="form.taille_sac"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                            <option value="1kg">1 kg</option>
                                            <option value="5kg">5 kg</option>
                                            <option value="10kg">10 kg</option>
                                            <option value="25kg">25 kg</option>
                                            <option value="50kg">50 kg</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Prix au kg (F CFA)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.prix_kg"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                        @error('form.prix_kg') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-slate-300">Unité de vente</label>
                                        <select
                                            wire:model="form.unite_vente"
                                            class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        >
                                            <option value="sac">Sac</option>
                                            <option value="kg">Kg</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        wire:click="$set('showModal', false)"
                                        class="px-4 py-2 bg-slate-600 text-slate-200 rounded hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                    >
                                        {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
