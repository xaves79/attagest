<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- En-tête + filtres période --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">📈 Dashboard Ventes</h1>
            <p class="text-slate-400 text-sm mt-1">Analyse des commandes et du chiffre d'affaires</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">

            {{-- Période --}}
            <div class="flex bg-slate-800 border border-slate-600 rounded-lg overflow-hidden">
                @foreach(['mois' => 'Mois', 'trimestre' => 'Trimestre', 'annee' => 'Année'] as $val => $label)
                    <button wire:click="$set('periode', '{{ $val }}')"
                            class="px-4 py-2 text-sm font-medium transition
                                {{ $periode === $val
                                    ? 'bg-green-600 text-white'
                                    : 'text-slate-300 hover:bg-slate-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Mois --}}
            @if($periode !== 'annee')
            <select wire:model.live="mois"
                    class="bg-slate-800 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                        {{ now()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>
            @endif

            {{-- Année --}}
            <select wire:model.live="annee"
                    class="bg-slate-800 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                @foreach(range(now()->year, now()->year - 3) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>

            {{-- Point de vente --}}
            <select wire:model.live="pointVenteId"
                    class="bg-slate-800 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                <option value="">Tous les points</option>
                @foreach($pointsVente as $pv)
                    <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- KPIs                                                          --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">CA Total</p>
            <p class="text-2xl font-bold text-green-400">
                {{ number_format($kpis['ca_total'], 0, ',', ' ') }}
            </p>
            <p class="text-slate-400 text-xs">FCFA</p>
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">Encaissé</p>
            <p class="text-2xl font-bold text-blue-400">
                {{ number_format($kpis['ca_encaisse'], 0, ',', ' ') }}
            </p>
            @if($kpis['ca_total'] > 0)
            <p class="text-slate-400 text-xs">
                {{ round(($kpis['ca_encaisse'] / $kpis['ca_total']) * 100) }}% du CA
            </p>
            @endif
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">En attente</p>
            <p class="text-2xl font-bold text-yellow-400">
                {{ number_format($kpis['ca_en_attente'], 0, ',', ' ') }}
            </p>
            <p class="text-slate-400 text-xs">
                {{ $kpis['nb_credit_ouvert'] }} crédit(s) ouvert(s)
            </p>
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">Panier moyen</p>
            <p class="text-2xl font-bold text-purple-400">
                {{ number_format($kpis['panier_moyen'], 0, ',', ' ') }}
            </p>
            <p class="text-slate-400 text-xs">FCFA / commande</p>
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">Commandes</p>
            <p class="text-2xl font-bold text-white">{{ $kpis['nb_commandes'] }}</p>
            <p class="text-slate-400 text-xs">sur la période</p>
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5">
            <p class="text-xs text-slate-400 mb-1">Livrées</p>
            <p class="text-2xl font-bold text-emerald-400">{{ $kpis['nb_livrees'] }}</p>
            <p class="text-slate-400 text-xs">
                Taux : {{ $kpis['taux_livraison'] }}%
            </p>
        </div>

        <div class="bg-slate-800 border border-slate-600 rounded-xl p-5 md:col-span-2">
            <p class="text-xs text-slate-400 mb-2">Taux de livraison</p>
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-slate-700 rounded-full h-3">
                    <div class="h-3 rounded-full bg-emerald-500 transition-all"
                         style="width: {{ $kpis['taux_livraison'] }}%"></div>
                </div>
                <span class="text-white font-bold text-lg">{{ $kpis['taux_livraison'] }}%</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Graphe CA + Répartition types                                --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Courbe CA --}}
        <div class="lg:col-span-2 bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">
                📊 Évolution du CA
                <span class="text-slate-500 font-normal ml-2">
                    {{ $periode === 'mois' ? 'par jour' : ($periode === 'trimestre' ? 'par semaine' : 'par mois') }}
                </span>
            </h3>

            @if(empty($caParJour))
                <div class="flex items-center justify-center h-40 text-slate-500">
                    Aucune donnée sur cette période
                </div>
            @else
                @php
                    $maxCa   = max(array_column($caParJour, 'ca')) ?: 1;
                    $barCount = count($caParJour);
                @endphp
                <div class="flex items-end gap-1 h-40 w-full">
                    @foreach($caParJour as $point)
                        @php $h = max(2, round(($point['ca'] / $maxCa) * 100)); @endphp
                        <div class="flex-1 flex flex-col items-center justify-end gap-1 group relative">
                            {{-- Tooltip --}}
                            <div class="absolute bottom-full mb-1 left-1/2 -translate-x-1/2
                                        bg-slate-900 border border-slate-600 rounded px-2 py-1
                                        text-xs text-white whitespace-nowrap
                                        opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                                {{ $point['label'] }}<br>
                                {{ number_format($point['ca'], 0, ',', ' ') }} FCFA
                            </div>
                            <div class="w-full bg-green-500 hover:bg-green-400 rounded-t transition"
                                 style="height: {{ $h }}%"></div>
                            @if($barCount <= 15)
                                <span class="text-slate-500 text-xs" style="font-size:9px">{{ $point['label'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-xs text-slate-500 mt-1">
                    <span>0</span>
                    <span>{{ number_format($maxCa, 0, ',', ' ') }} FCFA</span>
                </div>
            @endif
        </div>

        {{-- Répartition par type --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">🏷 Types de vente</h3>

            @if(empty($repartition))
                <div class="flex items-center justify-center h-32 text-slate-500 text-sm">
                    Aucune donnée
                </div>
            @else
                @php
                    $typeColors = [
                        'comptant'    => ['bar' => 'bg-blue-500',   'text' => 'text-blue-400'],
                        'credit'      => ['bar' => 'bg-orange-500', 'text' => 'text-orange-400'],
                        'anticipation'=> ['bar' => 'bg-purple-500', 'text' => 'text-purple-400'],
                        'gros'        => ['bar' => 'bg-cyan-500',   'text' => 'text-cyan-400'],
                    ];
                    $maxRep = max(array_column($repartition, 'ca')) ?: 1;
                @endphp
                <div class="space-y-4">
                    @foreach($repartition as $r)
                        @php
                            $colors = $typeColors[$r['type']] ?? ['bar' => 'bg-slate-500', 'text' => 'text-slate-400'];
                            $pct    = round(($r['ca'] / $caTotal) * 100);
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="{{ $colors['text'] }} font-medium capitalize">{{ $r['type'] }}</span>
                                <span class="text-slate-400 text-xs">{{ $r['nb'] }} cmd · {{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $colors['bar'] }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-white text-xs mt-0.5">
                                {{ number_format($r['ca'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Top clients + Performance points de vente                    --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Top 5 clients --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">🏆 Top 5 clients</h3>

            @if(empty($topClients))
                <p class="text-slate-500 text-sm text-center py-8">Aucun client sur cette période</p>
            @else
                <div class="space-y-3">
                    @foreach($topClients as $i => $client)
                        @php $pct = $caTotal > 0 ? round(($client['ca'] / $caTotal) * 100) : 0; @endphp
                        <div class="flex items-center gap-3">
                            <span class="text-slate-500 font-mono text-sm w-5">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between mb-1">
                                    <span class="text-white text-sm truncate font-medium">{{ $client['nom'] }}</span>
                                    <span class="text-slate-400 text-xs ml-2 whitespace-nowrap">{{ $client['nb'] }} cmd</span>
                                </div>
                                <div class="w-full bg-slate-700 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full bg-green-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                            <span class="text-green-400 text-sm font-semibold whitespace-nowrap">
                                {{ number_format($client['ca'], 0, ',', ' ') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Performance points de vente --}}
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-6">
            <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">🏪 Points de vente</h3>

            @if(empty($perfPV))
                <p class="text-slate-500 text-sm text-center py-8">Aucune donnée</p>
            @else
                <div class="space-y-3">
                    @foreach($perfPV as $pv)
                        @php $pct = $caTotal > 0 ? round(($pv['ca'] / $caTotal) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-white font-medium">{{ $pv['nom'] }}</span>
                                <span class="text-slate-400 text-xs">{{ $pv['nb'] }} cmd · {{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-blue-400 text-xs mt-0.5 font-semibold">
                                {{ number_format($pv['ca'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Crédits en retard                                            --}}
    {{-- ============================================================ --}}
    @if($creditRetard->isNotEmpty())
    <div class="bg-slate-800 border border-red-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-red-700 flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <h3 class="text-sm font-semibold text-red-400 uppercase tracking-wider">
                Crédits en retard de paiement
            </h3>
            <span class="ml-auto px-2 py-0.5 bg-red-900/50 text-red-300 text-xs rounded-full">
                {{ $creditRetard->count() }} commande(s)
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-700 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Commande</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Point de vente</th>
                        <th class="px-4 py-3 text-center">Échéance</th>
                        <th class="px-4 py-3 text-right">Retard</th>
                        <th class="px-4 py-3 text-right">Solde dû</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($creditRetard as $cmd)
                        @php $joursRetard = now()->diffInDays($cmd->date_echeance); @endphp
                        <tr class="text-slate-200 hover:bg-red-900/10">
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-white">{{ $cmd->code_commande }}</span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $cmd->client->raison_sociale ?? $cmd->client->nom . ' ' . $cmd->client->prenom }}
                            </td>
                            <td class="px-4 py-3 text-slate-400">
                                {{ $cmd->pointVente->nom ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-red-400">
                                {{ $cmd->date_echeance->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-0.5 bg-red-900/50 text-red-300 rounded-full text-xs font-semibold">
                                    {{ $joursRetard }}j
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-red-400 font-bold">
                                {{ number_format($cmd->montant_solde_fcfa, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('commandes.show', $cmd->id) }}"
                                   class="px-3 py-1 bg-slate-700 hover:bg-slate-600 text-white text-xs rounded-lg transition">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-700">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right text-slate-300 font-semibold">
                            Total en retard
                        </td>
                        <td class="px-4 py-3 text-right text-red-400 font-bold text-base">
                            {{ number_format($creditRetard->sum('montant_solde_fcfa'), 0, ',', ' ') }} FCFA
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

</div>