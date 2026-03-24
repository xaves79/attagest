<div class="min-h-screen bg-gray-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        {{-- Messages flash --}}
        @if (session()->has('message'))
            <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-800 border border-red-600 text-red-200 px-4 py-3 rounded mb-6 animate-pulse">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold">Lots riz étuvé</h2>
                <p class="text-gray-400 mt-1">
                    {{ $lots->total() }} lot{{ $lots->total() > 1 ? 's' : '' }}
                    trouvé{{ $lots->total() > 1 ? 's' : '' }}
                </p>
            </div>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
            >
                ➕ Ajouter un lot riz étuvé
            </button>
        </div>

        {{-- Recherche --}}
        <div class="mb-8">
            <div class="relative max-w-md">
                <input
                    type="text"
                    wire:model.debounce.500ms="search"
                    placeholder="Rechercher par code lot, étuvage ou variété..."
                    class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
                />
                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        @if($lots->isEmpty())
            <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-2xl font-bold mb-2">Aucun lot trouvé</h3>
                <p class="text-gray-400 mb-6">Créez votre premier lot riz étuvé ou vérifiez vos critères de recherche.</p>
                <button
                    wire:click="create"
                    class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
                >
                    Ajouter un lot riz étuvé
                </button>
            </div>
        @else
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200">Code lot</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Étuvage</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Variété</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Entrée (kg)</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Restant (kg)</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Masse après (kg)</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Perte (kg)</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Rendement (%)</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($lots as $lot)
                                <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                    <td class="px-6 py-4 font-mono bg-gray-900/50 rounded-l-lg group-hover:bg-green-900/30">
                                        {{ $lot->code_lot }}
                                    </td>
                                    <td class="px-6 py-4 max-w-[180px] truncate">
                                        {{ $lot->etuvage?->code_etuvage ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 max-w-[180px] truncate">
                                        {{ $lot->variete?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-green-400">
                                        {{ number_format($lot->quantite_entree_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-{{ $lot->quantite_restante_kg < ($lot->quantite_entree_kg * 0.2) ? 'red' : 'yellow' }}-400">
                                            {{ number_format($lot->quantite_restante_kg, 1, ',', ' ') }} kg
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-blue-400">
                                            {{ $lot->masse_apres_kg ? number_format($lot->masse_apres_kg, 1, ',', ' ') . ' kg' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-red-400">
                                            {{ $lot->perte_kg ? number_format($lot->perte_kg, 1, ',', ' ') . ' kg' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-green-400">
                                            {{ $lot->rendement_pourcentage ? number_format($lot->rendement_pourcentage, 2, ',', ' ') . '%' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center space-x-1">
                                        <button
                                            wire:click="show({{ $lot->id }})"
                                            class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Voir les détails"
                                        >
                                            👁️
                                        </button>
                                        <button
                                            wire:click="edit({{ $lot->id }})"
                                            class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Modifier"
                                        >
                                            ✏️
                                        </button>
                                        <button
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce mouvement ?"
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
                    {{ ($lots->currentPage() - 1) * $lots->perPage() + 1 }} -
                    {{ min($lots->currentPage() * $lots->perPage(), $lots->total()) }} sur {{ $lots->total() }}
                </div>
                <div>{{ $lots->links() }}</div>
            </div>
        @endif

        {{-- Modal SHOW (lecture seule) --}}
        @if($showModal && $viewMode)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 backdrop-blur-sm" wire:click="$set('showModal', false)">
                <div class="bg-gray-800/95 rounded-2xl p-8 shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-gray-700" wire:click.stop>
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-bold text-green-400 flex items-center">
                            📦 Détails lot riz étuvé
                            <span class="ml-3 bg-green-900/50 px-4 py-1 rounded-full text-sm font-semibold">{{ $form['code_lot'] }}</span>
                        </h2>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-white text-3xl p-2 hover:bg-gray-700 rounded-xl transition-all">
                            ✕
                        </button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-4 text-gray-200">📋 Informations générales</h3>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div><span class="text-gray-400">Code lot :</span> <span class="font-mono font-bold text-green-400">{{ $form['code_lot'] }}</span></div>
                                    <div><span class="text-gray-400">Étuvage :</span> <span class="font-semibold">{{ $etuvages->find($form['provenance_etuvage_id'])?->code_etuvage ?? '-' }}</span></div>
                                    <div><span class="text-gray-400">Variété :</span> <span class="font-semibold">{{ $varietes->find($form['variete_rice_id'])?->nom ?? '-' }}</span></div>
                                </div>
                            </div>

                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-4 text-gray-200">📊 Quantités</h3>
                                <div class="space-y-3">
                                    <div><span class="text-gray-400 block mb-1">Entrée (kg) :</span>
                                        <span class="font-bold text-green-400">{{ number_format($form['quantite_entree_kg'], 1, ',', ' ') }} kg</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Restant (kg) :</span>
                                        <span class="font-bold text-yellow-400">{{ number_format($form['quantite_restante_kg'], 1, ',', ' ') }} kg</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Masse après (kg) :</span>
                                        <span class="font-bold text-blue-400">
                                            {{ $form['masse_apres_kg'] ? number_format($form['masse_apres_kg'], 1, ',', ' ') . ' kg' : '-' }}
                                        </span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Perte (kg) :</span>
                                        <span class="font-bold text-red-400">
                                            {{ $form['perte_kg'] ? number_format($form['perte_kg'], 1, ',', ' ') . ' kg' : '-' }}
                                        </span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Rendement (%) :</span>
                                        <span class="font-bold text-green-400">
                                            {{ $form['rendement_pourcentage'] ? number_format($form['rendement_pourcentage'], 2, ',', ' ') . '%' : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-4 text-gray-200">📅 Dates</h3>
                                <div class="space-y-3">
                                    <div><span class="text-gray-400 block mb-1">Date production :</span>
                                        <span class="font-semibold">
                                            {{ $form['date_production'] ? \Carbon\Carbon::parse($form['date_production'])->format('d/m/Y H:i') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-10 pt-8 border-t-2 border-gray-700">
                        <button
                            wire:click="edit({{ $form['id'] }})"
                            class="bg-yellow-600 hover:bg-yellow-500 px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-white"
                        >
                            ✏️ Modifier ce lot
                        </button>
                        <button
                            wire:click="$set('showModal', false); $set('viewMode', false)"
                            class="bg-gray-600 hover:bg-gray-500 px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-white"
                        >
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal CREATE/EDIT --}}
        @if($showModal && !$viewMode)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 backdrop-blur-sm" wire:click="$set('showModal', false)">
                <div class="bg-gray-800/95 rounded-2xl p-8 shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-gray-700" wire:click.stop>
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-bold {{ $form['id'] ? 'text-yellow-400' : 'text-blue-400' }}">
                            {{ $form['id'] ? '✏️ Modifier' : '📦 Créer' }} lot riz étuvé
                        </h2>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-white text-3xl p-2 hover:bg-gray-700 rounded-xl transition-all">
                            ✕
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-6">
                        @error('form.*')
                            <div class="bg-red-900/70 border-2 border-red-500/50 p-4 rounded-xl animate-pulse">
                                <p class="text-red-200 font-semibold">{{ $message }}</p>
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Code lot <span class="text-red-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="form.code_lot"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Étuvage (provenance) <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model="form.provenance_etuvage_id"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                >
                                    <option value="">Sélectionner un étuvage</option>
                                    @foreach ($etuvages as $etuvage)
                                        <option value="{{ $etuvage->id }}">
                                            {{ $etuvage->code_etuvage }} ({{ number_format($etuvage->masse_sortie_kg, 1, ',', ' ') }} kg)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Variété de riz
                                </label>
                                <select
                                    wire:model="form.variete_rice_id"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                >
                                    <option value="">Sélectionner une variété</option>
                                    @foreach ($varietes as $variete)
                                        <option value="{{ $variete->id }}">{{ $variete->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Quantité entrée (kg) <span class="text-red-400">*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    wire:model="form.quantite_entree_kg"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white text-2xl font-bold focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Quantité restante (kg)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    wire:model="form.quantite_restante_kg"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Masse après (kg)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    wire:model="form.masse_apres_kg"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Date production
                                </label>
                                <input
                                    type="datetime-local"
                                    wire:model="form.date_production"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" wire:click="$set('showModal', false)" class="px-8 py-3 bg-gray-600 hover:bg-gray-500 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-white">
                                Annuler
                            </button>
                            <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-500 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all text-white">
                                {{ $form['id'] ? '✏️ Mettre à jour' : '💾 Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
