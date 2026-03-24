<div class="min-h-screen bg-slate-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        {{-- Messages flash --}}
        @if (session()->has('message'))
            <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold">Postes</h2>
            <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200">
                ➕ Nouveau poste
            </button>
        </div>

        {{-- Recherche --}}
        <div class="mb-6">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher un poste..."
                class="w-full px-4 py-2 bg-slate-800 border border-slate-600 rounded-lg text-white"
            />
        </div>

        {{-- Tableau --}}
        @if($postes->isEmpty())
            <div class="bg-slate-800 rounded-xl p-12 text-center border-2 border-dashed border-slate-600">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-2xl font-bold mb-2">Aucun poste trouvé</h3>
                <p class="text-slate-400 mb-6">Créez votre premier poste ou modifiez votre recherche.</p>
                <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold">
                    + Nouveau poste
                </button>
            </div>
        @else
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-slate-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b-2 border-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold">Libellé</th>
                                <th class="px-6 py-4 text-left font-semibold">Description</th>
                                <th class="px-6 py-4 text-center font-semibold">Actif</th>
                                <th class="px-6 py-4 text-center font-semibold w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach ($postes as $poste)
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4 font-medium">{{ $poste->libelle }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $poste->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($poste->actif)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-900/50 text-green-300 rounded-full">Oui</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-900/50 text-red-300 rounded-full">Non</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <button wire:click="edit({{ $poste->id }})" class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md transition-all" title="Modifier">
                                            ✏️
                                        </button>
                                        <button wire:click="delete({{ $poste->id }})" wire:confirm="Supprimer ce poste ?" class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md transition-all" title="Supprimer">
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-8 flex justify-between items-center text-sm text-slate-400">
                <div>
                    {{ ($postes->currentPage() - 1) * $postes->perPage() + 1 }} - {{ min($postes->currentPage() * $postes->perPage(), $postes->total()) }} sur {{ $postes->total() }}
                </div>
                <div>{{ $postes->links() }}</div>
            </div>
        @endif

        {{-- Modal --}}
        @if($showModal)
            <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
                <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-2xl w-full p-6 mx-4 text-white">
                    <h3 class="text-xl font-semibold mb-4">
                        {{ $form['id'] ? 'Modifier le poste' : 'Nouveau poste' }}
                    </h3>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Libellé *</label>
                            <input type="text" wire:model="form.libelle" required class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                            @error('form.libelle') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Description</label>
                            <textarea wire:model="form.description" rows="3" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500"></textarea>
                            @error('form.description') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="form.actif" id="actif" class="rounded border-slate-600 bg-slate-700 text-blue-600 focus:ring-blue-500">
                            <label for="actif" class="ml-2 text-sm text-slate-300">Actif</label>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-500 transition">
                                Annuler
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>