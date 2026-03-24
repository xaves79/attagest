<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                <!-- Barre de recherche + bouton Ajouter -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                    <div class="flex-1 max-w-md">
                        <input
                            type="text"
                            wire:model.debounce.500ms="search"
                            placeholder="Rechercher un achat de paddy..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Ajouter un achat de paddy
                    </button>
                </div>

                <!-- Liste des achats -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code lot</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variété</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date achat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté (kg)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($achats as $a)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $a->code_lot }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $a->fournisseur?->prenom ? $a->fournisseur->prenom . ' ' : '' }}{{ $a->fournisseur?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $a->variete?->nom ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $a->agent?->prenom ? $a->agent->prenom . ' ' : '' }}{{ $a->agent?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $a->date_achat?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($a->quantite_achat_kg, 1, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($a->prix_achat_unitaire_fcfa, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                        {{ number_format($a->montant_achat_total_fcfa, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                                        <button
                                            wire:click="edit({{ $a->id }})"
                                            class="text-blue-600 hover:text-blue-900"
                                            title="Modifier"
                                        >
                                            <x-heroicon-s-pencil class="h-5 w-5" />
                                        </button>
                                        <button
                                            wire:click="delete({{ $a->id }})"
                                            onclick="return confirm('Supprimer cet achat de paddy ?')"
                                            class="text-red-600 hover:text-red-900"
                                            title="Supprimer"
                                        >
                                            <x-heroicon-s-trash class="h-5 w-5" />
                                        </button>

                                        @unless($a->has_stock)
                                            <button
                                                wire:click="sendToStock({{ $a->id }})"
                                                wire:confirm="Envoyer ce lot de paddy au stock ?"
                                                class="text-green-600 hover:text-green-900"
                                                title="Envoyer au stock"
                                            >
                                                <x-heroicon-s-arrow-down-tray class="h-5 w-5" />
                                            </button>
                                        @else
                                            <span class="text-green-600 text-xs font-medium" title="Déjà en stock">
                                                <x-heroicon-s-check-circle class="h-4 w-4" />
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                        Aucun achat de paddy trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $achats->links() }}
                </div>

                <!-- Modal (formulaire) -->
                @if($showModal)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4">
                            <h2 class="text-xl font-semibold mb-4">
                                {{ $form['id'] ? 'Modifier l\'achat de paddy' : 'Ajouter un achat de paddy' }}
                            </h2>

                            <form wire:submit.prevent="save">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fournisseur</label>
                                        <select
                                            wire:model="form.fournisseur_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($fournisseurs as $f)
                                                <option value="{{ $f->id }}">
                                                    {{ $f->prenom ? $f->prenom . ' ' : '' }}{{ $f->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('form.fournisseur_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Variété</label>
                                        <select
                                            wire:model="form.variete_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Sélectionner</option>
                                            @foreach($varietes as $v)
                                                <option value="{{ $v->id }}">{{ $v->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('form.variete_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Agent</label>
                                        <select
                                            wire:model="form.agent_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Aucun</option>
                                            @foreach($agents as $a)
                                                <option value="{{ $a->id }}">
                                                    {{ $a->prenom ? $a->prenom . ' ' : '' }}{{ $a->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Localité</label>
                                        <select
                                            wire:model="form.localite_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Aucune</option>
                                            @foreach($localites as $l)
                                                <option value="{{ $l->id }}">{{ $l->nom }} ({{ $l->region }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Entreprise</label>
                                        <select
                                            wire:model="form.entreprise_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Aucune</option>
                                            @foreach($entreprises as $e)
                                                <option value="{{ $e->id }}">{{ $e->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date d’achat</label>
                                        <input
                                            type="date"
                                            wire:model="form.date_achat"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.date_achat') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantité (kg)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            wire:model="form.quantite_achat_kg"
                                            wire:change="updatedFormQuantiteAchatKg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.quantite_achat_kg') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prix unitaire (FCFA)</label>
                                        <input
                                            type="number"
                                            step="1"
                                            wire:model="form.prix_achat_unitaire_fcfa"
                                            wire:change="updatedFormPrixAchatUnitaireFcfa"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.prix_achat_unitaire_fcfa') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Montant total (FCFA)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.montant_achat_total_fcfa"
                                            readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                                        <select
                                            wire:model="form.statut"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="stock_paddy">Stock Paddy</option>
                                            <option value="en_decorticage">En décorticage</option>
                                            <option value="stock_riz_etuve">Stock riz étuvé</option>
                                            <option value="stock_riz_blanchi">Stock riz blanchi</option>
                                            <option value="vendu">Vendu</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantité restante (kg)</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="form.quantite_restante_kg"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button
                                        type="button"
                                        wire:click="$set('showModal', false)"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                    >
                                        Annuler
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
