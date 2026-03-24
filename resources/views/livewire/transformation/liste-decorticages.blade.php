<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-sm">⚙️</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Décorticages</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Riz étuvé → Riz blanc + Son + Brisures</p>
                </div>
            </div>
            <a href="{{ route('decorticages.nouvelle') }}"
               class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouveau décorticage
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Décorticages</p>
                <p class="text-3xl font-black text-white">{{ $kpis['nb'] }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz étuvé traité</p>
                <p class="text-3xl font-black text-purple-400">{{ number_format($kpis['total_entree'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz blanc produit</p>
                <p class="text-3xl font-black text-green-400">{{ number_format($kpis['total_blanc'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
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
                       placeholder="🔍 Code décorticage, lot étuvé..."
                       class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-purple-500 transition">
            </div>
            <select wire:model.live="filtrePeriode"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-purple-500 transition">
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
                        <th class="text-left px-5 py-3">Lot étuvé</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="text-right px-5 py-3">Entrée</th>
                        <th class="text-right px-5 py-3">Riz blanc</th>
                        <th class="text-right px-5 py-3">Son</th>
                        <th class="text-right px-5 py-3">Brisures</th>
                        <th class="text-right px-5 py-3">Rendement</th>
                        <th class="text-center px-5 py-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($decorticages as $d)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3">
                            <span class="font-mono text-purple-400 text-xs font-bold">{{ $d->code_decorticage }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-300 text-xs font-mono">{{ $d->code_lot_etuve ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $d->variete_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">
                            {{ $d->date_debut_decorticage ? \Carbon\Carbon::parse($d->date_debut_decorticage)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-3 text-right text-slate-300 font-semibold">{{ number_format($d->quantite_paddy_entree_kg, 0, ',', ' ') }} kg</td>
                        <td class="px-5 py-3 text-right text-green-400 font-semibold">{{ $d->quantite_riz_blanc_kg > 0 ? number_format($d->quantite_riz_blanc_kg, 0, ',', ' ') . ' kg' : '—' }}</td>
                        <td class="px-5 py-3 text-right text-amber-400">{{ $d->quantite_son_kg > 0 ? number_format($d->quantite_son_kg, 0, ',', ' ') . ' kg' : '—' }}</td>
                        <td class="px-5 py-3 text-right text-yellow-400">{{ $d->quantite_brise_kg > 0 ? number_format($d->quantite_brise_kg, 0, ',', ' ') . ' kg' : '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            @if($d->taux_rendement > 0)
                            <span class="font-bold {{ $d->taux_rendement >= 60 ? 'text-green-400' : ($d->taux_rendement >= 50 ? 'text-amber-400' : 'text-red-400') }}">
                                {{ number_format($d->taux_rendement, 1) }}%
                            </span>
                            @else <span class="text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs border
                                {{ $d->statut === 'termine' ? 'bg-green-900/50 text-green-300 border-green-700/50' : 'bg-purple-900/50 text-purple-300 border-purple-700/50' }}">
                                {{ $d->statut === 'termine' ? '✓ Terminé' : '⏳ En cours' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">⚙️</p>
                            <p class="text-sm">Aucun décorticage pour cette période.</p>
                            <a href="{{ route('decorticages.nouvelle') }}"
                               class="inline-block mt-4 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm rounded-xl transition">
                                + Nouveau décorticage
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($decorticages->hasPages())
            <div class="px-5 py-4 border-t border-slate-800">{{ $decorticages->links() }}</div>
            @endif
        </div>
    </div>
</div>