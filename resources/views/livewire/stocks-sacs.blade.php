<div>
    @if (session()->has('message'))
        <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">Stocks de sacs par point de vente</h2>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher (point de vente, code sac)..." class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
        </div>
        <div>
            <select wire:model.live="point_vente_id" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
                <option value="">Tous les points de vente</option>
                @foreach($pointsVente as $pv)
                    <option value="{{ $pv->id }}">{{ $pv->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button wire:click="resetFilters" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
                Réinitialiser
            </button>
        </div>
    </div>

    {{-- Filtres supplémentaires --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-gray-300">Type de sac</label>
            <select wire:model.live="filterType" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
                <option value="">Tous</option>
                @foreach($typesDisponibles as $type)
                    <option value="{{ $type }}">
                        @php
                            $typeLabel = match($type) {
                                'riz_blanc' => 'Riz blanc',
                                'brisures' => 'Brisures',
                                'rejets' => 'Rejets',
                                'son' => 'Son',
                                default => $type,
                            };
                        @endphp
                        {{ $typeLabel }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Poids (kg)</label>
            <select wire:model.live="filterPoids" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
                <option value="">Tous</option>
                @foreach($poidsDisponibles as $poids)
                    <option value="{{ $poids }}">{{ $poids }} kg</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-300">Variété</label>
            <select wire:model.live="filterVariete" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white">
                <option value="">Toutes</option>
                @foreach($varietesDisponibles as $variete)
                    <option value="{{ $variete }}">{{ $variete }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tableau des stocks --}}
    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left">Point de vente</th>
                        <th class="px-6 py-4 text-left">Code sac</th>
                        <th class="px-6 py-4 text-left">Type / Poids</th>
                        <th class="px-6 py-4 text-left">Variété</th>
                        <th class="px-6 py-4 text-right">Quantité (sacs)</th>
                        <th class="px-6 py-4 text-right">Poids total (kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse ($stocks as $stock)
                        @php
                            $quantite = $stock->quantite;
                            $rowClass = '';
                            $warningMsg = '';
                            if ($quantite <= 15) {
                                $rowClass = 'bg-red-900/30';
                                $warningMsg = '⚠️ Stock critique ! (≤15)';
                            } elseif ($quantite <= 25) {
                                $rowClass = 'bg-orange-900/30';
                                $warningMsg = '⚠️ Stock faible (≤25)';
                            } elseif ($quantite <= 35) {
                                $rowClass = 'bg-yellow-900/30';
                                $warningMsg = '⚠️ Stock bientôt épuisé (≤35)';
                            }
                        @endphp
                        <tr class="hover:bg-gray-800/50 {{ $rowClass }}" title="{{ $warningMsg }}">
                            <td class="px-6 py-4">{{ $stock->pointVente?->nom ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $stock->sac?->code_sac ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $typeLabel = match($stock->sac?->type_sac) {
                                        'riz_blanc' => 'Riz blanc',
                                        'brisures' => 'Brisures',
                                        'rejets' => 'Rejets',
                                        'son' => 'Son',
                                        default => $stock->sac?->type_sac,
                                    };
                                @endphp
                                {{ $typeLabel }} ({{ $stock->sac?->poids_sac_kg }} kg)
                            </td>
                            <td class="px-6 py-4">{{ $stock->sac?->variete_code ?? '-' }}</td>
                            <td class="px-6 py-4 text-right {{ $quantite <= 15 ? 'text-red-400 font-bold' : '' }}">
                                @if($quantite <= 15)
                                    <span class="inline-flex items-center gap-1">
                                        ⚠️ {{ $quantite }}
                                    </span>
                                @else
                                    {{ $quantite }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">{{ number_format(($stock->sac?->poids_sac_kg ?? 0) * $stock->quantite, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Aucun stock trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $stocks->links() }}
    </div>
</div>