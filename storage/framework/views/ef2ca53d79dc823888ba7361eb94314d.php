<div class="min-h-screen bg-slate-950 text-white">

    
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-sm">📊</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Bilan global</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Synthèse achats · production · ventes · trésorerie</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['semaine' => '7j', 'mois' => 'Mois', 'annee' => 'Année']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <button wire:click="$set('periode', '<?php echo e($val); ?>')"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                        <?php echo e($periode === $val ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'); ?>">
                    <?php echo e($label); ?>

                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                
                <input type="date" wire:model.lazy="date_debut"
                       class="bg-slate-800 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-indigo-500 transition">
                <span class="text-slate-600 text-xs">→</span>
                <input type="date" wire:model.lazy="date_fin"
                       class="bg-slate-800 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-indigo-500 transition">

                
                <button wire:click="telechargerPdf"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold rounded-xl transition flex items-center gap-2">
                    <span wire:loading.remove wire:target="telechargerPdf">📄 PDF</span>
                    <span wire:loading wire:target="telechargerPdf">⏳</span>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">

        
        <div class="text-center">
            <p class="text-xs text-slate-500 uppercase tracking-wider">Période analysée</p>
            <p class="text-lg font-bold text-white mt-1">
                <?php echo e(\Carbon\Carbon::parse($date_debut)->format('d/m/Y')); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($date_debut !== $date_fin): ?>
                → <?php echo e(\Carbon\Carbon::parse($date_fin)->format('d/m/Y')); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>

        
        
        
        <div class="space-y-4">
            <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-px bg-amber-700"></span> 🌾 Achats paddy <span class="flex-1 h-px bg-amber-700/30"></span>
            </h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-slate-900 border border-amber-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Lots achetés</p>
                    <p class="text-3xl font-black text-white"><?php echo e($achats->nb ?? 0); ?></p>
                </div>
                <div class="bg-slate-900 border border-amber-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Quantité totale</p>
                    <p class="text-3xl font-black text-amber-400"><?php echo e(number_format($achats->total_kg ?? 0, 0, ',', ' ')); ?><span class="text-base font-normal text-slate-500 ml-1">kg</span></p>
                </div>
                <div class="bg-slate-900 border border-amber-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Montant total</p>
                    <p class="text-3xl font-black text-amber-400"><?php echo e(number_format($achats->total_fcfa ?? 0, 0, ',', ' ')); ?><span class="text-base font-normal text-slate-500 ml-1">FCFA</span></p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($achatsFournisseurs->isNotEmpty()): ?>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">Par fournisseur</div>
                <table class="w-full text-sm">
                    <thead><tr class="text-xs text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-2">Fournisseur</th>
                        <th class="text-right px-5 py-2">Nb lots</th>
                        <th class="text-right px-5 py-2">Quantité (kg)</th>
                        <th class="text-right px-5 py-2">Montant (FCFA)</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $achatsFournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-5 py-2.5 text-slate-300 font-medium"><?php echo e($f->nom); ?></td>
                            <td class="px-5 py-2.5 text-right text-slate-400"><?php echo e($f->nb_achats); ?></td>
                            <td class="px-5 py-2.5 text-right text-amber-400 font-semibold"><?php echo e(number_format($f->total_kg, 0, ',', ' ')); ?></td>
                            <td class="px-5 py-2.5 text-right text-white font-bold"><?php echo e(number_format($f->total_fcfa, 0, ',', ' ')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <div class="space-y-4">
            <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-px bg-blue-700"></span> 🏭 Production <span class="flex-1 h-px bg-blue-700/30"></span>
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 border border-blue-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Paddy étuvé</p>
                    <p class="text-2xl font-black text-blue-400"><?php echo e(number_format($etuvages->total_entree ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($etuvages->nb ?? 0); ?> étuvage(s)</p>
                </div>
                <div class="bg-slate-900 border border-blue-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz étuvé produit</p>
                    <p class="text-2xl font-black text-blue-300"><?php echo e(number_format($rizEtuve->total_kg ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1">Rdt moy : <?php echo e(number_format($rizEtuve->rdt_moy ?? 0, 1)); ?>%</p>
                </div>
                <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Riz blanc produit</p>
                    <p class="text-2xl font-black text-green-400"><?php echo e(number_format($decorticages->total_blanc ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">kg</span></p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($decorticages->nb ?? 0); ?> décorticage(s)</p>
                </div>
                <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Rendement décorti.</p>
                    <p class="text-2xl font-black <?php echo e(($decorticages->rdt_moy ?? 0) >= 60 ? 'text-green-400' : 'text-amber-400'); ?>">
                        <?php echo e(number_format($decorticages->rdt_moy ?? 0, 1)); ?><span class="text-sm font-normal text-slate-500 ml-1">%</span>
                    </p>
                    <p class="text-xs text-slate-600 mt-1">Son : <?php echo e(number_format($decorticages->total_son ?? 0, 0, ',', ' ')); ?> kg · Brisures : <?php echo e(number_format($decorticages->total_brisures ?? 0, 0, ',', ' ')); ?> kg</p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prodParVariete->isNotEmpty()): ?>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">Production par variété</div>
                <table class="w-full text-sm">
                    <thead><tr class="text-xs text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-2">Variété</th>
                        <th class="text-right px-5 py-2">Riz blanc (kg)</th>
                        <th class="text-right px-5 py-2">Son (kg)</th>
                        <th class="text-right px-5 py-2">Rendement moy.</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $prodParVariete; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-5 py-2.5 text-slate-300 font-medium"><?php echo e($v->variete); ?></td>
                            <td class="px-5 py-2.5 text-right text-green-400 font-semibold"><?php echo e(number_format($v->riz_blanc_kg, 0, ',', ' ')); ?></td>
                            <td class="px-5 py-2.5 text-right text-amber-400"><?php echo e(number_format($v->son_kg, 0, ',', ' ')); ?></td>
                            <td class="px-5 py-2.5 text-right font-bold <?php echo e($v->rdt_moy >= 60 ? 'text-green-400' : 'text-amber-400'); ?>"><?php echo e(number_format($v->rdt_moy, 1)); ?>%</td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <div class="space-y-4">
            <h2 class="text-xs font-bold text-green-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-px bg-green-700"></span> 🛒 Ventes <span class="flex-1 h-px bg-green-700/30"></span>
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Commandes</p>
                    <p class="text-3xl font-black text-white"><?php echo e($commandes->nb ?? 0); ?></p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($commandes->nb_comptant ?? 0); ?> comptant · <?php echo e($commandes->nb_credit ?? 0); ?> crédit</p>
                </div>
                <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">CA total</p>
                    <p class="text-2xl font-black text-green-400"><?php echo e(number_format($commandes->total_fcfa ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                </div>
                <div class="bg-slate-900 border border-green-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Encaissé clients</p>
                    <p class="text-2xl font-black text-green-300"><?php echo e(number_format($paiementsClients->total_encaisse ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($paiementsClients->nb ?? 0); ?> paiement(s)</p>
                </div>
                <div class="bg-slate-900 border <?php echo e(($soldesClients->total_solde ?? 0) > 0 ? 'border-red-700/40' : 'border-slate-700/60'); ?> rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Soldes clients dus</p>
                    <p class="text-2xl font-black <?php echo e(($soldesClients->total_solde ?? 0) > 0 ? 'text-red-400' : 'text-green-400'); ?>">
                        <?php echo e(number_format($soldesClients->total_solde ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span>
                    </p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($soldesClients->nb_factures ?? 0); ?> facture(s) impayée(s)</p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ventesClients->isNotEmpty()): ?>
            <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 text-xs text-slate-400 uppercase tracking-wider font-semibold">Top clients</div>
                <table class="w-full text-sm">
                    <thead><tr class="text-xs text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-2">Client</th>
                        <th class="text-right px-5 py-2">Nb commandes</th>
                        <th class="text-right px-5 py-2">Montant (FCFA)</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ventesClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-5 py-2.5 text-slate-300 font-medium"><?php echo e($c->raison_sociale ?: $c->nom); ?></td>
                            <td class="px-5 py-2.5 text-right text-slate-400"><?php echo e($c->nb_commandes); ?></td>
                            <td class="px-5 py-2.5 text-right text-green-400 font-bold"><?php echo e(number_format($c->total_fcfa, 0, ',', ' ')); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <div class="space-y-4">
            <h2 class="text-xs font-bold text-violet-400 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-px bg-violet-700"></span> 💰 Trésorerie <span class="flex-1 h-px bg-violet-700/30"></span>
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-slate-900 border border-violet-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Encaissé clients</p>
                    <p class="text-2xl font-black text-green-400">+ <?php echo e(number_format($paiementsClients->total_encaisse ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                </div>
                <div class="bg-slate-900 border border-violet-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Payé fournisseurs</p>
                    <p class="text-2xl font-black text-red-400">- <?php echo e(number_format($paiementsFournisseurs->total_paye ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span></p>
                </div>
                <div class="bg-slate-900 border border-violet-700/30 rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Solde dû fournisseurs</p>
                    <p class="text-2xl font-black <?php echo e(($soldesFournisseurs->total_solde ?? 0) > 0 ? 'text-orange-400' : 'text-green-400'); ?>">
                        <?php echo e(number_format($soldesFournisseurs->total_solde ?? 0, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span>
                    </p>
                    <p class="text-xs text-slate-600 mt-1"><?php echo e($soldesFournisseurs->nb_recus ?? 0); ?> reçu(s) impayé(s)</p>
                </div>
                <?php
                    $flux = ($paiementsClients->total_encaisse ?? 0) - ($paiementsFournisseurs->total_paye ?? 0);
                ?>
                <div class="bg-slate-900 border <?php echo e($flux >= 0 ? 'border-green-700/40' : 'border-red-700/40'); ?> rounded-2xl p-5">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Flux net période</p>
                    <p class="text-2xl font-black <?php echo e($flux >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
                        <?php echo e($flux >= 0 ? '+' : ''); ?><?php echo e(number_format($flux, 0, ',', ' ')); ?><span class="text-sm font-normal text-slate-500 ml-1">FCFA</span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/bilans-globaux.blade.php ENDPATH**/ ?>