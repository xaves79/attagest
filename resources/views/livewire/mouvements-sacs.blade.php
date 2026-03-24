<div>
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
            <h2 class="text-3xl font-bold">Mouvements de sacs</h2>
            <p class="text-gray-400 mt-1">{{ $mouvements->total() }} mouvement{{ $mouvements->total() > 1 ? 's' : '' }}</p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Nouveau mouvement
        </button>
    </div>

    <div class="mb-8">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Rechercher..." class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
    </div>

    @if($mouvements->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 text-center">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-2xl font-bold mb-2">Aucun mouvement trouvé</h3>
            <button wire:click="create" class="bg-blue-600 px-8 py-3 rounded-lg font-semibold">Ajouter un mouvement</button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left">Point de vente</th>
                            <th class="px-6 py-4 text-left">Code sac</th>
                            <th class="px-6 py-4 text-left">Type / Poids</th>
                            <th class="px-6 py-4 text-right">Quantité</th>
                            <th class="px-6 py-4 text-left">Mouvement</th>
                            <th class="px-6 py-4 text-left">Agent</th>
                            <th class="px-6 py-4 text-left">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($mouvements as $m)
                            @php
                                $type = $m->stockSac?->sac?->type_sac;
                                $poids = $m->stockSac?->sac?->poids_sac_kg;
                                $typeLabel = match($type) {
                                    'riz_blanc' => 'Riz blanc',
                                    'brisures'  => 'Brisures',
                                    'rejets'    => 'Rejets',
                                    'son'       => 'Son',
                                    default     => $type,
                                };
                            @endphp
                            <tr class="hover:bg-gray-800/50">
                                <td class="px-6 py-4">{{ $m->stockSac?->pointVente?->nom ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $m->stockSac?->sac?->code_sac ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $typeLabel }} ({{ $poids }} kg)</td>
                                <td class="px-6 py-4 text-right">{{ $m->quantite }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->type_mouvement == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $m->type_mouvement }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $m->agent?->nom ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $m->date_mouvement?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4 text-center flex space-x-1">
									<button wire:click="show({{ $m->id }})" class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs rounded-lg" title="Voir">👁️</button>
									<button wire:click="delete({{ $m->id }})" wire:confirm="Supprimer ce mouvement ? Cette action ajustera les stocks." class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs rounded-lg" title="Supprimer">🗑️</button>
								</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $mouvements->links() }}
        </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-2xl w-full p-6 mx-4 text-white max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $viewMode ? 'Détails du mouvement' : 'Nouveau mouvement' }}
                </h2>

                @if($viewMode)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-400">Point de vente</label>
                            <p>{{ isset($form['point_vente_id']) ? ($pointsVente->firstWhere('id', $form['point_vente_id'])?->nom ?? '-') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Sac</label>
                            <p>{{ isset($form['sac_id']) ? ($sacs->firstWhere('id', $form['sac_id'])?->code_sac ?? '-') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Quantité</label>
                            <p>{{ $form['quantite'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Type mouvement</label>
                            <p>{{ $form['type_mouvement'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Agent</label>
                            <p>{{ isset($form['agent_id']) ? ($agents->firstWhere('id', $form['agent_id'])?->nom ?? '-') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Notes</label>
                            <p>{{ $form['notes'] ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-white rounded">Fermer</button>
                    </div>
                @else
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Point de vente</label>
                                <select wire:model.live="form.point_vente_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($pointsVente as $pv)
                                        <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                                    @endforeach
                                </select>
                                @error('form.point_vente_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Type mouvement</label>
                                <select wire:model.live="form.type_mouvement" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="entree">Entrée</option>
                                    <option value="sortie">Sortie</option>
                                </select>
                            </div>

                            @if($form['type_mouvement'] === 'entree')
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Sac</label>
                                    <select wire:model="form.sac_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <option value="">Sélectionner un sac</option>
                                        @foreach($stocksDisponibles as $item)
                                            <option value="{{ $item->id }}">{{ $item->display }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.sac_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Sac (stock disponible)</label>
                                    <select wire:model="form.sac_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($stocksDisponibles as $stock)
                                            <option value="{{ $stock->sac_id }}">{{ $stock->display }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.sac_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Quantité</label>
                                <input type="number" min="1" wire:model="form.quantite" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                @error('form.quantite') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Agent</label>
                                <select wire:model="form.agent_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    @foreach($agents as $a)
                                        <option value="{{ $a->id }}">{{ $a->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Notes</label>
                                <textarea wire:model="form.notes" rows="2" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-white rounded">Annuler</button>
                            <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 text-white rounded">
                                <span wire:loading.remove>Créer</span>
                                <span wire:loading>Création en cours...</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>