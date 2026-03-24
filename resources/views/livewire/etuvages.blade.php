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
                            placeholder="Rechercher un étuvage..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Ajouter un étuvage
                    </button>
                </div>

                <!-- Liste des étuvages -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code étuvage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lot de paddy</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code achat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date début</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masse entrée (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($etuvages as $e)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $e->code_etuvage }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $e->stockPaddy?->code_stock ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $e->achatPaddy?->code_lot ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $e->agent?->prenom ? $e->agent->prenom . ' ' : '' }}{{ $e->agent?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $e->date_debut_etuvage?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($e->masse_entree_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $e->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $e->statut }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                        <button
                                            wire:click="edit({{ $e->id }})"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Modifier"
                                        >
                                            <x-heroicon-s-pencil class="h-5 w-5" />
                                        </button>
                                        <button
                                            wire:click="delete({{ $e->id }})"
                                            onclick="return confirm('Supprimer cet étuvage ?')"
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
                                        Aucun étuvage trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $etuvages->links() }}
                </div>

                <!-- Modal (formulaire) -->
                @if($showModal)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4">
                            <h2 class="text-xl font-semibold mb-4">
                                {{ $form['id'] ? 'Modifier l\'étuvage' : 'Ajouter un étuvage' }}
                            </h2>

                            <form wire:submit.prevent="save">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Code étuvage</label>
                                        <input
                                            type="text"
                                            wire:model="form.code_etuvage"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.code_etuvage') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Agent</label>
                                        <select
                                            wire:model="form.agent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($agents as $a)
                                                <option value="{{ $a->id }}">
                                                    {{ $a->prenom ? $a->prenom . ' ' : '' }}{{ $a->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.agent_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Lot de paddy</label>
                                        <select
                                            wire:model="form.stock_paddy_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($stocks_paddy as $sp)
                                                <option value="{{ $sp->id }}">
                                                    {{ $sp->code_stock }} ({{ $sp->achatPaddy?->code_lot ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.stock_paddy_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Masse entrée (kg)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            wire:model="form.masse_entree_kg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.masse_entree_kg') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date début étuvage</label>
                                        <input
                                            type="date"
                                            wire:model="form.date_debut_etuvage"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.date_debut_etuvage') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Température étuvage</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.temperature_etuvage"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Durée (minutes)</label>
                                        <input
                                            type="number"
                                            wire:model="form.duree_etuvage_minutes"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date fin étuvage</label>
                                        <input
                                            type="date"
                                            wire:model="form.date_fin_etuvage"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <select
                                            wire:model="form.statut"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="en_cours">En cours</option>
                                            <option value="termine">Terminé</option>
                                        </select>
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
