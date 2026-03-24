<div class="min-h-screen bg-slate-950 text-white">

    {{-- BARRE SUPÉRIEURE --}}
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60 shadow-xl">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center gap-4">
            <a href="{{ route('achats.index') }}"
               class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm group">
                <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span> Retour
            </a>
            <div class="h-5 w-px bg-slate-700"></div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-sm">🌾</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Nouvel achat paddy</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Enregistrement lot + reçu fournisseur</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-8">

        {{-- STEPPER --}}
        <div class="flex items-center gap-0 mb-10">
            @foreach([1 => 'Lot paddy', 2 => 'Paiement', 3 => 'Confirmation'] as $n => $label)
            <div class="flex items-center {{ $n < 3 ? 'flex-1' : '' }}">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition
                        {{ $etape > $n ? 'bg-amber-500 border-amber-500 text-white' :
                           ($etape === $n ? 'border-amber-500 text-amber-400 bg-amber-500/10' :
                           'border-slate-700 text-slate-600 bg-slate-900') }}">
                        {{ $etape > $n ? '✓' : $n }}
                    </div>
                    <span class="text-xs font-medium {{ $etape >= $n ? 'text-white' : 'text-slate-600' }}">{{ $label }}</span>
                </div>
                @if($n < 3)
                <div class="flex-1 h-px mx-4 {{ $etape > $n ? 'bg-amber-500' : 'bg-slate-700' }}"></div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- MESSAGES --}}
        @if($successMessage)
        <div class="mb-6 bg-green-950/60 border border-green-500/40 rounded-2xl p-5 flex items-start gap-4">
            <span class="text-2xl">✅</span>
            <div>
                <p class="font-bold text-green-300">{{ $successMessage }}</p>
                @if($codeLot)
                <p class="text-green-400 text-sm mt-1">Lot : <span class="font-mono font-bold">{{ $codeLot }}</span>
                    @if($numeroRecu) · Reçu : <span class="font-mono font-bold">{{ $numeroRecu }}</span>@endif
                </p>
                @endif
                <div class="mt-3 flex gap-3">
                    <button wire:click="$set('successMessage', '')"
                            class="px-4 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-sm rounded-lg transition">
                        + Nouvel achat
                    </button>
                    <a href="{{ route('achats.index') }}"
                       class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white text-sm rounded-lg transition">
                        ← Liste des achats
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if($errorMessage)
        <div class="mb-6 bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm">{{ $errorMessage }}</p>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- ÉTAPE 1 — LOT PADDY                                           --}}
        {{-- ============================================================ --}}
        @if($etape === 1)
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-6">
            <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3">🌾 Informations du lot</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Fournisseur --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Fournisseur <span class="text-red-400">*</span></label>
                    <select wire:model="fournisseur_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($fournisseurs as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }} @if($f->prenom) {{ $f->prenom }} @endif</option>
                        @endforeach
                    </select>
                </div>

                {{-- Agent --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Agent responsable</label>
                    <select wire:model="agent_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($agents as $a)
                        <option value="{{ $a->id }}">{{ $a->prenom }} {{ $a->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Variété --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Variété de riz <span class="text-red-400">*</span></label>
                    <select wire:model="variete_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($varietes as $v)
                        <option value="{{ $v->id }}">{{ $v->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Localité --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Localité de provenance</label>
                    <select wire:model="localite_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        @foreach($localites as $l)
                        <option value="{{ $l->id }}">{{ $l->nom }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Date achat --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date d'achat <span class="text-red-400">*</span></label>
                    <input type="date" wire:model="date_achat"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                {{-- Achat anticipé --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3">
                        <input type="checkbox" wire:model="est_anticipe"
                               class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-amber-500">
                        <div>
                            <span class="text-sm text-slate-200 font-medium">⏳ Achat anticipé</span>
                            <p class="text-xs text-slate-500 mt-0.5">Le paddy n'est pas encore livré — le stock sera créé mais marqué "en attente"</p>
                        </div>
                    </label>
                </div>

                @if($est_anticipe)
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date de livraison prévue</label>
                    <input type="date" wire:model="date_livraison_prevue"
                           class="w-full bg-slate-800 border border-amber-700/40 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>
                @endif

                {{-- Quantité --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Quantité (kg) <span class="text-red-400">*</span></label>
                    <input type="number" wire:model.lazy="quantite_achat_kg" min="1" step="0.01"
                           placeholder="ex: 5000"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                {{-- Prix unitaire --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Prix unitaire (FCFA/kg) <span class="text-red-400">*</span></label>
                    <input type="number" wire:model.lazy="prix_achat_unitaire_fcfa" min="1"
                           placeholder="ex: 150"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                {{-- Montant total calculé --}}
                <div class="flex items-end">
                    <div class="w-full bg-slate-800/50 border border-amber-700/30 rounded-xl px-4 py-3">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total calculé</p>
                        <p class="text-amber-400 font-black text-xl">
                            {{ number_format($montantTotal, 0, ',', ' ') }} <span class="text-sm font-normal text-slate-500">FCFA</span>
                        </p>
                    </div>
                </div>

            </div>

            <div class="flex justify-end pt-2">
                <button wire:click="allerEtape2"
                        class="px-8 py-3 bg-amber-600 hover:bg-amber-500 active:scale-95 text-white font-bold rounded-xl transition-all">
                    Suivant — Paiement →
                </button>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- ÉTAPE 2 — REÇU / PAIEMENT                                     --}}
        {{-- ============================================================ --}}
        @if($etape === 2)
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-6">
            <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3">💳 Reçu fournisseur & paiement</h2>

            {{-- Récap lot --}}
            <div class="bg-slate-800/50 border border-amber-700/20 rounded-xl p-4 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité</p>
                    <p class="text-white font-bold">{{ number_format((float)$quantite_achat_kg, 0, ',', ' ') }} kg</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Prix unitaire</p>
                    <p class="text-white font-bold">{{ number_format((float)$prix_achat_unitaire_fcfa, 0, ',', ' ') }} FCFA/kg</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total</p>
                    <p class="text-amber-400 font-black text-lg">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Mode paiement --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Mode de paiement</label>
                    <select wire:model="mode_paiement"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="espece">💵 Espèces</option>
                        <option value="cheque">📝 Chèque</option>
                        <option value="mobile_money">📱 Mobile Money</option>
                        <option value="virement">🏦 Virement</option>
                        <option value="credit">⏳ Crédit</option>
                    </select>
                </div>

                {{-- Acompte --}}
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Acompte versé (FCFA)</label>
                    @if($mode_paiement === 'credit')
                    <div class="w-full bg-amber-950/30 border border-amber-700/40 text-amber-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                        <span>⏳</span>
                        <span>Paiement différé — reçu à régler ultérieurement</span>
                    </div>
                    @else
                    <input type="number" wire:model.lazy="acompte" min="0"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                    @if($soldeRestant > 0)
                    <p class="text-yellow-400 text-xs mt-1">
                        Solde dû : {{ number_format($soldeRestant, 0, ',', ' ') }} FCFA
                    </p>
                    @endif
                    @endif
                </div>

                {{-- Date limite paiement (si crédit) --}}
                @if($mode_paiement === 'credit' || $soldeRestant > 0)
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date limite paiement</label>
                    <input type="date" wire:model="date_limite_paiement"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Jours de crédit</label>
                    <input type="number" wire:model="jours_credit" min="0"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>
                @endif

                {{-- Référence --}}
                <div class="md:col-span-2">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Référence interne (optionnel)</label>
                    <input type="text" wire:model="reference_entreprise"
                           placeholder="Bon de commande, référence..."
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                {{-- Générer reçu --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="generer_recu"
                               class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-amber-500">
                        <span class="text-sm text-slate-300">Générer un reçu fournisseur</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-between pt-2">
                <button wire:click="retourEtape(1)"
                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl transition">
                    ← Retour
                </button>
                <button wire:click="allerEtape3"
                        class="px-8 py-3 bg-amber-600 hover:bg-amber-500 active:scale-95 text-white font-bold rounded-xl transition-all">
                    Suivant — Confirmer →
                </button>
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- ÉTAPE 3 — CONFIRMATION                                        --}}
        {{-- ============================================================ --}}
        @if($etape === 3)
        <div class="space-y-4">

            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6">
                <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3 mb-5">📋 Récapitulatif de l'achat</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Fournisseur</p>
                        <p class="text-white font-semibold text-sm">
                            {{ $fournisseurs->firstWhere('id', $fournisseur_id)?->nom ?? '—' }}
                        </p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Variété</p>
                        <p class="text-white font-semibold text-sm">
                            {{ $varietes->firstWhere('id', $variete_id)?->nom ?? '—' }}
                        </p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité</p>
                        <p class="text-amber-400 font-bold">{{ number_format((float)$quantite_achat_kg, 0, ',', ' ') }} kg</p>
                    </div>
                    <div class="bg-slate-800 border border-amber-700/30 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total</p>
                        <p class="text-amber-400 font-black text-lg">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Mode paiement</p>
                        <p class="text-white font-semibold capitalize text-sm">{{ str_replace('_', ' ', $mode_paiement) }}</p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Acompte</p>
                        <p class="text-green-400 font-bold">{{ number_format((int)$acompte, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Solde dû</p>
                        <p class="{{ $soldeRestant > 0 ? 'text-yellow-400' : 'text-green-400' }} font-bold">
                            {{ number_format($soldeRestant, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button wire:click="retourEtape(2)"
                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl transition">
                    ← Retour
                </button>
                <button wire:click="enregistrer"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-60 cursor-wait"
                        class="px-10 py-3 bg-amber-600 hover:bg-amber-500 active:scale-95 text-white font-black rounded-xl transition-all shadow-lg shadow-amber-900/30 flex items-center gap-2">
                    <span wire:loading.remove wire:target="enregistrer">✅ Enregistrer l'achat</span>
                    <span wire:loading wire:target="enregistrer">⏳ Enregistrement...</span>
                </button>
            </div>
        </div>
        @endif

    </div>
</div>