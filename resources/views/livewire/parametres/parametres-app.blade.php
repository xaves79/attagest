<div class="min-h-screen bg-slate-950 text-white">

    {{-- EN-TÊTE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-500/20 border border-violet-500/30 flex items-center justify-center text-sm">⚙️</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Paramètres application</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Seuils d'alerte et taux de rendement</p>
                </div>
            </div>
            <button wire:click="sauvegarder"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-wait"
                    class="px-5 py-2 bg-violet-600 hover:bg-violet-500 active:scale-95 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                <span wire:loading.remove wire:target="sauvegarder">💾 Sauvegarder</span>
                <span wire:loading wire:target="sauvegarder">⏳ Sauvegarde...</span>
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-8 space-y-6">

        {{-- MESSAGES --}}
        @if($successMessage)
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 flex items-center gap-3 text-green-300">
            <span>✅</span><p class="text-sm font-medium">{{ $successMessage }}</p>
        </div>
        @endif
        @if($errorMessage)
        <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm">{{ $errorMessage }}</p>
        </div>
        @endif

        {{-- GROUPES --}}
        @php
            $groupeConfig = [
                'stocks'     => ['icon' => '📦', 'label' => 'Stocks & alertes',       'color' => 'amber'],
                'achats'     => ['icon' => '🌾', 'label' => 'Achats & fournisseurs',  'color' => 'orange'],
                'ventes'     => ['icon' => '🛒', 'label' => 'Ventes & clients',       'color' => 'green'],
                'production' => ['icon' => '⚙️', 'label' => 'Production & rendement', 'color' => 'blue'],
                'general'    => ['icon' => '🔧', 'label' => 'Général',                'color' => 'slate'],
            ];
            $colorMap = [
                'amber'  => 'border-amber-700/40 bg-amber-500/5',
                'orange' => 'border-orange-700/40 bg-orange-500/5',
                'green'  => 'border-green-700/40 bg-green-500/5',
                'blue'   => 'border-blue-700/40 bg-blue-500/5',
                'slate'  => 'border-slate-700/40 bg-slate-800/30',
            ];
            $headerMap = [
                'amber'  => 'text-amber-400',
                'orange' => 'text-orange-400',
                'green'  => 'text-green-400',
                'blue'   => 'text-blue-400',
                'slate'  => 'text-slate-400',
            ];
        @endphp

        @foreach($groupes as $groupe => $params)
        @php
            $cfg   = $groupeConfig[$groupe] ?? $groupeConfig['general'];
            $color = $cfg['color'];
        @endphp
        <div class="bg-slate-900 border {{ $colorMap[$color] }} rounded-2xl overflow-hidden">

            {{-- En-tête groupe --}}
            <div class="px-6 py-4 border-b border-slate-700/60 flex items-center gap-3">
                <span class="text-lg">{{ $cfg['icon'] }}</span>
                <h2 class="text-sm font-bold {{ $headerMap[$color] }} uppercase tracking-wider">{{ $cfg['label'] }}</h2>
            </div>

            {{-- Paramètres --}}
            <div class="divide-y divide-slate-800/60">
                @foreach($params as $param)
                <div class="px-6 py-4 flex items-center justify-between gap-6">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white">{{ $param['label'] }}</p>
                        @if($param['description'])
                        <p class="text-xs text-slate-500 mt-0.5">{{ $param['description'] }}</p>
                        @endif
                        <p class="text-xs text-slate-600 mt-0.5 font-mono">{{ $param['cle'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @php
                            $unite = match(true) {
                                str_contains($param['cle'], '_kg')     => 'kg',
                                str_contains($param['cle'], 'jours')   => 'jours',
                                str_contains($param['cle'], 'taux') || str_contains($param['cle'], 'rendement') => '%',
                                default => ''
                            };
                        @endphp
                        <div class="relative">
                            <input
                                type="{{ $param['type'] === 'boolean' ? 'checkbox' : 'number' }}"
                                wire:model="parametres.{{ $param['id'] }}.valeur"
                                step="{{ $param['type'] === 'decimal' ? '0.1' : '1' }}"
                                min="0"
                                class="w-28 bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2 text-sm text-right
                                       focus:outline-none focus:border-violet-500 transition
                                       {{ $unite ? 'pr-10' : '' }}"
                            >
                            @if($unite)
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500 pointer-events-none">
                                {{ $unite }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Bouton bas de page --}}
        <div class="flex justify-end pb-8">
            <button wire:click="sauvegarder"
                    wire:loading.attr="disabled"
                    class="px-8 py-3 bg-violet-600 hover:bg-violet-500 active:scale-95 text-white font-bold rounded-xl transition-all shadow-lg shadow-violet-900/30">
                💾 Sauvegarder tous les paramètres
            </button>
        </div>

    </div>
</div>