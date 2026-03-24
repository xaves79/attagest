<div class="min-h-screen bg-slate-950 text-white">

    {{-- EN-TÊTE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-sm">📊</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Rapports</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $label }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Périodes --}}
                @foreach(['tous' => 'Tout', 'journalier' => 'Auj.', 'hebdomadaire' => 'Semaine', 'mensuel' => 'Mois', 'annuel' => 'Année'] as $k => $l)
                <button wire:click="$set('periode', '{{ $k }}')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                        {{ $periode === $k ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                    {{ $l }}
                </button>
                @endforeach

                {{-- Exports --}}
                <div class="w-px h-5 bg-slate-700 mx-1"></div>
                <button wire:click="exportPdf" wire:loading.attr="disabled"
                        class="px-3 py-1.5 bg-red-700/60 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition flex items-center gap-1">
                    <span wire:loading.remove wire:target="exportPdf">📄 PDF</span>
                    <span wire:loading wire:target="exportPdf">⏳</span>
                </button>
                <button wire:click="exportCsv" wire:loading.attr="disabled"
                        class="px-3 py-1.5 bg-green-700/60 hover:bg-green-600 text-white text-xs font-bold rounded-lg transition flex items-center gap-1">
                    <span wire:loading.remove wire:target="exportCsv">📊 CSV</span>
                    <span wire:loading wire:target="exportCsv">⏳</span>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        {{-- SÉLECTEURS PÉRIODE --}}
        @if($periode === 'mensuel')
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex gap-4 items-end">
            <div>
                <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Année</label>
                <input type="number" wire:model.live="annee" min="2020" max="2035"
                       class="bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 w-28 transition">
            </div>
            <div>
                <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Mois</label>
                <select wire:model.live="mois"
                        class="bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 transition">
                    @foreach(['1'=>'Janvier','2'=>'Février','3'=>'Mars','4'=>'Avril','5'=>'Mai','6'=>'Juin','7'=>'Juillet','8'=>'Août','9'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Décembre'] as $n => $nom)
                    <option value="{{ $n }}">{{ $nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @elseif($periode === 'annuel')
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex gap-4 items-end">
            <div>
                <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Année</label>
                <input type="number" wire:model.live="annee" min="2020" max="2035"
                       class="bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 w-28 transition">
            </div>
        </div>
        @elseif($periode === 'hebdomadaire')
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Semaine {{ $semaine }}</label>
                <input type="range" wire:model.live="semaine" min="1" max="53"
                       class="w-full accent-indigo-500">
            </div>
        </div>
        @endif

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-indigo-900/60 to-blue-900/40 border border-indigo-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Achats paddy</p>
                <p class="text-2xl font-black text-indigo-300">{{ number_format((int)$global['paddy_achete_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                <p class="text-sm text-indigo-400 font-semibold mt-1">{{ number_format((int)$global['paddy_achete_fcfa'], 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-slate-600 mt-0.5">{{ $global['achats_count'] }} lot(s)</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-900/60 to-teal-900/40 border border-emerald-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Ventes riz</p>
                <p class="text-2xl font-black text-emerald-300">{{ number_format((int)$global['riz_vendu_kg'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                <p class="text-sm text-emerald-400 font-semibold mt-1">{{ number_format((int)$global['riz_vendu_fcfa'], 0, ',', ' ') }} FCFA</p>
                <p class="text-xs text-slate-600 mt-0.5">{{ $global['ventes_count'] }} vente(s)</p>
            </div>
            <div class="bg-gradient-to-br from-purple-900/60 to-pink-900/40 border border-purple-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Paiements reçus</p>
                <p class="text-2xl font-black text-purple-300">{{ number_format((int)$global['paiements_fcfa'], 0, ',', ' ') }}<span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
            </div>
            <div class="bg-gradient-to-br from-orange-900/60 to-red-900/40 border border-orange-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-2">Traitements</p>
                <p class="text-lg font-black text-orange-300">{{ number_format((int)$global['paddy_traite_kg'], 0, ',', ' ') }} kg</p>
                <p class="text-sm text-orange-400 font-semibold mt-1">→ {{ number_format((int)$global['riz_blanc_kg'], 0, ',', ' ') }} kg blanc</p>
            </div>
        </div>

        {{-- TABLEAUX --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Achats --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center gap-2">
                    <span class="w-2 h-5 bg-indigo-500 rounded-full"></span>
                    <h3 class="text-sm font-bold text-white">Achats Paddy ({{ $achats->count() }})</h3>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-500 border-b border-slate-800">
                            <th class="text-left px-5 py-2">Date</th>
                            <th class="text-left px-5 py-2">Fournisseur</th>
                            <th class="text-right px-5 py-2">Kg</th>
                            <th class="text-right px-5 py-2">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($achats as $a)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-2.5 text-slate-400 text-xs">{{ \Carbon\Carbon::parse($a->date_achat)->format('d/m/Y') }}</td>
                            <td class="px-5 py-2.5 text-slate-300 font-medium">{{ $a->fournisseur ?? '—' }}</td>
                            <td class="px-5 py-2.5 text-right text-indigo-400 font-semibold">{{ number_format((int)$a->qte, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-white font-bold">{{ number_format((int)$a->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-600 text-sm">Aucun achat dans cette période</td></tr>
                        @endforelse
                        @if($achats->count() > 0)
                        <tr class="bg-slate-800/50 border-t border-slate-700">
                            <td colspan="2" class="px-5 py-2.5 text-xs text-slate-500 font-bold uppercase">Total</td>
                            <td class="px-5 py-2.5 text-right text-indigo-300 font-black">{{ number_format((int)$global['paddy_achete_kg'], 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-white font-black">{{ number_format((int)$global['paddy_achete_fcfa'], 0, ',', ' ') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Ventes --}}
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center gap-2">
                    <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                    <h3 class="text-sm font-bold text-white">Ventes Riz ({{ $ventes->count() }})</h3>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-500 border-b border-slate-800">
                            <th class="text-left px-5 py-2">Date</th>
                            <th class="text-left px-5 py-2">Client</th>
                            <th class="text-right px-5 py-2">Kg</th>
                            <th class="text-right px-5 py-2">Montant</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($ventes as $v)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-2.5 text-slate-400 text-xs">{{ \Carbon\Carbon::parse($v->date_vente)->format('d/m/Y') }}</td>
                            <td class="px-5 py-2.5 text-slate-300 font-medium">{{ $v->raison_sociale ?: $v->client }}</td>
                            <td class="px-5 py-2.5 text-right text-emerald-400 font-semibold">{{ number_format((int)$v->qte, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-white font-bold">{{ number_format((int)$v->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-600 text-sm">Aucune vente dans cette période</td></tr>
                        @endforelse
                        @if($ventes->count() > 0)
                        <tr class="bg-slate-800/50 border-t border-slate-700">
                            <td colspan="2" class="px-5 py-2.5 text-xs text-slate-500 font-bold uppercase">Total</td>
                            <td class="px-5 py-2.5 text-right text-emerald-300 font-black">{{ number_format((int)$global['riz_vendu_kg'], 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-white font-black">{{ number_format((int)$global['riz_vendu_fcfa'], 0, ',', ' ') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Traitements --}}
            @if($traitements->count() > 0)
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden lg:col-span-2">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center gap-2">
                    <span class="w-2 h-5 bg-orange-500 rounded-full"></span>
                    <h3 class="text-sm font-bold text-white">Traitements Clients ({{ $traitements->count() }})</h3>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-500 border-b border-slate-800">
                            <th class="text-left px-5 py-2">Date</th>
                            <th class="text-left px-5 py-2">Client</th>
                            <th class="text-right px-5 py-2">Paddy (kg)</th>
                            <th class="text-right px-5 py-2">Riz blanc (kg)</th>
                            <th class="text-right px-5 py-2">Montant</th>
                            <th class="text-center px-5 py-2">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($traitements as $t)
                        @php $badges = ['en_attente' => 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50', 'en_cours' => 'bg-blue-900/50 text-blue-300 border-blue-700/50', 'termine' => 'bg-green-900/50 text-green-300 border-green-700/50']; @endphp
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="px-5 py-2.5 text-slate-400 text-xs">{{ \Carbon\Carbon::parse($t->date_reception)->format('d/m/Y') }}</td>
                            <td class="px-5 py-2.5 text-slate-300 font-medium">{{ $t->raison_sociale ?: $t->client }}</td>
                            <td class="px-5 py-2.5 text-right text-orange-400 font-semibold">{{ number_format((int)$t->qte_paddy, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-green-400 font-semibold">{{ number_format((int)$t->qte_blanc, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-right text-white font-bold">{{ number_format((int)$t->montant, 0, ',', ' ') }}</td>
                            <td class="px-5 py-2.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs border {{ $badges[$t->statut] ?? 'bg-slate-800 text-slate-400 border-slate-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->statut)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>
</div>