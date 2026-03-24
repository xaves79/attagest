<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bannière -->
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-slate-100">
                <h2 class="text-2xl font-bold text-emerald-400 mb-2">
                    Dashboard Financier
                </h2>
                <p class="text-slate-300">
                    Chiffre d’affaires, marges et coûts d’achats.
                </p>
            </div>
        </div>

        <!-- KPI Financiers -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Chiffre d'affaires -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Chiffre d’affaires</h3>
                <p class="text-2xl font-bold text-emerald-400">
                    {{ number_format($ca ?? 0, 0, ',', ' ') }} F CFA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Ventes totales
                </p>
            </div>

            <!-- Coûts d'achats -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Coûts d’achats</h3>
                <p class="text-2xl font-bold text-red-400">
                    {{ number_format($coutAchats ?? 0, 0, ',', ' ') }} F CFA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Achats Paddy
                </p>
            </div>

            <!-- Marge brute -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Marge brute</h3>
                <p class="text-2xl font-bold {{ $marge >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ number_format($marge ?? 0, 0, ',', ' ') }} F CFA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    CA − Coûts
                </p>
            </div>
        </div>

        <!-- Graphique Évolution mensuelle -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-100 mb-4">
                    Évolution mensuelle (CA et coûts)
                </h3>
                <div class="h-64 w-full">
                    <canvas id="financesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('financesChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($prodLabels),
                        datasets: [
                            {
                                label: 'Chiffre d’affaires (F CFA)',
                                data: @json($caData),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: false
                            },
                            {
                                label: 'Coûts d’achats (F CFA)',
                                data: @json($coutData),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: '#e2e8f0' } }
                        },
                        scales: {
                            x: { ticks: { color: '#94a3b8' } },
                            y: { ticks: { color: '#94a3b8' } }
                        }
                    }
                });
            }
        });
    </script>
@endpush
