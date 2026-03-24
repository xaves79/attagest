<div class="min-h-screen bg-slate-950 text-white">

    
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-sm">🌾</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Achats paddy</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Gestion des lots</p>
                </div>
            </div>
            <a href="<?php echo e(route('achats.nouvelle')); ?>"
               class="flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                + Nouvel achat
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Lots achetés</p>
                <p class="text-3xl font-black text-white"><?php echo e($kpis['nb_lots']); ?></p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Total acheté</p>
                <p class="text-3xl font-black text-amber-400"><?php echo e(number_format($kpis['total_kg'], 0, ',', ' ')); ?><span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Montant total</p>
                <p class="text-2xl font-black text-amber-400"><?php echo e(number_format($kpis['total_montant'], 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
            </div>
            <div class="bg-slate-900 border border-green-700/40 rounded-2xl p-5">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Stock disponible</p>
                <p class="text-3xl font-black text-green-400"><?php echo e(number_format($kpis['disponible_kg'], 0, ',', ' ')); ?><span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
            </div>
        </div>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4">
            <div class="flex flex-wrap gap-3 items-center">

                
                <div class="flex-1 min-w-48">
                    <input type="text" wire:model.live.debounce.300ms="recherche"
                           placeholder="🔍 Lot, fournisseur..."
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
                </div>

                
                <select wire:model.live="filtrePeriode"
                        class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
                    <option value="semaine">Cette semaine</option>
                    <option value="mois" selected>Ce mois</option>
                    <option value="annee">Cette année</option>
                    <option value="">Tout</option>
                </select>

                
                <select wire:model.live="filtreStatut"
                        class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
                    <option value="">Tous statuts</option>
                    <option value="disponible">Disponible</option>
                    <option value="epuise">Épuisé</option>
                    <option value="en_traitement">En traitement</option>
                </select>

                
                <select wire:model.live="filtreVariete"
                        class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition">
                    <option value="">Toutes variétés</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $varietes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <option value="<?php echo e($v->id); ?>"><?php echo e($v->nom); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>

            </div>
        </div>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Lot</th>
                        <th class="text-left px-5 py-3">Fournisseur</th>
                        <th class="text-left px-5 py-3">Variété</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="text-right px-5 py-3">Quantité</th>
                        <th class="text-right px-5 py-3">Restant</th>
                        <th class="text-right px-5 py-3">Montant</th>
                        <th class="text-center px-5 py-3">Statut</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $achats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-slate-800/40 transition group">
                        <td class="px-5 py-3">
                            <span class="font-mono text-amber-400 text-xs font-bold"><?php echo e($achat->code_lot); ?></span>
                        </td>
                        <td class="px-5 py-3 text-slate-300"><?php echo e($achat->fournisseur?->nom ?? '—'); ?></td>
                        <td class="px-5 py-3 text-slate-400 text-xs"><?php echo e($achat->variete?->nom ?? '—'); ?></td>
                        <td class="px-5 py-3 text-slate-400 text-xs"><?php echo e($achat->date_achat?->format('d/m/Y')); ?></td>
                        <td class="px-5 py-3 text-right text-slate-300 font-semibold">
                            <?php echo e(number_format($achat->quantite_achat_kg, 0, ',', ' ')); ?> kg
                        </td>
                        <td class="px-5 py-3 text-right font-semibold
                            <?php echo e($achat->quantite_restante_kg > 0 ? 'text-green-400' : 'text-slate-600'); ?>">
                            <?php echo e(number_format($achat->quantite_restante_kg, 0, ',', ' ')); ?> kg
                        </td>
                        <td class="px-5 py-3 text-right text-amber-400 font-bold">
                            <?php echo e(number_format($achat->montant_achat_total_fcfa, 0, ',', ' ')); ?>

                        </td>
                        <td class="px-5 py-3 text-center">
                            <?php
                                $badges = [
                                    'disponible'    => 'bg-green-900/50 text-green-300 border-green-700/50',
                                    'epuise'        => 'bg-slate-800 text-slate-500 border-slate-700',
                                    'en_traitement' => 'bg-blue-900/50 text-blue-300 border-blue-700/50',
                                ];
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-xs border <?php echo e($badges[$achat->statut] ?? 'bg-slate-800 text-slate-400 border-slate-700'); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $achat->statut ?? '—'))); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="<?php echo e(route('achats.show', $achat->id)); ?>"
                               class="text-xs text-slate-500 group-hover:text-amber-400 transition font-medium">
                                Voir →
                            </a>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center text-slate-600">
                            <p class="text-4xl mb-3">🌾</p>
                            <p class="text-sm">Aucun achat trouvé pour cette période.</p>
                            <a href="<?php echo e(route('achats.nouvelle')); ?>"
                               class="inline-block mt-4 px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm rounded-xl transition">
                                + Enregistrer un achat
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($achats->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-800">
                <?php echo e($achats->links()); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/achats/liste-achats.blade.php ENDPATH**/ ?>