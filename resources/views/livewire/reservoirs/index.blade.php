<div class="bg-slate-900 min-h-screen text-slate-100 p-6">
    <div class="max-w-screen-2xl mx-auto">
        @if (session()->has('message'))
            <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span class="text-3xl">💧</span> Réservoirs
            </h2>
            <button
                wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau réservoir
            </button>
        </div>

        <div class="mb-6">
            <div class="relative max-w-md">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par nom ou point de vente..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                />
                <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        @if($reservoirs->isEmpty())
            <div class="bg-slate-800 rounded-xl p-12 text-center border-2 border-dashed border-slate-600">
                <div class="text-6xl mb-4">💧</div>
                <h3 class="text-2xl font-bold mb-2">Aucun réservoir trouvé</h3>
                <p class="text-slate-400 mb-6">Créez votre premier réservoir ou modifiez votre recherche.</p>
                <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold">
                    + Nouveau réservoir
                </button>
            </div>
        @else
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b border-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left">Nom</th>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-right">Capacité max (kg)</th>
                                <th class="px-4 py-3 text-right">Quantité actuelle (kg)</th>
                                <th class="px-4 py-3 text-left">Point de vente</th>
                                <th class="px-4 py-3 text-center w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach ($reservoirs as $r)
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 font-medium">{{ $r->nom_reservoir }}</td>
                                    <td class="px-4 py-3">{{ $r->type_produit }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($r->capacite_max_kg, 2, ',', ' ') }}</td>
                                    <td class="px-4 py-3 text-right">{{ number_format($r->quantite_actuelle_kg, 2, ',', ' ') }}</td>
                                    <td class="px-4 py-3">{{ $r->pointVente?->nom ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="show({{ $r->id }})" class="p-1.5 bg-blue-600/90 hover:bg-blue-500 text-white rounded-md transition" title="Voir">👁️</button>
                                            <button wire:click="edit({{ $r->id }})" class="p-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white rounded-md transition" title="Modifier">✏️</button>
                                            <button wire:click="delete({{ $r->id }})" wire:confirm="Supprimer ce réservoir ?" class="p-1.5 bg-red-600/90 hover:bg-red-500 text-white rounded-md transition" title="Supprimer">🗑️</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    {{ ($reservoirs->currentPage() - 1) * $reservoirs->perPage() + 1 }} - {{ min($reservoirs->currentPage() * $reservoirs->perPage(), $reservoirs->total()) }} sur {{ $reservoirs->total() }}
                </div>
                <div>{{ $reservoirs->links() }}</div>
            </div>
        @endif

        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">
                            {{ $viewMode ? 'Détails du réservoir' : ($form['id'] ? 'Modifier le réservoir' : 'Nouveau réservoir') }}
                        </h3>

                        @if($viewMode)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><label class="block text-xs text-slate-400">Nom</label><p class="text-white">{{ $form['nom_reservoir'] }}</p></div>
                                <div><label class="block text-xs text-slate-400">Type</label><p class="text-white">{{ $form['type_produit'] }}</p></div>
                                <div><label class="block text-xs text-slate-400">Capacité max</label><p class="text-white">{{ number_format($form['capacite_max_kg'], 2, ',', ' ') }} kg</p></div>
                                <div><label class="block text-xs text-slate-400">Quantité actuelle</label><p class="text-white">{{ number_format($form['quantite_actuelle_kg'], 2, ',', ' ') }} kg</p></div>
                                <div><label class="block text-xs text-slate-400">Point de vente</label><p class="text-white">{{ $pointsVente->firstWhere('id', $form['point_vente_id'])?->nom ?? '-' }}</p></div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500">Fermer</button>
                            </div>
                        @else
                            <form wire:submit.prevent="save" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Nom *</label>
                                        <input type="text" wire:model="form.nom_reservoir" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('form.nom_reservoir') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Type *</label>
                                        <select wire:model="form.type_produit" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="riz_blanc">Riz blanc</option>
                                            <option value="son">Son</option>
                                            <option value="brisures">Brisures</option>
                                            <option value="rejet">Rejet</option>
                                        </select>
                                        @error('form.type_produit') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Capacité max (kg) *</label>
                                        <input type="text" wire:model="form.capacite_max_kg" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('form.capacite_max_kg') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Quantité actuelle (kg)</label>
                                        <input type="text" wire:model="form.quantite_actuelle_kg" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        @error('form.quantite_actuelle_kg') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Point de vente</label>
                                        <select wire:model="form.point_vente_id" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">Sélectionner</option>
                                            @foreach($pointsVente as $pv)
                                                <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.point_vente_id') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500 transition">Annuler</button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">{{ $form['id'] ? 'Mettre à jour' : 'Créer' }}</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>