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
                            placeholder="Rechercher un client..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Ajouter un client
                    </button>
                </div>

                <!-- Liste des clients -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Point de vente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($clients as $c)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->type_client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $c->prenom ? $c->prenom . ' ' : '' }}{{ $c->nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->code_client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->localite?->nom ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $c->pointVente?->nom ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click="edit({{ $c->id }})"
                                            class="text-blue-600 hover:text-blue-900 mr-4"
                                        >
                                            Modifier
                                        </button>
                                        <button
                                            wire:click="delete({{ $c->id }})"
                                            onclick="return confirm('Supprimer ce client ?')"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Aucun client trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $clients->links() }}
                </div>

                <!-- Modal (formulaire) -->
                @if($showModal)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 mx-4">
                            <h2 class="text-xl font-semibold mb-4">
                                {{ $form['id'] ? 'Modifier le client' : 'Ajouter un client' }}
                            </h2>

                            <form wire:submit.prevent="save">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type de client</label>
                                        <select
                                            wire:model="form.type_client"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="GROSSISTE">Grossiste</option>
                                            <option value="PARTICULIER">Particulier</option>
                                            <option value="RESTAURANT">Restaurant</option>
                                            <option value="HOTEL">Hôtel</option>
                                            <option value="MARCHE">Marché</option>
                                            <option value="DETAILLANT">Détailant</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                                        <input
                                            type="text"
                                            wire:model="form.nom"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.nom') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prénom</label>
                                        <input
                                            type="text"
                                            wire:model="form.prenom"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Raison sociale</label>
                                        <input
                                            type="text"
                                            wire:model="form.raison_sociale"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Sigle</label>
                                        <input
                                            type="text"
                                            wire:model="form.sigle"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.sigle') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Code client</label>
                                        <input
                                            type="text"
                                            wire:model="form.code_client"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                        @error('form.code_client') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                        <input
                                            type="text"
                                            wire:model="form.whatsapp"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                                        <input
                                            type="text"
                                            wire:model="form.telephone"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input
                                            type="email"
                                            wire:model="form.email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
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
                                        <label class="block text-sm font-medium text-gray-700">Point de vente</label>
                                        <select
                                            wire:model="form.point_vente_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="">Aucun</option>
                                            @foreach($points_vente as $p)
                                                <option value="{{ $p->id }}">{{ $p->nom }} ({{ $p->localite?->nom ?? '-' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type d’achat</label>
                                        <select
                                            wire:model="form.type_achat"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        >
                                            <option value="Riz Blanc">Riz Blanc</option>
                                            <option value="Riz Étuvé">Riz Étuvé</option>
                                            <option value="Paddy">Paddy</option>
                                        </select>
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
