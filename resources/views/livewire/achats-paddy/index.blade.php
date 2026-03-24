<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white p-6">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Messages flash --}}
        @if (session()->has('message'))
            <div class="bg-green-800 border-l-4 border-green-400 text-green-200 px-4 py-3 rounded-lg mb-6 animate-pulse shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-800 border-l-4 border-red-400 text-red-200 px-4 py-3 rounded-lg mb-6 animate-pulse shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 class="text-4xl font-extrabold bg-gradient-to-r from-blue-400 to-green-400 bg-clip-text text-transparent">
                    Achats de paddy
                </h2>
                <p class="text-gray-400 mt-1">
                    {{ $achats->total() }} achat{{ $achats->total() > 1 ? 's' : '' }} trouvé{{ $achats->total() > 1 ? 's' : '' }}
                </p>
            </div>
            <button wire:click="create" class="group relative inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvel achat
            </button>
        </div>

        {{-- RECHERCHE --}}
        <div class="mb-6">
            <input type="text" wire:model.live="search" placeholder="Rechercher par code lot ou fournisseur..." 
                   class="w-full max-w-md px-6 py-4 bg-gray-800/50 border border-gray-600 rounded-2xl text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-xl">
        </div>

        {{-- TABLEAU --}}
        @if($achats->isEmpty())
            <div class="bg-gray-800/70 backdrop-blur-sm rounded-2xl p-16 shadow-2xl text-center border-2 border-dashed border-gray-600">
                <div class="text-8xl mb-6 animate-bounce">📦</div>
                <h3 class="text-3xl font-bold mb-4 text-gray-200">Aucun achat trouvé</h3>
                <p class="text-gray-400 mb-8 text-lg">Commencez par créer votre premier achat de paddy.</p>
                <button wire:click="create" class="px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    ✨ Créer un achat
                </button>
            </div>
        @else
            <div class="bg-gray-800/30 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl border border-gray-700/50">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-800 via-gray-900 to-gray-800 border-b border-gray-700/50">
                                <th class="px-6 py-5 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Code lot</th>
                                <th class="px-6 py-5 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Fournisseur</th>
                                <th class="px-6 py-5 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-5 text-right text-sm font-bold text-gray-300 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-5 text-right text-sm font-bold text-gray-300 uppercase tracking-wider">Prix/kg</th>
                                <th class="px-6 py-5 text-right text-sm font-bold text-gray-300 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-5 text-center text-sm font-bold text-gray-300 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-5 text-center text-sm font-bold text-gray-300 uppercase tracking-wider min-w-[320px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/30">
                            @foreach ($achats as $a)
                                <tr class="hover:bg-gray-700/20 transition-all duration-300 group">
                                    <td class="px-6 py-5 font-mono text-blue-300 font-medium">{{ $a->code_lot }}</td>
                                    <td class="px-6 py-5 truncate max-w-[220px]" title="{{ $a->fournisseur?->nom ?? '-' }}">
                                        {{ $a->fournisseur?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 truncate max-w-[200px]" title="{{ $a->agent?->nom ?? '-' }}">
                                        {{ $a->agent?->nom ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right text-blue-300 font-semibold">{{ number_format($a->quantite_achat_kg, 1, ',', ' ') }} kg</td>
                                    <td class="px-6 py-5 text-right text-green-300 font-semibold">{{ number_format($a->prix_achat_unitaire_fcfa, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-5 text-right text-yellow-300 font-bold">{{ number_format($a->montant_achat_total_fcfa, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-5 text-center">
                                        @if($a->has_stock)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-900/30 text-green-300 border border-green-500/30 shadow-lg">
                                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                                Oui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-900/30 text-red-300 border border-red-500/30">
                                                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                                Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            {{-- 👁️ Voir --}}
                                            <button wire:click="show({{ $a->id }})" class="p-2 bg-blue-600/20 hover:bg-blue-600/40 text-blue-300 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:scale-105" title="Voir les détails">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            
                                            {{-- ✏️ Modifier --}}
                                            <button wire:click="edit({{ $a->id }})" class="p-2 bg-yellow-600/20 hover:bg-yellow-600/40 text-yellow-300 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:scale-105" title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            
                                            {{-- 🗑️ Supprimer --}}
                                            <button wire:click="delete({{ $a->id }})" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $a->code_lot }} ?')"
                                                    class="p-2 bg-red-600/20 hover:bg-red-600/40 text-red-300 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:scale-105" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>

                                            {{-- 📦 Stock --}}
                                            @if(!$a->has_stock)
                                                <button wire:click="sendToStock({{ $a->id }})" 
                                                        class="p-2 bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 rounded-xl transition-all shadow-md hover:shadow-lg transform hover:scale-105" title="Créer stock">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $achats->links() }}
            </div>
        @endif

        {{-- MODAL SHOW --}}
        @if($showModal && $viewMode)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 p-4" 
                 x-data x-show="$wire.showModal" x-transition>
                <div class="bg-gray-800/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-700 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                Détails de l'achat
                            </h3>
                            <button wire:click="$set('showModal', false); $set('viewMode', false)" class="text-gray-400 hover:text-white text-3xl transition">&times;</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Code lot :</span> <span class="text-white font-mono">{{ $form['code_lot'] ?? '' }}</span></div>
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Date achat :</span> <span class="text-white">{{ \Carbon\Carbon::parse($form['date_achat'])->format('d/m/Y') ?? '' }}</span></div>
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Fournisseur :</span> <span class="text-white">{{ $fournisseurs->where('id', $form['fournisseur_id'])->first()?->nom ?? 'N/A' }}</span></div>
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Agent :</span> <span class="text-white">{{ $agents->where('id', $form['agent_id'])->first()?->nom ?? 'N/A' }}</span></div>
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Variété :</span> <span class="text-white">{{ $varietes->where('id', $form['variete_id'])->first()?->nom ?? 'N/A' }}</span></div>
                            <div class="bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Quantité :</span> <span class="text-white font-semibold">{{ number_format($form['quantite_achat_kg'] ?? 0, 1, ',', ' ') }} kg</span></div>
                            <div class="md:col-span-2 bg-gray-700/30 p-4 rounded-xl"><span class="text-gray-400">Montant total :</span> <span class="text-xl font-bold text-yellow-400">{{ number_format($form['montant_achat_total_fcfa'] ?? 0, 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button wire:click="$set('showModal', false)" class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg transition">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL CREATE/EDIT --}}
        @if($showModal && !$viewMode)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 p-4" x-data x-show="$wire.showModal" x-transition>
                <div class="bg-gray-800/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-700 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                                {{ $form['id'] ? 'Modifier l\'achat' : 'Nouvel achat de paddy' }}
                            </h3>
                            <button wire:click="resetForm" class="text-gray-400 hover:text-white text-3xl transition">&times;</button>
                        </div>
                        <form wire:submit="save">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Fournisseur <span class="text-red-500">*</span></label>
                                    <select wire:model="form.fournisseur_id" required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionner</option>
                                        @foreach($fournisseurs as $f) <option value="{{ $f->id }}">{{ $f->nom }}</option> @endforeach
                                    </select>
                                    @error('form.fournisseur_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Agent</label>
                                    <select wire:model="form.agent_id" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionner</option>
                                        @foreach($agents as $agent) <option value="{{ $agent->id }}">{{ $agent->nom }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Variété <span class="text-red-500">*</span></label>
                                    <select wire:model="form.variete_id" required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                        <option value="">Sélectionner</option>
                                        @foreach($varietes as $v) <option value="{{ $v->id }}">{{ $v->nom }}</option> @endforeach
                                    </select>
                                    @error('form.variete_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Date achat <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="form.date_achat" required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantité (kg) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.1" min="0.1" wire:model.live="form.quantite_achat_kg" required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Prix unitaire (FCFA) <span class="text-red-500">*</span></label>
                                    <input type="number" step="1" min="0" wire:model.live="form.prix_achat_unitaire_fcfa" required class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="lg:col-span-3">
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Montant total (FCFA)</label>
                                    <input type="text" wire:model="form.montant_achat_total_fcfa" readonly class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-yellow-400 font-bold">
                                </div>
                            </div>
                            <div class="mt-8 flex justify-end gap-4">
                                <button type="button" wire:click="resetForm" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-lg transition">
                                    Annuler
                                </button>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-2xl transform hover:scale-105 transition">
                                    {{ $form['id'] ? 'Mettre à jour' : 'Enregistrer' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
