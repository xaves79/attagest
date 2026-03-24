<div>
    @if (session()->has('message'))
        <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">Écritures comptables</h2>
            <p class="text-gray-400 mt-1">
                {{ $ecritures->total() }} écriture{{ $ecritures->total() > 1 ? 's' : '' }}
                trouvé{{ $ecritures->total() > 1 ? 'es' : '' }}
            </p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Ajouter une écriture
        </button>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
            <h3 class="text-sm font-medium text-gray-400">Total écritures</h3>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
            <h3 class="text-sm font-medium text-gray-400">Validées</h3>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $stats['validees'] }}</p>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
            <h3 class="text-sm font-medium text-gray-400">Total débit (FCFA)</h3>
            <p class="text-3xl font-bold text-green-400 mt-2">
                {{ number_format($stats['debit'], 0, ',', ' ') }}
            </p>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
            <h3 class="text-sm font-medium text-gray-400">Total crédit (FCFA)</h3>
            <p class="text-3xl font-bold text-red-400 mt-2">
                {{ number_format($stats['credit'], 0, ',', ' ') }}
            </p>
        </div>
    </div>

    {{-- Filtres avancés --}}
    <div class="bg-gray-800/50 rounded-xl p-6 mb-8 border border-gray-700">
        <h3 class="text-lg font-semibold mb-4">Filtres avancés</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">

            <div>
                <label class="block text-sm font-medium text-gray-300">Date du</label>
                <input
                    type="date"
                    wire:model.live="date_debut"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Date au</label>
                <input
                    type="date"
                    wire:model.live="date_fin"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300">Validé</label>
                <select
                    wire:model.live="valide_filter"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                    <option value="">Tous</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            {{-- Compte débit (filtre) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300">Compte débit</label>
                <select
                    wire:model.live="filter_compte_debit"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                    <option value="">Tous</option>
                    @foreach($comptes as $compte)
                        <option value="{{ $compte->code_compte }}">
                            {{ $compte->code_compte }} - {{ $compte->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Compte crédit (filtre) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300">Compte crédit</label>
                <select
                    wire:model.live="filter_compte_credit"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                    <option value="">Tous</option>
                    @foreach($comptes as $compte)
                        <option value="{{ $compte->code_compte }}">
                            {{ $compte->code_compte }} - {{ $compte->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Pièce comptable (filtre) --}}
            <div>
                <label class="block text-sm font-medium text-gray-300">Pièce comptable</label>
                <select
                    wire:model.live="filter_piece"
                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                >
                    <option value="">Toutes</option>
                    @foreach($pieces as $piece)
                        <option value="{{ $piece->code ?? $piece->libelle }}">
                            {{ $piece->code ?? $piece->libelle }} - {{ $piece->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>

    {{-- Recherche --}}
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.debounce.500ms="search"
                placeholder="Rechercher par code écriture, libellé ou pièce comptable..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    @if($ecritures->isEmpty())
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-2xl font-bold mb-2">Aucune écriture comptable trouvée</h3>
            <p class="text-gray-400 mb-6">Créez votre première écriture ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter une écriture
            </button>
        </div>
    @else
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Code écriture</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Date écriture</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Libellé</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Compte débit</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Montant débit (FCFA)</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[180px]">Compte crédit</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-200">Montant crédit (FCFA)</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Pièce comptable</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200 min-w-[120px]">Validé</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($ecritures as $e)
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4 font-mono">{{ $e->code_ecriture }}</td>
                                <td class="px-6 py-4">
                                    {{ $e->date_ecriture?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $e->libelle }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $e->compteDebit?->libelle ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-green-400">
                                    {{ number_format($e->montant_debit, 2, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    {{ $e->compteCredit?->libelle ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-red-400">
                                    {{ number_format($e->montant_credit, 2, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    {{ $e->pieceComptable?->libelle ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $e->valide ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $e->valide ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button
                                        wire:click="show({{ $e->id }})"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit({{ $e->id }})"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="delete({{ $e->id }})"
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
                {{ ($ecritures->currentPage() - 1) * $ecritures->perPage() + 1 }}
                - {{ min($ecritures->currentPage() * $ecritures->perPage(), $ecritures->total()) }}
                sur {{ $ecritures->total() }}
            </div>
            <div>{{ $ecritures->links() }}</div>
        </div>
    @endif

    {{-- Boutons d'export --}}
    <div class="flex gap-3 mt-6 flex-wrap">
        <button wire:click="exportJournalierPdf" class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
            PDF journalier
        </button>
        <button wire:click="exportJournalierExcel" class="px-4 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
            Excel journalier
        </button>

        <button wire:click="exportMensuelPdf" class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
            PDF mensuel
        </button>
        <button wire:click="exportMensuelExcel" class="px-4 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
            Excel mensuel
        </button>

        <button wire:click="exportAnnuelPdf" class="px-4 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
            PDF annuel
        </button>
        <button wire:click="exportAnnuelExcel" class="px-4 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700">
            Excel annuel
        </button>
    </div>

    {{-- Modal (formulaire) --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $form['id'] ? 'Modifier l\'écriture' : 'Ajouter une écriture' }}
                </h2>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Code écriture</label>
                            <input
                                type="text"
                                wire:model="form.code_ecriture"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                readonly
                            >
                            @error('form.code_ecriture') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Date écriture</label>
                            <input
                                type="date"
                                wire:model="form.date_ecriture"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.date_ecriture') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Libellé</label>
                            <input
                                type="text"
                                wire:model="form.libelle"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.libelle') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Compte débit (select avec codes) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Compte débit</label>
                            <select
                                wire:model="form.compte_debit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->code_compte }}">
                                        {{ $c->code_compte }} - {{ $c->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.compte_debit') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Montant débit (FCFA)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.montant_debit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.montant_debit') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Compte crédit (select avec codes) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Compte crédit</label>
                            <select
                                wire:model="form.compte_credit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner</option>
                                @foreach($comptes as $c)
                                    <option value="{{ $c->code_compte }}">
                                        {{ $c->code_compte }} - {{ $c->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.compte_credit') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Montant crédit (FCFA)</label>
                            <input
                                type="number"
                                step="0.01"
                                wire:model="form.montant_credit"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                            @error('form.montant_credit') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        {{-- Pièce comptable --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Pièce comptable</label>
                            <select
                                wire:model="form.piece_comptable"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="">Sélectionner</option>
                                @foreach($pieces as $p)
                                    <option value="{{ $p->code ?? $p->libelle }}">
                                        {{ $p->code ?? $p->libelle }} - {{ $p->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('form.piece_comptable') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300">Validé</label>
                            <select
                                wire:model="form.valide"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            >
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
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