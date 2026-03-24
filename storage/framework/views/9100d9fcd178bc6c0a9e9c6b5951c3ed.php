<div class="min-h-screen bg-slate-950 text-white">

    
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('achats.index')); ?>"
                   class="flex items-center gap-2 text-slate-400 hover:text-white transition text-sm group">
                    <span class="group-hover:-translate-x-1 transition-transform inline-block">←</span> Achats
                </a>
                <span class="text-slate-700">›</span>
                <span class="font-mono text-amber-400 text-sm font-bold"><?php echo e($lot->code_lot); ?></span>
            </div>
			<a href="<?php echo e(route('recus.imprimer', $recu->id)); ?>" target="_blank"
   class="px-3 py-1 bg-amber-700 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition">
    🖨 PDF
</a>
            <div class="flex items-center gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lot->statut === 'anticipe'): ?>
                <button wire:click="marquerLivre"
                        class="px-4 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition active:scale-95 flex items-center gap-2">
                    ✅ Marquer livré
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e(route('achats.nouvelle')); ?>"
                   class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    + Nouvel achat
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 flex items-center gap-3 text-green-300">
            <span>✅</span><p class="text-sm font-medium"><?php echo e($successMessage); ?></p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
        <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm"><?php echo e($errorMessage); ?></p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <div class="relative bg-gradient-to-br from-slate-900 via-slate-900 to-amber-950/20 border border-amber-700/30 rounded-2xl p-6 overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>

            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6">

                
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-2xl flex-shrink-0">🌾</div>
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h1 class="text-2xl font-black text-white font-mono tracking-tight"><?php echo e($lot->code_lot); ?></h1>
                                <?php
                                    $statutConfig = [
                                        'disponible'    => ['bg-green-900/50 text-green-300 border-green-700/50', '●', ''],
                                        'anticipe'      => ['bg-amber-900/50 text-amber-300 border-amber-700/50', '◌', 'animate-pulse'],
                                        'epuise'        => ['bg-slate-800 text-slate-500 border-slate-700', '○', ''],
                                        'en_traitement' => ['bg-blue-900/50 text-blue-300 border-blue-700/50', '◑', ''],
                                    ];
                                    $sc = $statutConfig[$lot->statut] ?? $statutConfig['disponible'];
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border <?php echo e($sc[0]); ?>">
                                    <span class="<?php echo e($sc[2]); ?>"><?php echo e($sc[1]); ?></span>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $lot->statut))); ?>

                                </span>
                            </div>
                            <p class="text-slate-400 text-sm mt-1">
                                <?php echo e($lot->variete_nom ?? '—'); ?> · <?php echo e($lot->localite_nom ?? 'Localité inconnue'); ?>

                                · Acheté le <?php echo e(\Carbon\Carbon::parse($lot->date_achat)->format('d/m/Y')); ?>

                            </p>
                        </div>
                    </div>

                    
                    <div class="flex items-center gap-3 bg-slate-800/50 rounded-xl px-4 py-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-700 flex items-center justify-center text-base flex-shrink-0">
                            <?php echo e($lot->type_personne === 'MORALE' ? '🏢' : '👤'); ?>

                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">
                                <?php echo e($lot->fournisseur_nom); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lot->fournisseur_prenom): ?> <?php echo e($lot->fournisseur_prenom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                            <p class="text-slate-500 text-xs">
                                <?php echo e($lot->fournisseur_tel ?? 'Pas de téléphone'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lot->agent_nom): ?> · Agent : <?php echo e($lot->agent_prenom); ?> <?php echo e($lot->agent_nom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-3">
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité achetée</p>
                        <p class="text-2xl font-black text-white"><?php echo e(number_format($lot->quantite_achat_kg, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    </div>
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total</p>
                        <p class="text-xl font-black text-amber-400"><?php echo e(number_format($lot->montant_achat_total_fcfa, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                    </div>
                    <div class="bg-slate-800/60 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Prix unitaire</p>
                        <p class="text-lg font-bold text-slate-300"><?php echo e(number_format($lot->prix_achat_unitaire_fcfa, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA/kg</span></p>
                    </div>
                </div>

            </div>
        </div>

        
        
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            
            <div class="lg:col-span-3 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">📦 Stock paddy généré</h2>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stock): ?>
                    <span class="font-mono text-xs text-slate-500"><?php echo e($stock->code_stock); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stock): ?>
                
                <?php
                    $restant   = (float)$stock->quantite_restante_kg;
                    $total     = (float)$lot->quantite_achat_kg;
                    $pctRestant = $total > 0 ? round($restant / $total * 100) : 0;
                    $barColor  = $pctRestant > 60 ? 'bg-green-500' : ($pctRestant > 25 ? 'bg-amber-500' : 'bg-red-500');
                ?>

                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-800 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Initial</p>
                        <p class="text-xl font-black text-white"><?php echo e(number_format($total, 0, ',', ' ')); ?><span class="text-xs font-normal text-slate-500 ml-1">kg</span></p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Consommé</p>
                        <p class="text-xl font-black text-orange-400"><?php echo e(number_format($pct_consomme, 0)); ?><span class="text-xs font-normal text-slate-500 ml-1">%</span></p>
                    </div>
                    <div class="bg-slate-800 border <?php echo e($pctRestant > 25 ? 'border-green-700/30' : 'border-red-700/30'); ?> rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Restant</p>
                        <p class="text-xl font-black <?php echo e($pctRestant > 60 ? 'text-green-400' : ($pctRestant > 25 ? 'text-amber-400' : 'text-red-400')); ?>">
                            <?php echo e(number_format($restant, 0, ',', ' ')); ?><span class="text-xs font-normal text-slate-500 ml-1">kg</span>
                        </p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-2">
                        <span>Stock restant</span>
                        <span><?php echo e($pctRestant); ?>%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden">
                        <div class="<?php echo e($barColor); ?> h-3 rounded-full transition-all duration-700"
                             style="width: <?php echo e($pctRestant); ?>%"></div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stock->emplacement): ?>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>📍</span>
                    <span>Emplacement : <span class="text-white font-medium"><?php echo e($stock->emplacement); ?></span></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php else: ?>
                <div class="text-center py-8 text-slate-600">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lot->statut === 'anticipe'): ?>
                    <p class="text-4xl mb-3">⏳</p>
                    <p class="text-sm">Le stock sera créé à la livraison du lot.</p>
                    <?php else: ?>
                    <p class="text-4xl mb-3">📦</p>
                    <p class="text-sm">Aucun stock trouvé pour ce lot.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="lg:col-span-2 bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">🧾 Reçu fournisseur</h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu): ?>
                
                <div class="flex items-center justify-between">
                    <span class="font-mono text-amber-400 text-sm font-bold"><?php echo e($recu->numero_recu); ?></span>
                    <span class="px-2 py-0.5 rounded-full text-xs border <?php echo e($recu->paye ? 'bg-green-900/50 text-green-300 border-green-700/50' : 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50'); ?>">
                        <?php echo e($recu->paye ? '✓ Soldé' : '⏳ En cours'); ?>

                    </span>
                </div>

                
                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-2">
                        <span>Avancement paiement</span>
                        <span><?php echo e($pct_paye); ?>%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                        <div class="<?php echo e($pct_paye >= 100 ? 'bg-green-500' : 'bg-amber-500'); ?> h-2 rounded-full transition-all duration-700"
                             style="width: <?php echo e($pct_paye); ?>%"></div>
                    </div>
                </div>

                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total</span>
                        <span class="text-white font-semibold"><?php echo e(number_format($recu->montant_total, 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Payé</span>
                        <span class="text-green-400 font-semibold"><?php echo e(number_format($recu->acompte, 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->solde_du > 0): ?>
                    <div class="flex justify-between border-t border-slate-700 pt-2">
                        <span class="text-slate-400">Solde dû</span>
                        <span class="text-yellow-400 font-black"><?php echo e(number_format($recu->solde_du, 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Mode</span>
                        <span class="text-slate-400 capitalize"><?php echo e(str_replace('_', ' ', $recu->mode_paiement)); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->date_limite_paiement): ?>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Échéance</span>
                        <span class="text-slate-400"><?php echo e(\Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y')); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paiements->isNotEmpty()): ?>
                <div class="border-t border-slate-700/60 pt-3 space-y-2">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Historique</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="flex items-center justify-between text-xs bg-slate-800 rounded-lg px-3 py-2">
                        <div>
                            <p class="text-white font-semibold"><?php echo e(number_format($p->montant, 0, ',', ' ')); ?> FCFA</p>
                            <p class="text-slate-500 capitalize"><?php echo e(str_replace('_', ' ', $p->mode_paiement)); ?></p>
                        </div>
                        <p class="text-slate-500"><?php echo e(\Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y')); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$recu->paye): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$showPaiementForm): ?>
                    <button wire:click="$set('showPaiementForm', true)"
                            class="w-full py-2.5 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition active:scale-95">
                        + Enregistrer un paiement
                    </button>
                    <?php else: ?>
                    <div class="border-t border-slate-700/60 pt-4 space-y-3">
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">💳 Nouveau paiement</p>
                        <input type="number" wire:model="montant_paiement"
                               placeholder="Montant FCFA"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <select wire:model="mode_paiement"
                                class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                            <option value="espece">💵 Espèces</option>
                            <option value="cheque">📝 Chèque</option>
                            <option value="mobile_money">📱 Mobile Money</option>
                            <option value="virement">🏦 Virement</option>
                        </select>
                        <input type="date" wire:model="date_paiement"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <input type="text" wire:model="note_paiement"
                               placeholder="Note (optionnel)"
                               class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <div class="flex gap-2">
                            <button wire:click="$set('showPaiementForm', false)"
                                    class="flex-1 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">
                                Annuler
                            </button>
                            <button wire:click="enregistrerPaiement"
                                    class="flex-1 py-2 bg-green-700 hover:bg-green-600 text-white text-sm font-bold rounded-xl transition">
                                Valider
                            </button>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php else: ?>
                <div class="text-center py-6 text-slate-600">
                    <p class="text-3xl mb-2">🧾</p>
                    <p class="text-sm">Aucun reçu généré pour ce lot.</p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>
        </div>

    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/achats/show-achat.blade.php ENDPATH**/ ?>