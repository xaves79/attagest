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
            <h2 class="text-3xl font-bold">Mouvements des réservoirs</h2>
            <p class="text-gray-400 mt-1">{{ $mouvements->total() }} mouvement{{ $mouvements->total() > 1 ? 's' : '' }}</p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Nouveau mouvement
        </button>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Rechercher par réservoir..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($mouvements->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 text-center border-2 border-dashed border-gray-600">
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
                            <th class="px-6 py-4 text-left">Réservoir</th>
                            <th class="px-6 py-4 text-left">Type</th>
                            <th class="px-6 py-4 text-right">Quantité (kg)</th>
                            <th class="px-6 py-4 text-left">Source / Destination</th>
                            <th class="px-6 py-4 text-left">Agent</th>
                            <th class="px-6 py-4 text-left">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($mouvements as $m)
                            <tr class="hover:bg-gray-800/50">
                                <td class="px-6 py-4">{{ $m->reservoir?->nom_reservoir ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $m->type_mouvement == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $m->type_mouvement }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">{{ number_format($m->quantite_kg, 2, ',', ' ') }} kg</td>
                                <td class="px-6 py-4">
                                    @if($m->stock)
                                        Stock: {{ $m->stock->code_stock }}
                                    @elseif($m->decorticage)
                                        Décorticage: {{ $m->decorticage->code_decorticage }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $m->agent?->nom ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $m->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button wire:click="show({{ $m->id }})" class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs rounded-lg" title="Voir">👁️</button>
                                    <button wire:click="edit({{ $m->id }})" class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs rounded-lg" title="Modifier">✏️</button>
                                    <button wire:click="delete({{ $m->id }})" wire:confirm="Supprimer ?" class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs rounded-lg" title="Supprimer">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center text-sm text-gray-400">
            <div>
                {{ ($mouvements->currentPage() - 1) * $mouvements->perPage() + 1 }} - {{ min($mouvements->currentPage() * $mouvements->perPage(), $mouvements->total()) }} sur {{ $mouvements->total() }}
            </div>
            <div>{{ $mouvements->links() }}</div>
        </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-2xl w-full p-6 mx-4 text-white max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $viewMode ? 'Détails' : ($form['id'] ? 'Modifier' : 'Nouveau mouvement') }}
                </h2>

                @if($viewMode)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-400">Réservoir</label>
                            <p>{{ $reservoirs->firstWhere('id', $form['reservoir_id'])?->nom_reservoir ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Type mouvement</label>
                            <p>{{ $form['type_mouvement'] }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Quantité (kg)</label>
                            <p>{{ number_format($form['quantite_kg'], 2, ',', ' ') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Source</label>
                            <p>
                                @if($form['stock_id'])
                                    Stock ID: {{ $form['stock_id'] }}
                                @elseif($form['decorticage_id'])
                                    Décorticage ID: {{ $form['decorticage_id'] }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Agent</label>
                            <p>{{ $agents->firstWhere('id', $form['agent_id'])?->nom ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400">Date création</label>
                            <p>{{ $form['created_at'] ? \Carbon\Carbon::parse($form['created_at'])->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-white rounded">Fermer</button>
                    </div>
                @else
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Réservoir</label>
                                <select wire:model="form.reservoir_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($reservoirs as $r)
                                        <option value="{{ $r->id }}">{{ $r->nom_reservoir }} ({{ $r->pointVente?->nom ?? 'Sans point' }})</option>
                                    @endforeach
                                </select>
                                @error('form.reservoir_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Type mouvement</label>
                                <select wire:model.live="form.type_mouvement" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="entree">Entrée</option>
                                    <option value="sortie">Sortie</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Quantité (kg)</label>
                                <input type="number" step="0.01" wire:model="form.quantite_kg" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                @error('form.quantite_kg') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            @if($form['type_mouvement'] === 'entree')
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Source (Stock)</label>
                                    <select wire:model="form.stock_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Sélectionner un stock</option>
                                        @foreach($stocksDisponibles as $stock)
                                            <option value="{{ $stock->id }}">{{ $stock->code_stock }} ({{ number_format($stock->quantite_kg, 2) }} kg) - {{ $stock->type_produit }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.stock_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Ou Décorticage</label>
                                    <select wire:model="form.decorticage_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Sélectionner un décorticage</option>
                                        @foreach(\App\Models\Decorticage::where('statut', 'termine')->get() as $d)
                                            <option value="{{ $d->id }}">{{ $d->code_decorticage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Agent</label>
                                <select wire:model="form.agent_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    @foreach($agents as $a)
                                        <option value="{{ $a->id }}">{{ $a->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-white rounded">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ $form['id'] ? 'Mettre à jour' : 'Créer' }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>