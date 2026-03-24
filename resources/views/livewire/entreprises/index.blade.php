<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-emerald-400">
                        Entreprises
                    </h2>
                    <button
                        wire:click="create"
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-semibold"
                    >
                        + Nouvelle entreprise
                    </button>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Barre de recherche -->
                <div class="mb-6">
                    <input
                        wire:model.live="search"
                        type="text"
                        placeholder="Rechercher une entreprise..."
                        class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                </div>

                <!-- Liste des entreprises -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                    Logo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                    Sigle
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                    Code
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-800 divide-y divide-slate-700">
                        @forelse ($entreprises as $entreprise)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    <img src="{{ $entreprise->logo_url }}" alt="Logo" class="w-8 h-8 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $entreprise->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $entreprise->sigle }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $entreprise->code_entreprise }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        wire:click="showDetails({{ $entreprise->id }})"
                                        class="text-blue-400 hover:text-blue-300 mr-3"
                                    >
                                        Show
                                    </button>
                                    <button
                                        wire:click="edit({{ $entreprise->id }})"
                                        class="text-emerald-400 hover:text-emerald-300 mr-3"
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        wire:click="delete({{ $entreprise->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cette entreprise ?"
                                        class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Supprimer"
                                    >
                                        🗑️
                                    </button>
                                </td>
                            </tr>

                            <!-- Bloc détaillé qui s'affiche quand on clique sur "Show" -->
                            @if ($showDetailsId === $entreprise->id)
                                <tr class="bg-slate-700">
                                    <td colspan="5" class="px-6 py-4 text-sm text-slate-300">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <strong>Nom :</strong> {{ $entreprise->nom }}<br>
                                                <strong>Sigle :</strong> {{ $entreprise->sigle }}<br>
                                                <strong>Code entreprise :</strong> {{ $entreprise->code_entreprise }}<br>
                                                <strong>Téléphone :</strong> {{ $entreprise->telephone }}<br>
                                                <strong>WhatsApp :</strong> {{ $entreprise->whatsapp }}<br>
                                            </div>
                                            <div>
                                                <strong>Email :</strong> {{ $entreprise->email }}<br>
                                                <strong>Adresse :</strong> {{ $entreprise->adresse }}<br>
                                                <strong>Gérant :</strong> {{ $entreprise->gerant_nom }}<br>
                                                <strong>Logo :</strong>
                                                @if ($entreprise->logo)
                                                    <img src="{{ $entreprise->logo_url }}" alt="Logo" class="h-8 mt-1">
                                                @else
                                                    <span class="text-slate-400">Aucun logo</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-400">
                                    Aucune entreprise trouvée.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $entreprises->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal (formulaire) -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $form['id'] ? 'Modifier l\'entreprise' : 'Nouvelle entreprise' }}
                </h2>

                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Code entreprise *</label>
                            <input type="text" wire:model="form.code_entreprise" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('form.code_entreprise') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nom *</label>
                            <input type="text" wire:model="form.nom" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('form.nom') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Sigle *</label>
                            <input type="text" wire:model="form.sigle" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            @error('form.sigle') <span class="text-sm text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Gérant</label>
                            <input type="text" wire:model="form.gerant_nom" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Téléphone</label>
                            <input type="text" wire:model="form.telephone" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">WhatsApp</label>
                            <input type="text" wire:model="form.whatsapp" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Email</label>
                            <input type="email" wire:model="form.email" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Adresse</label>
                            <input type="text" wire:model="form.adresse" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        {{-- Champ logo avec indicateur de chargement --}}
                        <div class="lg:col-span-3">
                            <label class="block text-sm font-medium text-gray-300">Logo</label>
                            <input
                                type="file"
                                wire:model="logo"
                                wire:loading.attr="disabled"
                                accept="image/*"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                            >
                            <div wire:loading wire:target="logo" class="text-sm text-blue-400 mt-1">
                                ⏳ Téléchargement en cours...
                            </div>
                            @error('logo')
                                <span class="text-sm text-red-400">{{ $message }}</span>
                            @enderror
                            @if ($logo)
                                <div class="mt-2">
                                    <img src="{{ $logo->temporaryUrl() }}" class="h-20 rounded-lg">
                                </div>
                            @elseif ($form['logo'])
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $form['logo']) }}" class="h-20 rounded-lg">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Annuler
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ $form['id'] ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>