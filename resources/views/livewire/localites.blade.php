<div class="bg-slate-900 min-h-screen text-slate-100 p-6">
    <div class="max-w-screen-2xl mx-auto">
        {{-- Messages flash --}}
        @if (session()->has('message'))
            <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                {{ session('message') }}
            </div>
        @endif

        {{-- En-tête --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span class="text-3xl">📍</span> Localités
            </h2>
            <button
                wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle localité
            </button>
        </div>

        {{-- Recherche --}}
        <div class="mb-6">
            <div class="relative max-w-md">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par nom ou région..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                />
                <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Tableau --}}
        @if($localites->isEmpty())
            <div class="bg-slate-800 rounded-xl p-12 text-center border-2 border-dashed border-slate-600">
                <div class="text-6xl mb-4">📍</div>
                <h3 class="text-2xl font-bold mb-2">Aucune localité trouvée</h3>
                <p class="text-slate-400 mb-6">Créez votre première localité ou modifiez votre recherche.</p>
                <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold">
                    + Nouvelle localité
                </button>
            </div>
        @else
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b border-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Nom</th>
                                <th class="px-4 py-3 text-left font-semibold">Région</th>
                                <th class="px-4 py-3 text-center font-semibold w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach ($localites as $localite)
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 font-medium">{{ $localite->nom }}</td>
                                    <td class="px-4 py-3">{{ $localite->region }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="show({{ $localite->id }})" class="p-1.5 bg-blue-600/90 hover:bg-blue-500 text-white rounded-md transition" title="Voir">
                                                👁️
                                            </button>
                                            <button wire:click="edit({{ $localite->id }})" class="p-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white rounded-md transition" title="Modifier">
                                                ✏️
                                            </button>
                                            <button wire:click="delete({{ $localite->id }})" wire:confirm="Supprimer cette localité ?" class="p-1.5 bg-red-600/90 hover:bg-red-500 text-white rounded-md transition" title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    {{ ($localites->currentPage() - 1) * $localites->perPage() + 1 }} - {{ min($localites->currentPage() * $localites->perPage(), $localites->total()) }} sur {{ $localites->total() }}
                </div>
                <div>{{ $localites->links() }}</div>
            </div>
        @endif

        {{-- Modal de création / édition / visualisation --}}
        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">
                            {{ $viewMode ? 'Détails de la localité' : ($form['id'] ? 'Modifier la localité' : 'Nouvelle localité') }}
                        </h3>

                        @if($viewMode)
                            {{-- Mode visualisation --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-400">Nom</label>
                                    <p class="mt-1 text-white">{{ $form['nom'] }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-400">Région</label>
                                    <p class="mt-1 text-white">{{ $form['region'] }}</p>
                                </div>
                                <div class="flex justify-end mt-6">
                                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500">
                                        Fermer
                                    </button>
                                </div>
                            </div>
                        @else
                            {{-- Formulaire --}}
                            <form wire:submit.prevent="save" class="space-y-4">
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-slate-300">Nom *</label>
                                    <input type="text" id="nom" wire:model="form.nom" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                    @error('form.nom') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="region" class="block text-sm font-medium text-slate-300">Région *</label>
                                    <input type="text" id="region" wire:model="form.region" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                    @error('form.region') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500 transition">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
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