<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-500/20 border border-green-500/30 flex items-center justify-center text-sm">🍚</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Stocks produits finis</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Riz blanc · Son · Brisures · Rejets</p>
                </div>
            </div>
            <a href="{{ route('decorticages.nouvelle') }}"
               class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouveau décorticage
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- KPIs PAR TYPE --}}
        @php
            $typesConfig = [
                'riz_blanc' => ['icon' => '🍚', 'label' => 'Riz blanc',  'color' => 'green',  'seuil_key' => 'seuil_stock_riz_blanc_kg'],
                'son'       => ['icon' => '🟤', 'label' => 'Son de riz', 'color' => 'amber',  'seuil_key' => 'seuil_stock_son_kg'],
                'brisures'  => ['icon' => '💛', 'label' => 'Brisures',   'color' => 'yellow', 'seuil_key' => 'seuil_stock_brisures_kg'],
                'rejet'     => ['icon' => '🗑️', 'label' => 'Rejets',     'color' => 'slate',  'seuil_key' => null],
            ];
            $colorText   = ['green' => 'text-green-400', 'amber' => 'text-amber-400', 'yellow' => 'text-yellow-400', 'slate' => 'text-slate-400'];
            $colorBorder = ['green' => 'border-green-700/30', 'amber' => 'border-amber-700/30', 'yellow' => 'border-yellow-700/30', 'slate' => 'border-slate-700/60'];
            $colorBar    = ['green' => 'bg-green-500', 'amber' => 'bg-amber-500', 'yellow' => 'bg-yellow-500', 'slate' => 'bg-slate-500'];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($typesConfig as $type => $cfg)
            @php
                $kpi      = $kpis->get($type);
                $totalKg  = (float)($kpi?->total_kg ?? 0);
                $nbLots   = (int)($kpi?->nb_lots ?? 0);
                $seuilKg  = $cfg['seuil_key'] ? (float)($seuils[$cfg['seuil_key']] ?? 0) : 0;
                $critique = $seuilKg > 0 && $totalKg < $seuilKg;
                $pct      = $seuilKg > 0 ? min(100, round($totalKg / $seuilKg * 100)) : 100;
            @endphp
            <div class="bg-slate-900 border {{ $critique ? 'border-red-700/50' : $colorBorder[$cfg['color']] }} rounded-2xl p-5 cursor-pointer hover:bg-slate-800/40 transition"
                 wire:click="$set('filtreType', '{{ $type }}')">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg">{{ $cfg['icon'] }}</span>
                    @if($critique)
                    <span class="px-2 py-0.5 bg-red-900/50 border border-red-700/50 rounded-full text-xs text-red-300 font-semibold">⚠️ Critique</span>
                    @else
                    <span class="text-xs text-slate-600">{{ $nbLots }} lot(s)</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">{{ $cfg['label'] }}</p>
                <p class="text-2xl font-black {{ $critique ? 'text-red-400' : $colorText[$cfg['color']] }}">
                    {{ number_format($totalKg, 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span>
                </p>
                @if($seuilKg > 0)
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-slate-600 mb-1">
                        <span>Seuil : {{ number_format($seuilKg, 0, ',', ' ') }} kg</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-1.5">
                        <div class="{{ $critique ? 'bg-red-500' : $colorBar[$cfg['color']] }} h-1.5 rounded-full transition-all"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- FILTRES --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap gap-3">
            <div class="flex-1 min-w-48">
                <input type="text" wire:model.live.debounce.300ms="recherche"
                       placeholder="🔍 Code stock, variété, décorticage..."
                       class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
            </div>
            <select wire:model.live="filtreType"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                <option value="">Tous types</option>
                <option value="riz_blanc">🍚 Riz blanc</option>
                <option value="son">🟤 Son</option>
                <option value="brisures">💛 Brisures</option>
                <option value="rejet">🗑️ Rejets</option>
            </select>
            <select wire:model.live="filtreStatut"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                <option value="disponible">Disponible</option>
                <option value="">Tous statuts</option>
                <option value="epuise">Épuisé</option>
                <option value="reserve">Réservé</option>
            </select>
            @if($filtreType)
            <button wire:click="$set('filtreType', '')"
                    class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs rounded-xl transition">
                ✕ Réinitialiser
            </button>
            @endif
        </div>

        {{-- TABLEAU --}}
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Code stock</th>
                        <th class="text-left px-5 py-3">Type</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-left px-5 py-3">Origine</th>
                        <th class="text-right px-5 py-3">Quantité</th>
                        <th class="text-center px-5 py-3">Statut</th>
                        <th class="text-left px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($stocks as $s)
                    @php
                        $typeLabel = ['riz_blanc' => ['🍚 Riz blanc', 'text-green-400'], 'son' => ['🟤 Son', 'text-amber-400'], 'brisures' => ['💛 Brisures', 'text-yellow-400'], 'rejet' => ['🗑️ Rejet', 'text-slate-400']];
                        [$tLabel, $tColor] = $typeLabel[$s->type_produit] ?? [$s->type_produit, 'text-slate-400'];
                    @endphp
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs font-bold text-slate-300">{{ $s->code_stock }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold {{ $tColor }}">{{ $tLabel }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $s->variete_nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs">
                            @if($s->code_decorticage)
                            <span class="font-mono text-purple-400">{{ $s->code_decorticage }}</span>
                            @else
                            <span class="text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-bold
                            {{ $s->quantite_kg > 0 ? 'text-white' : 'text-slate-600' }}">
                            {{ number_format($s->quantite_kg, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $statutBadge = [
                                    'disponible' => 'bg-green-900/50 text-green-300 border-green-700/50',
                                    'epuise'     => 'bg-slate-800 text-slate-500 border-slate-700',
                                    'reserve'    => 'bg-amber-900/50 text-amber-300 border-amber-700/50',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs border {{ $statutBadge[$s->statut] ?? 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                {{ ucfirst($s->statut) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-slate-500 text-xs">
                            {{ $s->created_at ? \Carbon\Carbon::parse($s->created_at)->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">🍚</p>
                            <p class="text-sm">Aucun stock trouvé.</p>
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