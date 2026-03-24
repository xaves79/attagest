<div class="min-h-screen bg-slate-950 text-white">

    {{-- EN-TÊTE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-sm">🏭</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Dashboard Production</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Étuvage · Décorticage · Stocks produits finis</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @foreach(['semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $val => $label)
                <button wire:click="$set('periode', '{{ $val }}')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                        {{ $periode === $val ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- ALERTES STOCKS CRITIQUES --}}
        @if(count($alertes) > 0)
        <div class="bg-red-950/40 border border-red-700/40 rounded-2xl p-4 flex flex-wrap gap-3 items-center">
            <span class="text-red-400 font-bold text-sm flex items-center gap-2">⚠️ Stocks critiques :</span>
            @foreach($alertes as $alerte)
            <span class="px-3 py-1 bg-red-900/50 border border-red-700/50 rounded-lg text-xs text-red-300">
                {{ $alerte['label'] }} : {{ number_format($alerte['stock'], 0, ',', ' ') }} kg
                <span class="text-red-500">(seuil : {{ number_format($alerte['seuil'], 0, ',', ' ') }} kg)</span>
            </span>
            @endforeach
            <a href="{{ route('parametres.index') }}" class="ml-auto text-xs text-red-400 hover:text-red-300 underline">Modifier les seuils →</a>
        </div>
        @endif

        {{-- EN COURS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-blue-700/30 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/15 flex items-center justify-center text-xl flex-shrink-0">🔥</div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Étuvages en cours</p>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-2xl font-black text-white">{{ $kpisGlobaux['etuvages_en_cours'] }}</p>
                        @if($kpisGlobaux['etuvages_en_cours'] > 0)
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-purple-700/30 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/15 flex items-center justify-center text-xl flex-shrink-0">⚙️</div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Décorticages en cours</p>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-2xl font-black text-white">{{ $kpisGlobaux['decorticages_en_cours'] }}</p>
                        @if($kpisGlobaux['decorticages_en_cours'] > 0)
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center text-xl flex-shrink-0">📦</div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Lots étuvés dispo</p>
                    <p class="text-2xl font-black text-amber-400 mt-1">{{ $kpisGlobaux['lots_riz_etuve_dispo'] }}</p>
                </div>
            </div>
            <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-green-500/15 flex items-center justify-center text-xl flex-shrink-0">🍚</div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Stock riz blanc</p>
                    <p class="text-2xl font-black text-green-400 mt-1">{{ number_format($kpisGlobaux['stock_riz_blanc_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                </div>
            </div>
        </div>

        {{-- STOCKS PRODUITS FINIS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $produitsStock = [
                    ['label' => '🍚 Riz blanc', 'kg' => $kpisGlobaux['stock_riz_blanc_kg'], 'color' => 'green'],
                    ['label' => '🟤 Son de riz', 'kg' => $kpisGlobaux['stock_son_kg'],       'color' => 'amber'],
                    ['label' => '💛 Brisures',   'kg' => $kpisGlobaux['stock_brisures_kg'],  'color' => 'yellow'],
                    ['label' => '🗑️ Rejets',     'kg' => $kpisGlobaux['stock_rejet_kg'],    'color' => 'slate'],
                ];
                $totalStock = array_sum(array_column($produitsStock, 'kg'));
            @endphp
            @foreach($produitsStock as $p)
            @php
                $pct = $totalStock > 0 ? round($p['kg'] / $totalStock * 100) : 0;
                $colorMap = ['green' => 'text-green-400 bg-green-500', 'amber' => 'text-amber-400 bg-amber-500', 'yellow' => 'text-yellow-400 bg-yellow-500', 'slate' => 'text-slate-400 bg-slate-500'];
                $borderMap = ['green' => 'border-green-700/30', 'amber' => 'border-amber-700/30', 'yellow' => 'border-yellow-700/30', 'slate' => 'border-slate-700/60'];
            @endphp
            <div class="bg-slate-900 border {{ $borderMap[$p['color']] }} rounded-2xl p-5">
                <p class="text-sm font-semibold text-white mb-3">{{ $p['label'] }}</p>
                <p class="text-3xl font-black {{ explode(' ', $colorMap[$p['color']])[0] }}">
                    {{ number_format($p['kg'], 0, ',', ' ') }}<span class="text-base font-normal text-slate-500 ml-1">kg</span>
                </p>
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Part du stock total</span><span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-1.5">
                        <div class="{{ explode(' ', $colorMap[$p['color']])[1] }} h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- KPIs PÉRIODE --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider mb-5">📊 Performance de la période</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-slate-800/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Paddy étuvé</p>
                    <p class="text-2xl font-black text-blue-400">{{ number_format($kpisPeriode['paddy_etuve_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1">{{ $kpisPeriode['nb_etuvages'] }} étuvage(s)</p>
                </div>
                <div class="bg-slate-800/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz étuvé produit</p>
                    <p class="text-2xl font-black text-blue-300">{{ number_format($kpisPeriode['riz_etuve_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1">Rendement ref. : {{ number_format($kpisPeriode['rendement_etuvage'] ?? 0, 1) }}%</p>
                </div>
                <div class="bg-slate-800/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz blanc produit</p>
                    <p class="text-2xl font-black text-green-400">{{ number_format($kpisPeriode['riz_blanc_produit_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1">{{ $kpisPeriode['nb_decorticages'] }} décorticage(s)</p>
                </div>
                <div class="bg-slate-800/50 rounded-xl p-4 text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Rendement décorti.</p>
                    <p class="text-2xl font-black {{ ($kpisPeriode['rendement_decorticage'] ?? 0) >= 60 ? 'text-green-400' : 'text-amber-400' }}">
                        {{ number_format($kpisPeriode['rendement_decorticage'] ?? 0, 1) }}<span class="text-base font-normal text-slate-500 ml-1">%</span>
                    </p>
                    <p class="text-xs text-slate-600 mt-1">Son : {{ number_format($kpisPeriode['son_produit_kg'], 0, ',', ' ') }} kg</p>
                </div>
            </div>
        </div>

        {{-- TABLEAUX RÉCENTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Étuvages récents --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700/60 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white">🔥 Derniers étuvages</h3>
                    <a href="{{ route('etuvages.index') }}" class="text-xs text-blue-400 hover:text-blue-300 transition">Voir tout →</a>
                </div>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-slate-500 uppercase tracking-wider border-b border-slate-800">
                            <th class="text-left px-5 py-2.5">Code</th>
                            <th class="text-left px-5 py-2.5">Variété</th>
                            <th class="text-right px-5 py-2.5">Entrée</th>
                            <th class="text-right px-5 py-2.5">Sortie</th>
                            <th class="text-right px-5 py-2.5">Rdt</th>
                            <th class="text-center px-5 py-2.5">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($etuvagesRecents as $e)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-2.5 font-mono text-blue-400 font-semibold">{{ $e->code_etuvage }}</td>
                            <td class="px-5 py-2.5 text-slate-400">{{ $e->variete_nom ?? '—' }}</td>
                            <td class="px-5 py-2.5 text-right text-slate-300">{{ number_format($e->quantite_paddy_entree_kg, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-blue-300">{{ $e->masse_apres_kg ? number_format($e->masse_apres_kg, 0, ',', ' ') : '—' }}</td>
                            <td class="px-5 py-2.5 text-right font-bold {{ ($e->rendement_pourcentage ?? 0) >= 90 ? 'text-green-400' : 'text-amber-400' }}">
                                {{ $e->rendement_pourcentage ? number_format($e->rendement_pourcentage, 1) . '%' : '—' }}
                            </td>
                            <td class="px-5 py-2.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-xs {{ $e->statut === 'termine' ? 'text-green-400' : 'text-amber-400' }}">
                                    {{ $e->statut === 'termine' ? '✓' : '⏳' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-600">Aucun étuvage</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Décorticages récents --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700/60 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white">⚙️ Derniers décorticages</h3>
                    <a href="{{ route('decorticages.index') }}" class="text-xs text-purple-400 hover:text-purple-300 transition">Voir tout →</a>
                </div>
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-slate-500 uppercase tracking-wider border-b border-slate-800">
                            <th class="text-left px-5 py-2.5">Code</th>
                            <th class="text-left px-5 py-2.5">Variété</th>
                            <th class="text-right px-5 py-2.5">Entrée</th>
                            <th class="text-right px-5 py-2.5">Blanc</th>
                            <th class="text-right px-5 py-2.5">Son</th>
                            <th class="text-right px-5 py-2.5">Rdt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($decorticagesRecents as $d)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-2.5 font-mono text-purple-400 font-semibold">{{ $d->code_decorticage }}</td>
                            <td class="px-5 py-2.5 text-slate-400">{{ $d->variete_nom ?? '—' }}</td>
                            <td class="px-5 py-2.5 text-right text-slate-300">{{ number_format($d->quantite_paddy_entree_kg, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-green-400 font-semibold">{{ $d->quantite_riz_blanc_kg > 0 ? number_format($d->quantite_riz_blanc_kg, 0, ',', ' ') : '—' }}</td>
                            <td class="px-5 py-2.5 text-right text-amber-400">{{ $d->quantite_son_kg > 0 ? number_format($d->quantite_son_kg, 0, ',', ' ') : '—' }}</td>
                            <td class="px-5 py-2.5 text-right font-bold {{ ($d->taux_rendement ?? 0) >= 60 ? 'text-green-400' : 'text-amber-400' }}">
                                {{ $d->taux_rendement > 0 ? number_format($d->taux_rendement, 1) . '%' : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-600">Aucun décorticage</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>