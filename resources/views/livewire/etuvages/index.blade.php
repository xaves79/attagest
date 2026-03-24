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

        {{-- Alerte stock paddy --}}
        @php
            $stockPaddyTotal = \App\Models\StockPaddy::sum('quantite_restante_kg');
            $alertClass = '';
            $alertMessage = '';
            if ($stockPaddyTotal <= 1200) {
                $alertClass = 'bg-red-800 border-red-600 text-red-200';
                $alertMessage = '⚠️ Stock de paddy critique ! (' . number_format($stockPaddyTotal, 0, ',', ' ') . ' kg restants) Risque d\'arrêt de production. Approvisionnez-vous.';
            } elseif ($stockPaddyTotal <= 1800) {
                $alertClass = 'bg-orange-800 border-orange-600 text-orange-200';
                $alertMessage = '⚠️ Stock de paddy faible (' . number_format($stockPaddyTotal, 0, ',', ' ') . ' kg restants). Prévoyez un réapprovisionnement.';
            } elseif ($stockPaddyTotal <= 2400) {
                $alertClass = 'bg-yellow-800 border-yellow-600 text-yellow-200';
                $alertMessage = '⚠️ Stock de paddy bientôt critique (' . number_format($stockPaddyTotal, 0, ',', ' ') . ' kg restants). Surveillez votre consommation.';
            }
        @endphp

        @if($alertClass)
            <div class="{{ $alertClass }} border px-4 py-3 rounded mb-6 flex items-center gap-2">
                <span class="text-lg">⚠️</span>
                <span>{{ $alertMessage }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold">Étuvages</h2>
                <p class="text-gray-400 mt-1">
                    {{ $etuvages->total() }} étuvage{{ $etuvages->total() > 1 ? 's' : '' }}
                    trouvé{{ $etuvages->total() > 1 ? 's' : '' }}
                </p>
            </div>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
            >
                ➕ Créer un étuvage
            </button>
        </div>

        {{-- Recherche --}}
        <div class="mb-8">
            <div class="relative max-w-md">
                <input
                    type="text"
                    wire:model.debounce.500ms="search"
                    placeholder="Rechercher par code étuvage, agent ou statut..."
                    class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
                />
                <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        @if($etuvages->isEmpty())
            <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
                <div class="text-6xl mb-4">🔥</div>
                <h3 class="text-2xl font-bold mb-2">Aucun étuvage trouvé</h3>
                <p class="text-gray-400 mb-6">Créez votre premier étuvage ou vérifiez vos critères de recherche.</p>
                <button
                    wire:click="create"
                    class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
                >
                    Créer un étuvage
                </button>
            </div>
        @else
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200">Code étuvage</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Agent</th>
                                <th class="px-6 py-4 text-right font-semibold text-gray-200">Masse entrée (kg)</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200">Début</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200">Fin</th>
                                <th class="px-6 py-4 text-left font-semibold text-gray-200">Statut</th>
                                <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($etuvages as $etuvage)
                                <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                    <td class="px-6 py-4 font-mono bg-gray-900/50 rounded-l-lg group-hover:bg-green-900/30">
                                        {{ $etuvage->code_etuvage }}
                                    </td>
                                    <td class="px-6 py-4 max-w-[180px] truncate">
                                        {{ $etuvage->agent?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-green-400">
                                        {{ number_format($etuvage->masse_entree_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $etuvage->date_debut_etuvage?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $etuvage->date_fin_etuvage?->format('d/m/Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $etuvage->statut === 'termine' ? 'bg-green-900/50 text-green-200' : 'bg-yellow-900/50 text-yellow-200' }}
                                            border border-gray-600">
                                            {{ $etuvage->statut }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center space-x-1">
                                        <button
                                            wire:click="show({{ $etuvage->id }})"
                                            class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Voir les détails"
                                        >
                                            👁️
                                        </button>
                                        <button
                                            wire:click="edit({{ $etuvage->id }})"
                                            class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Modifier"
                                        >
                                            ✏️
                                        </button>
                                        <button
                                            wire:click="delete({{ $etuvage->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce mouvement ?"
                                            class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                            title="Supprimer"
                                        >
                                            🗑️
                                        </button>

                                        @if($etuvage->statut !== 'termine')
                                            @if($etuvage->masse_sortie_kg)
                                                <button
                                                    wire:click="terminer({{ $etuvage->id }})"
                                                    class="px-3 py-1.5 bg-green-600/90 hover:bg-green-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                                    title="Terminer et créer lot riz étuvé"
                                                >
                                                    ✅ Terminer
                                                </button>
                                            @else
                                                <span class="text-xs text-yellow-400 ml-2">Masse sortie non saisie</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center text-sm text-gray-400">
                <div>
                    {{ ($etuvages->currentPage() - 1) * $etuvages->perPage() + 1 }} -
                    {{ min($etuvages->currentPage() * $etuvages->perPage(), $etuvages->total()) }} sur {{ $etuvages->total() }}
                </div>
                <div>{{ $etuvages->links() }}</div>
            </div>
        @endif

        {{-- Modal SHOW (lecture seule) --}}
        @if($showModal && $viewMode)
            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 backdrop-blur-sm" wire:click="$set('showModal', false)">
                <div class="bg-gray-800/95 rounded-2xl p-8 shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto border border-gray-700" wire:click.stop>
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-3xl font-bold text-green-400 flex items-center">
                            🔥 Détails étuvage
                            <span class="ml-3 bg-green-900/50 px-4 py-1 rounded-full text-sm font-semibold">{{ $form['code_etuvage'] }}</span>
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
                                    <div><span class="text-gray-400">Code étuvage :</span> <span class="font-mono font-bold text-green-400">{{ $form['code_etuvage'] }}</span></div>
                                    <div><span class="text-gray-400">Statut :</span> <span class="font-semibold">{{ $form['statut'] }}</span></div>
                                    <div><span class="text-gray-400">Agent :</span> <span class="font-semibold">{{ $agents->find($form['agent_id'])?->nom ?? '-' }}</span></div>
                                </div>
                            </div>

                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-4 text-gray-200">📦 Stock / Achat</h3>
                                <div class="space-y-3">
                                    <div><span class="text-gray-400 block mb-1">Stock paddy :</span>
                                        <span class="font-semibold">{{ $stocks_paddy->find($form['stock_paddy_id'])?->code_stock ?? '-' }}</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Achat lié :</span>
                                        <span class="font-semibold">{{ $etuvages->find($form['id'])?->achatPaddy?->code_lot ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-6 text-gray-200">📊 Paramètres étuvage</h3>
                                <div class="space-y-3">
                                    <div><span class="text-gray-400 block mb-1">Masse entrée (kg) :</span>
                                        <span class="font-bold text-green-400">{{ number_format($form['masse_entree_kg'], 1, ',', ' ') }} kg</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Température (°C) :</span>
                                        <span class="font-semibold">{{ $form['temperature_etuvage'] ?? '-' }} °C</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Durée (min) :</span>
                                        <span class="font-semibold">{{ $form['duree_etuvage_minutes'] ?? '-' }} min</span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Masse sortie (kg) :</span>
                                        <span class="font-semibold text-blue-400">
                                            {{ $form['masse_sortie_kg'] ? number_format($form['masse_sortie_kg'], 1, ',', ' ') . ' kg' : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                                <h3 class="font-bold text-xl mb-4 text-gray-200">📅 Dates</h3>
                                <div class="space-y-3">
                                    <div><span class="text-gray-400 block mb-1">Début :</span>
                                        <span class="font-semibold">
                                            {{ $form['date_debut_etuvage'] ? \Carbon\Carbon::parse($form['date_debut_etuvage'])->format('d/m/Y H:i') : '-' }}
                                        </span>
                                    </div>
                                    <div><span class="text-gray-400 block mb-1">Fin :</span>
                                        <span class="font-semibold">
                                            {{ $form['date_fin_etuvage'] ? \Carbon\Carbon::parse($form['date_fin_etuvage'])->format('d/m/Y H:i') : '-' }}
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
                            ✏️ Modifier cet étuvage
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
                            {{ $form['id'] ? '✏️ Modifier' : '🔥 Créer' }} étuvage
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
                                    Code étuvage <span class="text-red-400">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="form.code_etuvage"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Agent <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model="form.agent_id"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                >
                                    <option value="">Sélectionner un agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Stock paddy <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model.live="form.stock_paddy_id"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                >
                                    <option value="">Sélectionner un stock</option>
                                    @foreach ($stocks_paddy as $stock)
                                        <option value="{{ $stock->id }}">
                                            {{ $stock->code_stock }} ({{ number_format($stock->quantite_restante_kg, 1, ',', ' ') }} kg restants)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Champ caché pour l'achat lié (rempli automatiquement par updatedFormStockPaddyId) --}}
                            <input type="hidden" wire:model="form.achat_paddy_id">

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Masse entrée (kg) <span class="text-red-400">*</span>
                                </label>
                                <input
                                    type="number"
                                    step="0.1"
                                    wire:model="form.masse_entree_kg"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white text-2xl font-bold focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Température (°C)
                                </label>
                                <input
                                    type="number"
                                    step="0.1"
                                    wire:model="form.temperature_etuvage"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Durée (minutes)
                                </label>
                                <input
                                    type="number"
                                    wire:model="form.duree_etuvage_minutes"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Date début <span class="text-red-400">*</span>
                                </label>
                                <input
                                    type="datetime-local"
                                    wire:model="form.date_debut_etuvage"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Date fin
                                </label>
                                <input
                                    type="datetime-local"
                                    wire:model="form.date_fin_etuvage"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Masse sortie (kg) après pesée
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    wire:model="form.masse_sortie_kg"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-3 text-gray-300">
                                    Statut <span class="text-red-400">*</span>
                                </label>
                                <select
                                    wire:model="form.statut"
                                    class="w-full bg-gray-700/50 border border-gray-600 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all"
                                    required
                                >
                                    <option value="en_cours">En cours</option>
                                    <option value="termine">Terminé</option>
                                    <option value="annule">Annulé</option>
                                </select>
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