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
            <h2 class="text-3xl font-bold">Sacs produits finis</h2>
            <p class="text-gray-400 mt-1">{{ $sacs->total() }} sac{{ $sacs->total() > 1 ? 's' : '' }} trouvé{{ $sacs->total() > 1 ? 's' : '' }}</p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Ajouter un sac
        </button>
    </div>

    {{-- Bilans des sacs disponibles --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Par variété --}}
        <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-5 border border-gray-700">
            <h3 class="text-lg font-semibold text-gray-200 mb-3">Sacs par variété</h3>
            <div class="space-y-2 text-sm">
                @foreach ($sacsParVariete as $variete => $total)
                    <div class="flex justify-between">
                        <span class="text-gray-300">{{ $variete ?: 'Non renseigné' }}</span>
                        <span class="font-semibold text-green-400">{{ $total }} sacs</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Par capacité de sac --}}
        <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-5 border border-gray-700">
            <h3 class="text-lg font-semibold text-gray-200 mb-3">Sacs par capacité</h3>
            <div class="space-y-2 text-sm">
                @foreach ($sacsParPoids as $poids => $total)
                    <div class="flex justify-between">
                        <span class="text-gray-300">{{ $poids }} kg</span>
                        <span class="font-semibold text-purple-400">{{ $total }} sacs</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Par type de produit --}}
        <div class="bg-gray-800/60 backdrop-blur-sm rounded-xl p-5 border border-gray-700">
            <h3 class="text-lg font-semibold text-gray-200 mb-3">Sacs par type</h3>
            <div class="space-y-2 text-sm">
                @foreach ($sacsParType as $type => $total)
                    <div class="flex justify-between">
                        <span class="text-gray-300">{{ $type }}</span>
                        <span class="font-semibold text-blue-400">{{ $total }} sacs</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div>
            <label class="block text-sm font-medium text-gray-300">Type sac</label>
            <select
                wire:model.live="filterTypeSac"
                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">Tous</option>
                <option value="riz_blanc">Riz blanc</option>
                <option value="brisures">Brisures</option>
                <option value="rejets">Rejets</option>
                <option value="son">Son</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300">Statut</label>
            <select
                wire:model.live="filterStatut"
                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">Tous</option>
                <option value="disponible">Disponible</option>
                <option value="en_transfert">En transfert</option>
                <option value="transfere">Transféré</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300">Variété</label>
            <select
                wire:model.live="filterVariete"
                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
                <option value="">Tous</option>
                @foreach($varietes as $variete)
                    <option value="{{ $variete->code_variete }}">
                        {{ $variete->code_variete }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button
                wire:click="resetFilters"
                class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
                Réinitialiser
            </button>
        </div>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Rechercher par code sac ou stock..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($sacs->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-2xl font-bold mb-2">Aucun sac trouvé</h3>
            <p class="text-gray-400 mb-6">Créez votre premier sac de produit fini ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter un sac
            </button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Code sac</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Stock</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Type sac</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Poids total (kg)</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Date emballage</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Agent</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Statut</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($sacs as $s)
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4 font-mono">{{ $s->code_sac }}</td>
                                <td class="px-6 py-4">
                                    {{ $s->stockProduitFini?->code_stock ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $s->type_sac === 'riz_blanc' ? 'bg-blue-100 text-blue-800' : ($s->type_sac === 'brisures' ? 'bg-yellow-100 text-yellow-800' : ($s->type_sac === 'son' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $s->type_sac }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-purple-400">
                                    {{ number_format($s->poids_sac_kg * $s->nombre_sacs, 2) }} kg
                                </td>
                                <td class="px-6 py-4">
                                    {{ $s->date_emballage?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $s->agent?->prenom ? $s->agent->prenom . ' ' : '' }}{{ $s->agent?->nom ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $s->statut === 'disponible' ? 'bg-green-100 text-green-800' : ($s->statut === 'en_transfert' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $s->statut }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button
                                        wire:click="show({{ $s->id }})"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit({{ $s->id }})"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="delete({{ $s->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce sac ?"
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
                {{ ($sacs->currentPage() - 1) * $sacs->perPage() + 1 }} - {{ min($sacs->currentPage() * $sacs->perPage(), $sacs->total()) }} sur {{ $sacs->total() }}
            </div>
            <div>{{ $sacs->links() }}</div>
        </div>
    @endif

    {{-- Modal (show / edit) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $viewMode ? 'Détails du sac produit fini' : ($form['id'] ? 'Modifier le sac produit fini' : 'Ajouter un sac produit fini') }}
                </h2>

                {{-- Mode Lecture seule (Show) --}}
                @if($viewMode)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Code sac</label>
                            <p class="mt-1">{{ $form['code_sac'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Stock produit fini</label>
                            <p class="mt-1">{{ $stocks_produits_finis->firstWhere('id', $form['stock_produit_fini_id'])?->code_stock ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Type sac</label>
                            <p class="mt-1">{{ $form['type_sac'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Poids sac (kg)</label>
                            <p class="mt-1">{{ $form['poids_sac_kg'] ? number_format($form['poids_sac_kg'], 2) . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Nombre sacs</label>
                            <p class="mt-1">{{ $form['nombre_sacs'] ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Poids total (kg)</label>
                            <p class="mt-1">{{ $form['poids_sac_kg'] && $form['nombre_sacs'] ? number_format($form['poids_sac_kg'] * $form['nombre_sacs'], 2) . ' kg' : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Date emballage</label>
                            <p class="mt-1">{{ $form['date_emballage'] ? \Carbon\Carbon::parse($form['date_emballage'])->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Agent</label>
                            <p class="mt-1">
                                @php $agent = $agents->firstWhere('id', $form['agent_id']); @endphp
                                {{ $agent ? ($agent->prenom ? $agent->prenom . ' ' : '') . $agent->nom : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Statut</label>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $form['statut'] === 'disponible' ? 'bg-green-100 text-green-800' : ($form['statut'] === 'en_transfert' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $form['statut'] ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Fermer</button>
                    </div>

                {{-- Mode Édition (Edit / Create) --}}
                @else
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Code sac</label>
                                <input type="text" wire:model="form.code_sac" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Type sac</label>
                                <select wire:model.live="form.type_sac" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="riz_blanc">Riz blanc</option>
                                    <option value="brisures">Brisures</option>
                                    <option value="rejets">Rejets</option>
                                    <option value="son">Son</option>
                                </select>
                                @error('form.type_sac') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            {{-- Stock produit fini (filtré dynamiquement) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Stock produit fini</label>
                                <select wire:model.live="form.stock_produit_fini_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionner un stock</option>
                                    @foreach($stocksDisponibles as $stock)
                                        <option value="{{ $stock->id }}">
                                            {{ $stock->code_stock }} ({{ number_format($stock->quantite_kg, 2) }} kg) - 
                                            {{ $stock->varieteRice?->code_variete ?? '' }} [{{ $stock->type_produit }}]
                                        </option>
                                    @endforeach
                                </select>
                                @error('form.stock_produit_fini_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Poids sac (kg)</label>
                                <select wire:model.live="form.poids_sac_kg" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    @foreach($poidsOptions as $poids)
                                        <option value="{{ $poids }}">{{ $poids }} kg</option>
                                    @endforeach
                                </select>
                                @error('form.poids_sac_kg') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Quantité totale (kg)</label>
                                <input type="number" wire:model.live="form.quantite_totale" step="0.01" min="0.1" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('form.quantite_totale') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Nombre de sacs (calculé)</label>
                                <input type="text" value="{{ number_format($nombre_sacs_calcule, 0) }}" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm sm:text-sm" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Date emballage</label>
                                <input type="date" wire:model="form.date_emballage" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('form.date_emballage') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Agent</label>
                                <select wire:model="form.agent_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    @foreach($agents as $a)
                                        <option value="{{ $a->id }}">
                                            {{ $a->prenom ? $a->prenom . ' ' : '' }}{{ $a->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Statut</label>
                                <select wire:model="form.statut" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="disponible">Disponible</option>
                                    <option value="en_transfert">En transfert</option>
                                    <option value="transfere">Transféré</option>
                                </select>
                                @error('form.statut') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Annuler</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ $form['id'] ? 'Mettre à jour' : 'Créer' }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>