<div class="min-h-screen bg-slate-950 text-white">

    
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60 shadow-xl">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center gap-4">
            <a href="<?php echo e(route('achats.index')); ?>"
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

        
        <div class="flex items-center gap-0 mb-10">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [1 => 'Lot paddy', 2 => 'Paiement', 3 => 'Confirmation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="flex items-center <?php echo e($n < 3 ? 'flex-1' : ''); ?>">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition
                        <?php echo e($etape > $n ? 'bg-amber-500 border-amber-500 text-white' :
                           ($etape === $n ? 'border-amber-500 text-amber-400 bg-amber-500/10' :
                           'border-slate-700 text-slate-600 bg-slate-900')); ?>">
                        <?php echo e($etape > $n ? '✓' : $n); ?>

                    </div>
                    <span class="text-xs font-medium <?php echo e($etape >= $n ? 'text-white' : 'text-slate-600'); ?>"><?php echo e($label); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($n < 3): ?>
                <div class="flex-1 h-px mx-4 <?php echo e($etape > $n ? 'bg-amber-500' : 'bg-slate-700'); ?>"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
        <div class="mb-6 bg-green-950/60 border border-green-500/40 rounded-2xl p-5 flex items-start gap-4">
            <span class="text-2xl">✅</span>
            <div>
                <p class="font-bold text-green-300"><?php echo e($successMessage); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($codeLot): ?>
                <p class="text-green-400 text-sm mt-1">Lot : <span class="font-mono font-bold"><?php echo e($codeLot); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($numeroRecu): ?> · Reçu : <span class="font-mono font-bold"><?php echo e($numeroRecu); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="mt-3 flex gap-3">
                    <button wire:click="$set('successMessage', '')"
                            class="px-4 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-sm rounded-lg transition">
                        + Nouvel achat
                    </button>
                    <a href="<?php echo e(route('achats.index')); ?>"
                       class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white text-sm rounded-lg transition">
                        ← Liste des achats
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
        <div class="mb-6 bg-red-950/60 border border-red-500/40 rounded-xl p-4 flex items-center gap-3 text-red-300">
            <span>⚠️</span><p class="text-sm"><?php echo e($errorMessage); ?></p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($etape === 1): ?>
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-6">
            <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3">🌾 Informations du lot</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Fournisseur <span class="text-red-400">*</span></label>
                    <select wire:model="fournisseur_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($f->id); ?>"><?php echo e($f->nom); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f->prenom): ?> <?php echo e($f->prenom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Agent responsable</label>
                    <select wire:model="agent_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($a->id); ?>"><?php echo e($a->prenom); ?> <?php echo e($a->nom); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Variété de riz <span class="text-red-400">*</span></label>
                    <select wire:model="variete_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $varietes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($v->id); ?>"><?php echo e($v->nom); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Localité de provenance</label>
                    <select wire:model="localite_id"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $localites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($l->id); ?>"><?php echo e($l->nom); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date d'achat <span class="text-red-400">*</span></label>
                    <input type="date" wire:model="date_achat"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                
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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($est_anticipe): ?>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Date de livraison prévue</label>
                    <input type="date" wire:model="date_livraison_prevue"
                           class="w-full bg-slate-800 border border-amber-700/40 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Quantité (kg) <span class="text-red-400">*</span></label>
                    <input type="number" wire:model.lazy="quantite_achat_kg" min="1" step="0.01"
                           placeholder="ex: 5000"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Prix unitaire (FCFA/kg) <span class="text-red-400">*</span></label>
                    <input type="number" wire:model.lazy="prix_achat_unitaire_fcfa" min="1"
                           placeholder="ex: 150"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                
                <div class="flex items-end">
                    <div class="w-full bg-slate-800/50 border border-amber-700/30 rounded-xl px-4 py-3">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total calculé</p>
                        <p class="text-amber-400 font-black text-xl">
                            <?php echo e(number_format($montantTotal, 0, ',', ' ')); ?> <span class="text-sm font-normal text-slate-500">FCFA</span>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($etape === 2): ?>
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6 space-y-6">
            <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3">💳 Reçu fournisseur & paiement</h2>

            
            <div class="bg-slate-800/50 border border-amber-700/20 rounded-xl p-4 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité</p>
                    <p class="text-white font-bold"><?php echo e(number_format((float)$quantite_achat_kg, 0, ',', ' ')); ?> kg</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Prix unitaire</p>
                    <p class="text-white font-bold"><?php echo e(number_format((float)$prix_achat_unitaire_fcfa, 0, ',', ' ')); ?> FCFA/kg</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total</p>
                    <p class="text-amber-400 font-black text-lg"><?php echo e(number_format($montantTotal, 0, ',', ' ')); ?> FCFA</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                
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

                
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Acompte versé (FCFA)</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mode_paiement === 'credit'): ?>
                    <div class="w-full bg-amber-950/30 border border-amber-700/40 text-amber-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                        <span>⏳</span>
                        <span>Paiement différé — reçu à régler ultérieurement</span>
                    </div>
                    <?php else: ?>
                    <input type="number" wire:model.lazy="acompte" min="0"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($soldeRestant > 0): ?>
                    <p class="text-yellow-400 text-xs mt-1">
                        Solde dû : <?php echo e(number_format($soldeRestant, 0, ',', ' ')); ?> FCFA
                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mode_paiement === 'credit' || $soldeRestant > 0): ?>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="md:col-span-2">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-2">Référence interne (optionnel)</label>
                    <input type="text" wire:model="reference_entreprise"
                           placeholder="Bon de commande, référence..."
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-amber-500 transition">
                </div>

                
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($etape === 3): ?>
        <div class="space-y-4">

            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-6">
                <h2 class="text-base font-bold text-white border-b border-slate-700 pb-3 mb-5">📋 Récapitulatif de l'achat</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Fournisseur</p>
                        <p class="text-white font-semibold text-sm">
                            <?php echo e($fournisseurs->firstWhere('id', $fournisseur_id)?->nom ?? '—'); ?>

                        </p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Variété</p>
                        <p class="text-white font-semibold text-sm">
                            <?php echo e($varietes->firstWhere('id', $variete_id)?->nom ?? '—'); ?>

                        </p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Quantité</p>
                        <p class="text-amber-400 font-bold"><?php echo e(number_format((float)$quantite_achat_kg, 0, ',', ' ')); ?> kg</p>
                    </div>
                    <div class="bg-slate-800 border border-amber-700/30 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Montant total</p>
                        <p class="text-amber-400 font-black text-lg"><?php echo e(number_format($montantTotal, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Mode paiement</p>
                        <p class="text-white font-semibold capitalize text-sm"><?php echo e(str_replace('_', ' ', $mode_paiement)); ?></p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Acompte</p>
                        <p class="text-green-400 font-bold"><?php echo e(number_format((int)$acompte, 0, ',', ' ')); ?> FCFA</p>
                    </div>
                    <div class="bg-slate-800 rounded-xl p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Solde dû</p>
                        <p class="<?php echo e($soldeRestant > 0 ? 'text-yellow-400' : 'text-green-400'); ?> font-bold">
                            <?php echo e(number_format($soldeRestant, 0, ',', ' ')); ?> FCFA
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/achats/nouvel-achat.blade.php ENDPATH**/ ?>