<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 p-6">
    <div class="max-w-7xl mx-auto">
        {{-- En-tête --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-blue-400 mb-2">
                    💰 Paiements fournisseurs
                </h1>
                @if($recu)
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-blue-900/30 border border-blue-500/30 rounded-2xl backdrop-blur-sm">
                        <span class="text-2xl">📄</span>
                        <span class="text-xl font-semibold text-blue-300">{{ $recu->numero_recu }}</span>
                        <span class="text-slate-400">•</span>
                        <span class="text-lg text-slate-200">{{ $recu->fournisseur->nom }}</span>
                        <span class="text-slate-400">•</span>
                        <span class="text-xl font-bold text-yellow-400">{{ number_format($recu->montant_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                @endif
            </div>
            
            @if($recu)
                <a href="{{ route('paiements-fournisseurs.index') }}" 
                   class="group flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 rounded-2xl border border-slate-600 transition-all duration-300">
                    <svg class="w-5 h-5 text-slate-400 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-lg font-medium">Tous les paiements</span>
                </a>
            @else
                <button wire:click="create" 
                        class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xl font-bold">Nouveau paiement</span>
                </button>
            @endif
        </div>

        {{-- Recherche --}}
        @if(!$recu)
        <div class="mb-10">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Rechercher par fournisseur, référence ou mode de paiement..."
                       class="w-full pl-14 pr-6 py-4 bg-slate-800/70 backdrop-blur-sm border border-slate-600 rounded-2xl text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                <svg class="absolute left-5 top-4 w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        @endif

        {{-- Stats si reçu filtré --}}
        @if($recu)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
            <div class="bg-slate-800/60 backdrop-blur-sm rounded-2xl border border-slate-700 p-6 text-center">
                <div class="text-4xl font-bold text-emerald-400 mb-2">{{ $paiements->count() }}</div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Paiements</div>
            </div>
            <div class="bg-slate-800/60 backdrop-blur-sm rounded-2xl border border-slate-700 p-6 text-center">
                <div class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($paiements->sum('montant'), 0, ',', ' ') }}</div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Total payé</div>
            </div>
            <div class="bg-slate-800/60 backdrop-blur-sm rounded-2xl border border-slate-700 p-6 text-center">
                <div class="text-4xl font-bold text-red-400 mb-2">{{ number_format($recu->montant_total - $paiements->sum('montant'), 0, ',', ' ') }}</div>
                <div class="text-sm uppercase tracking-wider text-slate-400">Solde restant</div>
            </div>
        </div>
        @endif

        {{-- Tableau --}}
        <div class="bg-slate-800/40 backdrop-blur-md rounded-2xl border border-slate-700/50 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-800/80 border-b border-slate-700">
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Fournisseur</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">N° reçu</th>
                            <th class="px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                            @if($recu)
                                <th class="px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Montant reçu</th>
                                <th class="px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Solde après</th>
                            @endif
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Mode</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Référence</th>
                            <th class="px-6 py-5 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($paiements as $p)
                            @php
                                // Calcul du cumul des paiements pour ce reçu (si on est dans le contexte d'un reçu)
                                if($recu) {
                                    static $cumul = 0;
                                    $cumul += $p->montant;
                                }
                            @endphp
                            <tr class="hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-blue-400">{{ $p->date_paiement?->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm text-slate-200">{{ $p->recu?->fournisseur?->nom ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-mono text-emerald-400">{{ $p->recu?->numero_recu ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span class="text-base font-bold text-yellow-400">{{ number_format($p->montant, 0, ',', ' ') }}</span>
                                    <span class="text-xs text-slate-500 ml-1">FCFA</span>
                                </td>
                                @if($recu)
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <span class="text-sm text-blue-300">{{ number_format($recu->montant_total, 0, ',', ' ') }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <span class="text-sm {{ ($recu->montant_total - $cumul) > 0 ? 'text-orange-400' : 'text-green-400' }}">
                                            {{ number_format($recu->montant_total - $cumul, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @switch($p->mode_paiement)
                                        @case('espece') <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-600/20 text-green-300 rounded-full text-xs border border-green-600/30">💵 Espèce</span> @break
                                        @case('cheque') <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600/20 text-blue-300 rounded-full text-xs border border-blue-600/30">📄 Chèque</span> @break
                                        @case('mobile_money') <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600/20 text-purple-300 rounded-full text-xs border border-purple-600/30">📱 Mobile</span> @break
                                        @case('virement') <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-600/20 text-indigo-300 rounded-full text-xs border border-indigo-600/30">🏦 Virement</span> @break
                                        @default <span class="text-xs">{{ $p->mode_paiement }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-xs font-mono text-slate-400 bg-slate-700/50 px-3 py-1 rounded-full">{{ $p->reference ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <button wire:click="delete({{ $p->id }})" 
                                            onclick="return confirm('Supprimer ce paiement ?')"
                                            class="p-2 text-red-400 hover:text-red-300 hover:bg-red-600/10 rounded-lg transition-colors"
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $recu ? '9' : '7' }}" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 mb-4 rounded-full bg-slate-700/50 flex items-center justify-center">
                                            <span class="text-4xl text-slate-500">{{ $recu ? '💰' : '📭' }}</span>
                                        </div>
                                        <p class="text-lg text-slate-400">
                                            @if($recu) Aucun paiement pour ce reçu @else Aucun paiement trouvé @endif
                                        </p>
                                        @if(!$recu)
                                            <button wire:click="create" class="mt-4 px-6 py-2 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-300 rounded-lg border border-emerald-600/30 transition-colors">
                                                + Créer un paiement
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($paiements->hasPages())
        <div class="mt-8">
            {{ $paiements->links() }}
        </div>
        @endif

        {{-- Modal création --}}
        @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl p-6">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-3 text-slate-100">
                    <span class="text-2xl">💰</span> Nouveau paiement
                </h3>
                
                <form wire:submit="save" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Montant (FCFA)</label>
                        <input type="number" wire:model="montant" step="0.01" required
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                        @error('montant') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Date paiement</label>
                            <input type="date" wire:model="date_paiement" required
                                   class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500">
                            @error('date_paiement') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1">Mode</label>
                            <select wire:model="mode_paiement" required
                                    class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white focus:ring-2 focus:ring-emerald-500">
                                <option value="espece">💵 Espèce</option>
                                <option value="cheque">📄 Chèque</option>
                                <option value="mobile_money">📱 Mobile money</option>
                                <option value="virement">🏦 Virement</option>
                            </select>
                            @error('mode_paiement') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Référence (optionnel)</label>
                        <input type="text" wire:model="reference" maxlength="100"
                               class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white">
                        @error('reference') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="3"
                                  class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-white resize-none"></textarea>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="$set('showForm', false)"
                                class="flex-1 px-4 py-3 bg-slate-600 hover:bg-slate-500 text-white font-medium rounded-xl transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white font-medium rounded-xl shadow-lg transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>