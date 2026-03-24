<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <!-- Barre de recherche + bouton Ajouter -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                    <div class="flex-1 max-w-md">
                        <input
                            type="text"
                            wire:model.debounce.500ms="search"
                            placeholder="Rechercher un lot..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Ajouter un lot
                    </button>
                </div>

                <!-- Liste des lots -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code lot</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étuvage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masse entrée (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masse après (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perte (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rendement (%)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lots as $l)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $l->code_lot }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $l->etuvage?->code_etuvage ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $l->varieteRice?->nom ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($l->quantite_entree_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ $l->masse_apres_kg ? number_format($l->masse_apres_kg, 1, ',', ' ') : '-' }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($l->perte_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($l->rendement_pourcentage, 1, ',', ' ') }} %
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                        <button
                                            wire:click="edit({{ $l->id }})"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Modifier"
                                        >
                                            <x-heroicon-s-pencil class="h-5 w-5" />
                                        </button>
                                        <button
                                            wire:click="delete({{ $l->id }})"
                                            onclick="return confirm('Supprimer ce lot ?')"
                                            class="text-red-600 hover:text-red-900"
                                            title="Supprimer"
                                        >
                                            <x-heroicon-s-trash class="h-5 w-5" />
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Aucun lot trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $lots->links() }}
                </div>

                <!-- Modal (formulaire) -->
                @if($showModal)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4">
                            <h2 class="text-xl font-semibold mb-4">
                                {{ $form['id'] ? 'Modifier le lot' : 'Ajouter un lot' }}
                            </h2>

                            <form wire:submit.prevent="save">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Code lot</label>
                                        <input
                                            type="text"
                                            wire:model="form.code_lot"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.code_lot') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Étuvage</label>
                                        <select
                                            wire:model="form.provenance_etuvage_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($etuvages as $e)
                                                <option value="{{ $e->id }}">
                                                    {{ $e->code_etuvage }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.provenance_etuvage_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Variété</label>
                                        <select
                                            wire:model="form.variete_rice_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($varietes_rice as $v)
                                                <option value="{{ $v->id }}">
                                                    {{ $v->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.variete_rice_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masse entrée (kg)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            wire:model="form.quantite_entree_kg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.quantite_entree_kg') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masse après (kg)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            wire:model="form.masse_apres_kg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Stock restant (kg)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            wire:model="form.quantite_restante_kg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.quantite_restante_kg') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        wire:click="$set('showModal', false)"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
