{{-- resources/views/livewire/historique-paiements-traitement.blade.php --}}
<div class="bg-slate-800 rounded-lg shadow-lg border border-slate-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-white">📊 Historique des paiements</h3>
        <button wire:click="$emit('closeModal')" class="text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    @if($paiements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">N° Paiement</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">Date</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-200">Montant</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">Mode</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-200">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($paiements as $paiement)
                        <tr class="hover:bg-slate-700">
                            <td class="px-4 py-3 font-mono text-blue-400">{{ $paiement->numero_paiement }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $paiement->date_paiement }}</td>
                            <td class="px-4 py-3 text-right font-mono text-green-400">
                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-blue-900 text-blue-200 text-xs rounded-full">
                                    {{ ucfirst($paiement->mode_paiement) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-green-900 text-green-200 text-xs rounded-full">
                                    {{ ucfirst($paiement->statut) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right text-sm text-slate-400">
            {{ $paiements->count() }} paiement(s)
        </div>
    @else
        <div class="text-center py-12 text-slate-400">
            <div class="w-16 h-16 mx-auto mb-4 bg-slate-700 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <p class="text-lg font-medium">Aucun paiement</p>
            <p class="mt-1">Ce traitement n'a pas encore de paiements enregistrés</p>
        </div>
    @endif
</div>
