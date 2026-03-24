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
            <h2 class="text-3xl font-bold">Décorticages</h2>
            <p class="text-gray-400 mt-1">
                {{ $decorticages->total() }} décorticage{{ $decorticages->total() > 1 ? 's' : '' }}
                trouvé{{ $decorticages->total() > 1 ? 's' : '' }}
            </p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Ajouter un décorticage
        </button>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Rechercher par code, lot, étuvage ou agent..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($decorticages->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">🌾</div>
            <h3 class="text-2xl font-bold mb-2">Aucun décorticage trouvé</h3>
            <p class="text-gray-400 mb-6">Créez votre premier décorticage ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter un décorticage
            </button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Code</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Lot riz étuvé</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Agent</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Entrée (kg)</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Riz blanc (kg)</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Rejet (kg)</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Rendement (%)</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Date début</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Statut</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($decorticages as $d)
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4 font-mono">
                                    {{ $d->code_decorticage }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $d->lotRizEtuve?->code_lot ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $d->agent?->prenom ? $d->agent->prenom . ' ' : '' }}{{ $d->agent?->nom ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-400">
                                    {{ number_format($d->quantite_paddy_entree_kg, 2, ',', ' ') }} kg
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-blue-400">
                                    {{ $d->quantite_riz_blanc_kg ? number_format($d->quantite_riz_blanc_kg, 2, ',', ' ') . ' kg' : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-red-400">
                                    {{ $d->quantite_rejet_kg ? number_format($d->quantite_rejet_kg, 2, ',', ' ') . ' kg' : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-purple-400">
                                    {{ $d->taux_rendement ? number_format(min($d->taux_rendement, 100), 1, ',', ' ') . ' %' : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $d->date_debut_decorticage?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $d->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $d->statut }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    @if($d->statut === 'en_cours')
                                        <button
                                            wire:click="terminer({{ $d->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir terminer ce décorticage ? Les stocks de produits finis seront créés."
                                            class="px-3 py-1.5 bg-green-600/90 hover:bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Terminer le décorticage et créer les stocks"
                                        >
                                            ✅ Terminer
                                        </button>
                                    @endif
                                    <button
                                        wire:click="show({{ $d->id }})"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir les détails"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit({{ $d->id }})"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="delete({{ $d->id }})"
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
                {{ ($decorticages->currentPage() - 1) * $decorticages->perPage() + 1 }} -
                {{ min($decorticages->currentPage() * $decorticages->perPage(), $decorticages->total()) }} sur {{ $decorticages->total() }}
            </div>
            <div>{{ $decorticages->links() }}</div>
        </div>
    @endif

    {{-- Modal (formulaire) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $form['id'] ? 'Modifier le décorticage #' . $form['id'] : 'Ajouter un décorticage' }}
                </h2>

                <form wire:submit.prevent="save">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-800/70 border border-red-600 text-red-200 px-4 py-3 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Champ caché pour l'ID -->
                    <input type="hidden" wire:model="form.id">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Agent -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Agent</label>
                            <select
                                wire:model="form.agent_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner l’agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->prenom }} {{ $agent->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.agent_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Code décorticage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Code décorticage</label>
                            <input
                                type="text"
                                wire:model="form.code_decorticage"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                            @error('form.code_decorticage') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lot riz étuvé -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">
                                Lot riz étuvé
                                ({{ $lots_riz_etuve->find($form['lot_riz_etuve_id'])?->quantite_restante_kg ?? 0 }} kg en stock)
                            </label>
                            <select
                                wire:model.live="form.lot_riz_etuve_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner</option>
                                @foreach($lots_riz_etuve as $l)
                                    <option value="{{ $l->id }}">
                                        {{ $l->code_lot }} ({{ number_format($l->quantite_restante_kg, 1, ',', ' ') }} kg)
                                    </option>
                                @endforeach
                            </select>
                            @error('form.lot_riz_etuve_id') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <!-- Étuvage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Étuvage</label>
                            <input
                                type="text"
                                value="{{ $etuvages->firstWhere('id', $form['etuvage_id'])?->code_etuvage ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                        </div>

                        <!-- Achat paddy -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Achat paddy</label>
                            <input
                                type="text"
                                value="{{ $achatPaddyLabel }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                        </div>

                        <!-- Variété -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Variété</label>
                            <input
                                type="text"
                                value="{{ $varieteLabel }}"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                        </div>

                        <!-- Quantités -->
                        <!-- Masse entrée -->
						<div>
							<label class="block text-sm font-medium text-gray-300">Masse entrée (kg)</label>
							<input type="text" wire:model.live="form.quantite_paddy_entree_kg"
								   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
							@error('form.quantite_paddy_entree_kg') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
						</div>

						<!-- Masse riz blanc -->
						<div>
							<label class="block text-sm font-medium text-gray-300">Masse riz blanc (kg)</label>
							<input type="text" wire:model.live="form.quantite_riz_blanc_kg"
								   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
						</div>

						<!-- Rejet -->
						<div>
							<label class="block text-sm font-medium text-gray-300">Rejet (kg)</label>
							<input type="text" wire:model.live="form.quantite_rejet_kg"
								   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
						</div>

						<!-- Masse brisé -->
						<div>
							<label class="block text-sm font-medium text-gray-300">Masse brisé (kg)</label>
							<input type="text" wire:model.live="form.quantite_brise_kg"
								   class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
						</div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Masse son (kg)</label>
                            <input
                                type="text"
                                wire:model="form.quantite_son_kg"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Rendement (%)</label>
                            <input
                                type="text"
                                wire:model="form.taux_rendement"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                        </div>

                        <!-- Dates -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Date début décorticage</label>
                            <input
                                type="date"
                                wire:model="form.date_debut_decorticage"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.date_debut_decorticage') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Date fin décorticage</label>
                            <input
                                type="date"
                                wire:model="form.date_fin_decorticage"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Date terminaison</label>
                            <input
                                type="date"
                                wire:model="form.date_terminaison"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                        </div>

                        <!-- Statut -->
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-300">Statut</label>
                            <select
                                wire:model="form.statut"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="en_cours">En cours</option>
                                <option value="termine">Terminé</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            Annuler
                        </button>
                        @unless($viewMode)
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        @endunless
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>