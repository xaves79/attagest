<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Bannière -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6 text-white">
                <h2 class="text-2xl font-bold mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"></path>
                    </svg>
                    🏢 Dashboard Entreprise
                </h2>
                <p class="opacity-90">Vue d’ensemble des acteurs de l’entreprise</p>
            </div>
        </div>

        <!-- Filtre période -->
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 shadow mb-8">
            <div class="flex items-center mb-4">
                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm text-slate-300">Période</span>
            </div>
            <select wire:model.live="periode" class="w-full text-sm bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="mois">📅 Ce mois</option>
                <option value="trimestre">📊 Ce trimestre</option>
                <option value="an">📈 Cette année</option>
            </select>
        </div>

        <!-- KPIs alignés sur une seule ligne sur grand écran -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Agents -->
    <div class="bg-slate-800 border-2 border-blue-500/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between min-h-48">
        <div>
            <div class="flex items-center mb-3">
                <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                <h3 class="text-sm font-semibold text-slate-300">Agents</h3>
            </div>
            <p class="text-3xl font-bold text-blue-400">{{ number_format($totalAgents) }}</p>
        </div>
    </div>

    <!-- Clients -->
    <div class="bg-slate-800 border-2 border-emerald-500/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between min-h-48">
        <div>
            <div class="flex items-center mb-3">
                <div class="w-3 h-3 bg-emerald-400 rounded-full mr-3"></div>
                <h3 class="text-sm font-semibold text-slate-300">Clients</h3>
            </div>
            <p class="text-3xl font-bold text-emerald-400">{{ number_format($totalClients) }}</p>
        </div>
        <p class="text-xs text-slate-400 mt-1">
            +{{ number_format($nouveauxClients) }} ce {{ $periode }}
        </p>
    </div>

    <!-- Fournisseurs -->
    <div class="bg-slate-800 border-2 border-orange-500/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between min-h-48">
        <div>
            <div class="flex items-center mb-3">
                <div class="w-3 h-3 bg-orange-400 rounded-full mr-3"></div>
                <h3 class="text-sm font-semibold text-slate-300">Fournisseurs</h3>
            </div>
            <p class="text-3xl font-bold text-orange-400">{{ number_format($totalFournisseurs) }}</p>
        </div>
        <p class="text-xs text-slate-400 mt-1">
            +{{ number_format($nouveauxFournisseurs) }} ce {{ $periode }}
        </p>
    </div>

    <!-- Points de vente -->
    <div class="bg-slate-800 border-2 border-purple-500/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between min-h-48">
        <div>
            <div class="flex items-center mb-3">
                <div class="w-3 h-3 bg-purple-400 rounded-full mr-3"></div>
                <h3 class="text-sm font-semibold text-slate-300">Points de vente</h3>
            </div>
            <p class="text-3xl font-bold text-purple-400">{{ number_format($totalPointsVente) }}</p>
        </div>
    </div>

    <!-- Localités -->
    <div class="bg-slate-800 border-2 border-teal-500/50 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all flex flex-col justify-between min-h-48">
        <div>
            <div class="flex items-center mb-3">
                <div class="w-3 h-3 bg-teal-400 rounded-full mr-3"></div>
                <h3 class="text-sm font-semibold text-slate-300">Localités</h3>
            </div>
            <p class="text-3xl font-bold text-teal-400">{{ number_format($totalLocalites) }}</p>
        </div>
    </div>
</div>


        <!-- Répartition clients par localité -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl mb-8">
            <div class="p-6">
                <h3 class="text-xl font-bold text-slate-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Clients par localité
                </h3>
                <div class="overflow-x-auto rounded-lg border border-slate-700">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-slate-300 font-semibold">Localité</th>
                                <th class="px-6 py-4 text-right text-slate-300 font-semibold">Clients</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($localites as $localite)
                                @php
                                    $total = $clientsParLocalite[$localite->id]?->total ?? 0;
                                @endphp
                                <tr class="hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-200">{{ $localite->nom }}</td>
                                    <td class="px-6 py-4 text-right font-bold {{ $total > 0 ? 'text-emerald-400' : 'text-slate-400' }}">
                                        {{ number_format($total) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
