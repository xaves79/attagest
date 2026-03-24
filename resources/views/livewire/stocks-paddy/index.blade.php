<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-sm">🌾</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Stocks paddy</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Suivi des lots en stock</p>
                </div>
            </div>
            <a href="{{ route('achats.nouvelle') }}"
               class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouvel achat
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Lots en stock</p>
                <p class="text-3xl font-black text-white">{{ $kpis['total_lots'] }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Total acheté</p>
                <p class="text-3xl font-black text-amber-400">{{ number_format($kpis['total_kg'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Stock restant</p>
                <p class="text-3xl font-black text-green-400">{{ number_format($kpis['restant_kg'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Lots disponibles</p>
                <p class="text-3xl font-black text-white">{{ $kpis['lots_disponibles'] }}</p>
            </div>
        </div>

        {{-- FILTRES --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="🔍 Code stock, lot paddy, fournisseur, emplacement..."
                   class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
        </div>

        {{-- TABLEAU --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Code stock</th>
                        <th class="text-left px-5 py-3">Lot paddy</th>
                        <th class="text-left px-5 py-3">Fournisseur</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-left px-5 py-3">Emplacement</th>
                        <th class="text-right px-5 py-3">Initial</th>
                        <th class="text-right px-5 py-3">Restant</th>
                        <th class="text-center px-5 py-3">Statut lot</th>
                        <th class="text-left px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($stocks as $s)
                    @php
                        $pct = $s->quantite_stock_kg > 0
                            ? round($s->quantite_restante_kg / $s->quantite_stock_kg * 100)
                            : 0;
                    @endphp
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs font-bold text-amber-400">{{ $s->code_stock }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('achats.show', ['id' => DB::table('lots_paddy')->where('code_lot', $s->code_lot)->value('id')]) }}"
                               class="font-mono text-xs text-slate-300 hover:text-amber-400 transition">
                                {{ $s->code_lot ?? '—' }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $s->fournisseur_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $s->variete_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-500 text-xs">{{ $s->emplacement ?? '—' }}</td>
                        <td class="px-5 py-3 text-right text-slate-300 font-semibold">
                            {{ number_format($s->quantite_stock_kg, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <span class="font-bold {{ $pct > 50 ? 'text-green-400' : ($pct > 20 ? 'text-amber-400' : 'text-red-400') }}">
                                    {{ number_format($s->quantite_restante_kg, 0, ',', ' ') }} kg
                                </span>
                                <span class="text-xs text-slate-600">({{ $pct }}%)</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $badges = [
                                    'disponible'    => 'bg-green-900/50 text-green-300 border-green-700/50',
                                    'anticipe'      => 'bg-amber-900/50 text-amber-300 border-amber-700/50',
                                    'complet'       => 'bg-slate-800 text-slate-500 border-slate-700',
                                    'epuise'        => 'bg-slate-800 text-slate-500 border-slate-700',
                                    'en_cours'      => 'bg-blue-900/50 text-blue-300 border-blue-700/50',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs border {{ $badges[$s->lot_statut] ?? 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $s->lot_statut ?? '—')) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-slate-500 text-xs">
                            {{ $s->created_at ? \Carbon\Carbon::parse($s->created_at)->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">🌾</p>
                            <p class="text-sm">Aucun stock paddy trouvé.</p>
                            <a href="{{ route('achats.nouvelle') }}"
                               class="inline-block mt-4 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm rounded-xl transition">
                                + Enregistrer un achat
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($stocks->hasPages())
            <div class="px-5 py-4 border-t border-slate-800">{{ $stocks->links() }}</div>
            @endif
        </div>
    </div>
</div>