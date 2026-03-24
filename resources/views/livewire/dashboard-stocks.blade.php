<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bannière -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-white">
                <h2 class="text-2xl font-bold mb-2">📦 Stocks & Réservoirs</h2>
                <p class="opacity-90">Vue d'ensemble temps réel - Mise à jour automatique</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 shadow mb-8">
            <h3 class="text-lg font-semibold text-slate-200 mb-4">Filtres</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">📅 Période</label>
                    <select wire:model.live="periode" class="w-full text-sm bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        <option value="tous">🟢 Tous les stocks</option>
                        <option value="mois">📅 Ce mois</option>
                        <option value="trimestre">📊 Ce trimestre</option>
                        <option value="annee">📈 Cette année</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">🌾 Variété</label>
                    <select wire:model.live="variete_id" class="w-full text-sm bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        <option value="">Toutes les variétés</option>
                        @foreach ($varietes as $variete)
                            <option value="{{ $variete->id }}">{{ $variete->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">📦 Type produit</label>
                    <select wire:model.live="type_produit" class="w-full text-sm bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        <option value="">Tous les types</option>
                        <option value="riz_blanchi">Riz blanchi</option>
                        <option value="riz_rejet">Riz rejet</option>
                        <option value="riz_brisure">Riz brisure</option>
                        <option value="son_de_riz">Son de riz</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- KPI Principaux (UNIQUEMENT ici le poll) -->
        <div wire:poll.10s class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mr-3"></div>
                    <h3 class="text-sm font-semibold text-slate-300">Stock Paddy</h3>
                </div>
                <p class="text-3xl font-bold {{ $stockPaddyKg < $seuilPaddy ? 'text-red-400' : 'text-emerald-400' }}">
                    {{ number_format($stockPaddyKg ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal">kg</span>
                </p>
                <p class="text-xs text-slate-400 mt-2">Seuil alerte: {{ number_format($seuilPaddy, 0, ',', ' ') }} kg</p>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-3 h-3 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full mr-3"></div>
                    <h3 class="text-sm font-semibold text-slate-300">Produits finis</h3>
                </div>
                <p class="text-3xl font-bold {{ $stockProduitsFinisKg < $seuilProduitsFinis ? 'text-red-400' : 'text-emerald-400' }}">
                    {{ number_format($stockProduitsFinisKg ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal">kg</span>
                </p>
                <p class="text-xs text-slate-400 mt-2">Seuil alerte: {{ number_format($seuilProduitsFinis, 0, ',', ' ') }} kg</p>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-3 h-3 bg-gradient-to-r from-orange-400 to-red-500 rounded-full mr-3"></div>
                    <h3 class="text-sm font-semibold text-slate-300">Riz Étuvé</h3>
                </div>
                <p class="text-3xl font-bold {{ $stockRizEtuveKg < $seuilRizEtuve ? 'text-red-400 animate-pulse' : 'text-emerald-400' }}">
                    {{ number_format($stockRizEtuveKg ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal">kg</span>
                </p>
                <p class="text-xs text-slate-400 mt-2">Seuil alerte: {{ number_format($seuilRizEtuve, 0, ',', ' ') }} kg</p>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full mr-3"></div>
                    <h3 class="text-sm font-semibold text-slate-300">Capacité réservoirs</h3>
                </div>
                <p class="text-3xl font-bold text-blue-400">
                    {{ number_format($capaciteReservoirsKg ?? 0, 0, ',', ' ') }} <span class="text-sm font-normal">kg</span>
                </p>
            </div>
        </div>

        <!-- TOTAUX PAR TYPE (brisure, rejet, son) -->
        <div wire:poll.10s class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @foreach($totauxParType as $type => $total)
                <div class="bg-slate-800 border-2 {{ $total < 100 ? 'border-red-500/50' : 'border-emerald-500/50' }} rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center mb-3">
                        <div class="w-4 h-4 {{ $total < 100 ? 'bg-red-400' : 'bg-emerald-400' }} rounded-full mr-3"></div>
                        <h3 class="text-sm font-semibold text-slate-300">{{ ucwords(str_replace('_', ' ', $type)) }}</h3>
                    </div>
                    <p class="text-2xl font-bold {{ $total < 100 ? 'text-red-400' : 'text-emerald-400' }}">
                        {{ number_format($total ?? 0, 0, ',', ' ') }} kg
                    </p>
                    @if($total < 100)
                        <p class="text-xs text-red-400 mt-1 font-medium">⚠️ Stock critique</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Tableau détaillé avec pagination -->
<div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl mb-8">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-100">📋 Stocks produits finis par variété</h3>
            <span class="text-sm text-slate-400">
                {{ $stocksProduitsFinis->total() }} lignes
            </span>
        </div>

        @if($stocksProduitsFinis->isEmpty())
            <div class="text-center py-12 text-slate-500 bg-slate-700/50 rounded-lg">
                <div class="text-4xl mb-4">📭</div>
                <h4 class="text-lg font-semibold mb-2">Aucun stock trouvé</h4>
                <p>Avec les filtres actuels ({{ $periode ?? 'tous' }})</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-slate-700">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-slate-300 font-semibold">Variété</th>
                            <th class="px-6 py-4 text-left text-slate-300 font-semibold">Type</th>
                            <th class="px-6 py-4 text-right text-slate-300 font-semibold">Quantité</th>
                            <th class="px-6 py-4 text-right text-slate-300 font-semibold">État</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach ($stocksProduitsFinis as $stock)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-200">
                                    {{ $stock->varieteRice->nom ?? 'Non spécifiée' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $stock->type_produit == 'riz_blanc' ? 'bg-white/10 text-white border border-white/20' : '' }}
                                        {{ $stock->type_produit == 'riz_rejet' ? 'bg-orange-500/20 text-orange-300 border border-orange-500/30' : '' }}
                                        {{ $stock->type_produit == 'brisures' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' : '' }}
                                        {{ $stock->type_produit == 'son' ? 'bg-slate-500/20 text-slate-300 border border-slate-500/30' : '' }}">
                                        {{ ucwords(str_replace('_', ' ', $stock->type_produit)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-lg font-bold text-slate-200">
                                    {{ number_format($stock->quantite_kg ?? 0, 2, ',', ' ') }} kg
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($stock->quantite_kg < 100)
                                        <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-red-500/20 text-red-400 border-2 border-red-500/30 animate-pulse">
                                            ⚠️ {{ number_format($stock->quantite_kg, 2) }} kg (CRITIQUE)
                                        </span>
                                    @elseif($stock->quantite_kg < 500)
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-amber-500/20 text-amber-400 border-2 border-amber-500/30">
                                            ⚠️ Faible
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-400 border-2 border-emerald-500/30">
                                            ✅ Bon stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $stocksProduitsFinis->links() }}
                </div>
            </div>
        @endif
    </div>
</div>


        {{-- Graphique SANS poll + canvas isolé --}}
		<div class="bg-slate-800 border border-slate-700 rounded-xl shadow-2xl" wire:key="chart-container">
			<div class="p-8">
				<h3 class="text-xl font-bold text-slate-100 mb-6 flex items-center">
					📈 Évolution Stock Paddy (6 derniers mois)
					<span class="ml-3 px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded-full font-semibold">
						Stable
					</span>
				</h3>
				<div class="h-96 bg-slate-900/50 rounded-xl border border-slate-700 p-4">
					<canvas id="stocksChart" wire:ignore class="w-full h-full"></canvas>
				</div>
				<div class="mt-4 text-center text-xs text-slate-500">
					Données: {{ implode(', ', array_map(fn($v) => number_format($v), $evolutionStocks ?? [])) }} kg
				</div>
			</div>
		</div>

		@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
window.livewireCharts = window.livewireCharts || {};

function initChart() {
    const canvas = document.getElementById('stocksChart');
    if (!canvas || window.livewireCharts.stocksChart) return;
    
    const ctx = canvas.getContext('2d');
    
    {{-- CORRECTION : Sépare PHP et JS --}}
    const evolutionData = {!! json_encode($evolutionStocks ?? [12000, 13500, 11000, 14000, 15500, 13000]) !!};

    window.livewireCharts.stocksChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Stock Paddy (kg)',
                data: evolutionData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: { labels: { color: '#e2e8f0', padding: 20 } }
            },
            scales: {
                x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(0,0,0,0.3)' } },
                y: { 
                    ticks: { 
                        color: '#94a3b8',
                        callback: value => value.toLocaleString('fr-FR') + ' kg'
                    },
                    grid: { color: 'rgba(0,0,0,0.3)' }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', initChart);
Livewire.hook('morph.updated', () => {
    if (window.livewireCharts.stocksChart) {
        window.livewireCharts.stocksChart.destroy();
        window.livewireCharts.stocksChart = null;
    }
    setTimeout(initChart, 200);
});
</script>
@endpush

