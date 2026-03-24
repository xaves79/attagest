<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-sm">🔥</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Étuvages</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Transformation paddy</p>
                </div>
            </div>
            <a href="{{ route('etuvages.nouvelle') }}"
               class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouvel étuvage
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Étuvages</p>
                <p class="text-3xl font-black text-white">{{ $kpis['nb'] }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Paddy traité</p>
                <p class="text-3xl font-black text-amber-400">{{ number_format($kpis['total_entree'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz étuvé produit</p>
                <p class="text-3xl font-black text-blue-400">{{ number_format($kpis['total_etuve'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Rendement moyen</p>
                <p class="text-3xl font-black text-green-400">{{ number_format($kpis['rendement_moy'] ?? 0, 1) }}<span class="text-base font-normal text-slate-500 ml-1">%</span></p>
            </div>
        </div>

        {{-- FILTRES --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap gap-3">
            <div class="flex-1 min-w-48">
                <input type="text" wire:model.live.debounce.300ms="recherche"
                       placeholder="🔍 Code étuvage, lot paddy..."
                       class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>
            <select wire:model.live="filtrePeriode"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition">
                <option value="semaine">Cette semaine</option>
                <option value="mois" selected>Ce mois</option>
                <option value="annee">Cette année</option>
                <option value="">Tout</option>
            </select>
        </div>

        {{-- TABLEAU --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Code</th>
                        <th class="text-left px-5 py-3">Lot paddy</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="text-right px-5 py-3">Entrée</th>
                        <th class="text-right px-5 py-3">Sortie</th>
                        <th class="text-right px-5 py-3">Rendement</th>
                        <th class="text-left px-5 py-3">Lot étuvé</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($etuvages as $e)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3">
                            <span class="font-mono text-blue-400 text-xs font-bold">{{ $e->code_etuvage }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-300 text-xs font-mono">{{ $e->code_lot ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $e->variete_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">
                            {{ $e->date_debut_etuvage ? \Carbon\Carbon::parse($e->date_debut_etuvage)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right text-slate-300 font-semibold">
                            {{ number_format($e->quantite_paddy_entree_kg, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-5 py-3 text-right text-blue-400 font-semibold">
                            {{ $e->masse_apres_kg ? number_format($e->masse_apres_kg, 0, ',', ' ') . ' kg' : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($e->rendement_pourcentage)
                            <span class="font-bold {{ $e->rendement_pourcentage >= 90 ? 'text-green-400' : ($e->rendement_pourcentage >= 75 ? 'text-amber-400' : 'text-red-400') }}">
                                {{ number_format($e->rendement_pourcentage, 1) }}%
                            </span>
                            @else
                            <span class="text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($e->code_lot_etuve)
                            <span class="font-mono text-xs text-green-400 font-semibold">{{ $e->code_lot_etuve }}</span>
                            @else
                            <span class="text-slate-600 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">🔥</p>
                            <p class="text-sm">Aucun étuvage trouvé pour cette période.</p>
                            <a href="{{ route('etuvages.nouvelle') }}"
                               class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm rounded-xl transition">
                                + Enregistrer un étuvage
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($etuvages->hasPages())
            <div class="px-5 py-4 border-t border-slate-800">{{ $etuvages->links() }}</div>
            @endif
        </div>
    </div>
</div>