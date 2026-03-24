<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bannière -->
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-slate-100">
                <h2 class="text-2xl font-bold text-emerald-400 mb-2">
                    Dashboard Achats & Approvisionnement
                </h2>
                <p class="text-slate-300">
                    Suivi des achats de Paddy, des fournisseurs et des réceptions.
                </p>
            </div>
        </div>

        <!-- KPI Achats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Montant total des achats -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Montant total achats</h3>
                <p class="text-2xl font-bold text-emerald-400">
                    {{ number_format($montantTotalAchats ?? 0, 0, ',', ' ') }} F CFA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Depuis le début de l’année
                </p>
            </div>

            <!-- Nombre de réceptions fournisseurs -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Réceptions fournisseurs</h3>
                <p class="text-2xl font-bold text-emerald-400">
                    {{ number_format($nombreRecus ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Nombre total de réceptions
                </p>
            </div>

            <!-- Fournisseurs actifs -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Fournisseurs actifs</h3>
                <p class="text-2xl font-bold text-emerald-400">
                    {{ number_format($fournisseursActifs ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Ayant au moins une réception
                </p>
            </div>

            <!-- Moyenne par achat -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-5 shadow">
                <h3 class="text-sm font-medium text-slate-300 mb-1">Moyenne par achat</h3>
                @php
                    $avg = $montantTotalAchats && $nombreRecus
                        ? $montantTotalAchats / max(1, $nombreRecus)
                        : 0;
                @endphp
                <p class="text-2xl font-bold text-emerald-400">
                    {{ number_format($avg, 0, ',', ' ') }} F CFA
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Montant moyen par réception
                </p>
            </div>
        </div>

        <!-- Graphique Évolution des achats Paddy -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-100 mb-4">
                    Évolution des achats de Paddy mensuels
                </h3>
                <div class="h-64 w-full">
                    <canvas id="achatsChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <!-- Derniers achats Paddy -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-100 mb-4">
                    Derniers achats de Paddy
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="px-4 py-2 text-left text-slate-300">N° Achat</th>
                                <th class="px-4 py-2 text-left text-slate-300">Fournisseur</th>
                                <th class="px-4 py-2 text-left text-slate-300">Date</th>
                                <th class="px-4 py-2 text-right text-slate-300">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($derniersAchats as $achat)
                                <tr class="border-b border-slate-700 hover:bg-slate-700">
                                    <td class="px-4 py-2">{{ $achat->code_lot }}</td>
                                    <td class="px-4 py-2">{{ $achat->fournisseur?->nom ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $achat->date_achat?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($achat->montant_achat_total_fcfa, 0, ',', ' ') }} F CFA
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-slate-400">
                                        Aucun achat trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dernières réceptions fournisseurs -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-100 mb-4">
                    Dernières réceptions fournisseurs
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="px-4 py-2 text-left text-slate-300">N° Réception</th>
                                <th class="px-4 py-2 text-left text-slate-300">Fournisseur</th>
                                <th class="px-4 py-2 text-left text-slate-300">Date</th>
                                <th class="px-4 py-2 text-right text-slate-300">Quantité (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($derniersRecus as $recu)
                                <tr class="border-b border-slate-700 hover:bg-slate-700">
                                    <td class="px-4 py-2">{{ $recu->numero_recu }}</td>
                                    <td class="px-4 py-2">{{ $recu->fournisseur?->nom ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $recu->date_recu?->format('d/m/Y') ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($recu->quantite_kg ?? 0, 0, ',', ' ') }} kg
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-slate-400">
                                        Aucune réception trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('achatsChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($achatsLabels),
                        datasets: [{
                            label: 'Achats de Paddy mensuels (F CFA)',
                            data: @json($achatsData),
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: '#3b82f6',
                            borderWidth: 1
                        }]
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
