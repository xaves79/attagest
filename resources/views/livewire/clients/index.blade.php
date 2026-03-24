<div class="py-6 bg-slate-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-slate-800 border-b border-slate-700">

                {{-- Messages flash --}}
                @if (session()->has('message'))
                    <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Barre de recherche + bouton Ajouter -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                    <div class="flex-1 max-w-md">
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="Rechercher un client..."
                            class="block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                        >
                    </div>
                    <button
                        wire:click="create"
                        class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Ajouter un client
                    </button>
                </div>

                <!-- Liste des clients -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Nom / Raison sociale</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Téléphone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Code client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-800 divide-y divide-slate-700">
                            @forelse($clients as $c)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">
                                        @if($c->raison_sociale)
                                            {{ $c->raison_sociale }} {{ $c->sigle ? '('.$c->sigle.')' : '' }}
                                        @else
                                            {{ $c->prenom }} {{ $c->nom }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $c->telephone ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $c->email ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $c->code_client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-200">{{ $c->type_client }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <button wire:click="show({{ $c->id }})" class="text-blue-400 hover:text-blue-300 mx-1" title="Voir">👁️</button>
                                        <button wire:click="edit({{ $c->id }})" class="text-yellow-400 hover:text-yellow-300 mx-1" title="Modifier">✏️</button>
                                        <button wire:click="delete({{ $c->id }})" wire:confirm="Supprimer ce client ?" class="text-red-400 hover:text-red-300 mx-1" title="Supprimer">🗑️</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-400">
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
                    <div class="fixed inset-0 bg-slate-900 bg-opacity-75 flex items-center justify-center z-50">
                        <div class="bg-slate-800 rounded-lg shadow-xl max-w-2xl w-full p-6 mx-4 border border-slate-700 max-h-[90vh] overflow-y-auto">
                            <h2 class="text-xl font-semibold text-white mb-4">
                                {{ $viewMode ? 'Détails du client' : ($form['id'] ? 'Modifier le client' : 'Ajouter un client') }}
                            </h2>

                            @if($viewMode)
                                {{-- Mode lecture --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div><span class="text-slate-400">Code client :</span> {{ $form['code_client'] }}</div>
                                    @if($form['type_personne'] == 'PHYSIQUE')
                                        <div><span class="text-slate-400">Nom :</span> {{ $form['nom'] }}</div>
                                        <div><span class="text-slate-400">Prénom :</span> {{ $form['prenom'] }}</div>
                                    @else
                                        <div><span class="text-slate-400">Raison sociale :</span> {{ $form['raison_sociale'] }}</div>
                                        <div><span class="text-slate-400">Sigle :</span> {{ $form['sigle'] }}</div>
                                    @endif
                                    <div><span class="text-slate-400">Type client :</span> {{ $form['type_client'] }}</div>
                                    <div><span class="text-slate-400">Téléphone :</span> {{ $form['telephone'] ?? '-' }}</div>
                                    <div><span class="text-slate-400">WhatsApp :</span> {{ $form['whatsapp'] ?? '-' }}</div>
                                    <div><span class="text-slate-400">Email :</span> {{ $form['email'] ?? '-' }}</div>
                                    <div><span class="text-slate-400">Adresse :</span> {{ $form['adresse'] ?? '-' }}</div>
                                    <div><span class="text-slate-400">Localité :</span> {{ $localites->firstWhere('id', $form['localite_id'])?->nom ?? '-' }}</div>
                                    <div><span class="text-slate-400">Point de vente :</span> {{ $pointsVente->firstWhere('id', $form['point_vente_id'])?->nom ?? '-' }}</div>
                                    <div><span class="text-slate-400">Type d'achat :</span> {{ $form['type_achat'] ?? '-' }}</div>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500">
                                        Fermer
                                    </button>
                                </div>
                            @else
                                {{-- Formulaire --}}
                                <form wire:submit.prevent="save">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Type personne -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Type *</label>
                                            <select wire:model.live="form.type_personne" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                <option value="PHYSIQUE">Personne physique</option>
                                                <option value="MORALE">Personne morale</option>
                                            </select>
                                        </div>

                                        <!-- Code client -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Code client *</label>
                                            <input type="text" wire:model="form.code_client" readonly class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                            @error('form.code_client') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Champs conditionnels -->
                                        @if($form['type_personne'] == 'PHYSIQUE')
                                            <div wire:key="physique-nom">
                                                <label class="block text-sm font-medium text-slate-300">Nom *</label>
                                                <input type="text" wire:model="form.nom" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                @error('form.nom') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                            </div>
                                            <div wire:key="physique-prenom">
                                                <label class="block text-sm font-medium text-slate-300">Prénom</label>
                                                <input type="text" wire:model="form.prenom" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                            </div>
                                        @else
                                            <div wire:key="morale-raison">
                                                <label class="block text-sm font-medium text-slate-300">Raison sociale *</label>
                                                <input type="text" wire:model="form.raison_sociale" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                @error('form.raison_sociale') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                            </div>
                                            <div wire:key="morale-sigle">
                                                <label class="block text-sm font-medium text-slate-300">Sigle</label>
                                                <input type="text" wire:model="form.sigle" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                @error('form.sigle') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                            </div>
                                        @endif

                                        <!-- Téléphone -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Téléphone</label>
                                            <input type="text" wire:model="form.telephone" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        </div>

                                        <!-- WhatsApp -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">WhatsApp</label>
                                            <input type="text" wire:model="form.whatsapp" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        </div>

                                        <!-- Email -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Email</label>
                                            <input type="email" wire:model="form.email" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        </div>

                                        <!-- Adresse -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Adresse</label>
                                            <textarea wire:model="form.adresse" rows="2" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                                        </div>

                                        <!-- Localité -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Localité</label>
                                            <select wire:model="form.localite_id" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                <option value="">Sélectionner</option>
                                                @foreach($localites as $localite)
                                                    <option value="{{ $localite->id }}">{{ $localite->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Point de vente -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Point de vente</label>
                                            <select wire:model="form.point_vente_id" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                <option value="">Sélectionner</option>
                                                @foreach($pointsVente as $pv)
                                                    <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Type client (catégorie) -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Catégorie client *</label>
                                            <select wire:model="form.type_client" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                                <option value="PARTICULIER">Particulier</option>
                                                <option value="GROSSISTE">Grossiste</option>
                                                <option value="RESTAURANT">Restaurant</option>
                                                <option value="HOTEL">Hôtel</option>
                                                <option value="MARCHE">Marché</option>
                                                <option value="DETAILLANT">Détaillant</option>
                                            </select>
                                            @error('form.type_client') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Type d'achat -->
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300">Type d'achat</label>
                                            <input type="text" wire:model="form.type_achat" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-slate-200 rounded hover:bg-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                            Annuler
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                            {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>