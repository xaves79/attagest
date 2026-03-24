<div class="min-h-screen bg-slate-950 text-white">

    
    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-500/20 border border-violet-500/30 flex items-center justify-center text-sm">📒</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Comptabilité</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Journal · Grand livre · Résultat</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['journal' => '📋 Journal', 'grand_livre' => '📖 Grand livre', 'resultat' => '📊 Résultat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <button wire:click="$set('onglet', '<?php echo e($k); ?>')"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition
                        <?php echo e($onglet === $k ? 'bg-violet-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white'); ?>">
                    <?php echo e($l); ?>

                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($onglet === 'journal'): ?>
                <button wire:click="nouvelleEcriture"
                        class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-xl transition active:scale-95">
                    + Écriture
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
        <div class="bg-green-950/60 border border-green-500/40 rounded-xl p-4 text-green-300 text-sm flex items-center gap-2">
            ✅ <?php echo e($successMessage); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($onglet === 'journal'): ?>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="🔍 Libellé, code..."
                   class="flex-1 min-w-48 bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
            <select wire:model.live="filtreCompte"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                <option value="">Tous les comptes</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($c->code_compte); ?>"><?php echo e($c->code_compte); ?> — <?php echo e($c->libelle); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <input type="date" wire:model.live="filtreDateDeb"
                   class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
            <input type="date" wire:model.live="filtreDateFin"
                   class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
        </div>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Code</th>
                        <th class="text-left px-5 py-3">Date</th>
                        <th class="text-left px-5 py-3" style="width:30%">Libellé</th>
                        <th class="text-left px-5 py-3">Débit</th>
                        <th class="text-left px-5 py-3">Crédit</th>
                        <th class="text-right px-5 py-3">Montant</th>
                        <th class="text-center px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $ecritures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3 font-mono text-violet-400 text-xs font-bold"><?php echo e($e->code_ecriture); ?></td>
                        <td class="px-5 py-3 text-slate-400 text-xs"><?php echo e(\Carbon\Carbon::parse($e->date_ecriture)->format('d/m/Y')); ?></td>
                        <td class="px-5 py-3 text-slate-300"><?php echo e($e->libelle); ?></td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-mono text-blue-400 font-bold"><?php echo e($e->compte_debit); ?></span>
                            <p class="text-xs text-slate-500"><?php echo e($e->libelle_debit); ?></p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-mono text-green-400 font-bold"><?php echo e($e->compte_credit); ?></span>
                            <p class="text-xs text-slate-500"><?php echo e($e->libelle_credit); ?></p>
                        </td>
                        <td class="px-5 py-3 text-right font-black text-white">
                            <?php echo e(number_format((int)$e->montant, 0, ',', ' ')); ?>

                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="editerEcriture(<?php echo e($e->id); ?>)"
                                        class="p-1.5 bg-yellow-700/60 hover:bg-yellow-600 text-white rounded-lg transition text-xs">✏️</button>
                                <button wire:click="supprimerEcriture(<?php echo e($e->id); ?>)"
                                        wire:confirm="Supprimer cette écriture ?"
                                        class="p-1.5 bg-red-700/60 hover:bg-red-600 text-white rounded-lg transition text-xs">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="7" class="px-5 py-16 text-center text-slate-600">
                        <p class="text-4xl mb-3">📋</p>
                        <p class="text-sm">Aucune écriture. Commencez par enregistrer des mouvements.</p>
                        <button wire:click="nouvelleEcriture"
                                class="mt-4 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-xl transition">
                            + Première écriture
                        </button>
                    </td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ecritures->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-800"><?php echo e($ecritures->links()); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        
        
        <?php elseif($onglet === 'grand_livre'): ?>

        <?php
            $types = ['actif' => ['label' => 'Actif', 'color' => 'blue'], 'passif' => ['label' => 'Passif', 'color' => 'purple'], 'charge' => ['label' => 'Charges', 'color' => 'red'], 'produit' => ['label' => 'Produits', 'color' => 'green']];
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <?php $comptesFiltres = $grandLivre->where('type_compte', $type); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($comptesFiltres->isNotEmpty()): ?>
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <div class="px-5 py-3 border-b border-slate-800 flex items-center gap-2">
                <span class="w-2 h-5 bg-<?php echo e($cfg['color']); ?>-500 rounded-full"></span>
                <h3 class="text-sm font-bold text-white"><?php echo e($cfg['label']); ?></h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-2">Code</th>
                        <th class="text-left px-5 py-2">Libellé</th>
                        <th class="text-right px-5 py-2">Débit</th>
                        <th class="text-right px-5 py-2">Crédit</th>
                        <th class="text-right px-5 py-2">Solde net</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $comptesFiltres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="hover:bg-slate-800/30 transition">
                        <td class="px-5 py-2.5 font-mono text-violet-400 text-xs font-bold"><?php echo e($c->code_compte); ?></td>
                        <td class="px-5 py-2.5 text-slate-300"><?php echo e($c->libelle); ?></td>
                        <td class="px-5 py-2.5 text-right text-blue-400"><?php echo e((float)$c->solde_debit > 0 ? number_format((int)$c->solde_debit, 0, ',', ' ') : '—'); ?></td>
                        <td class="px-5 py-2.5 text-right text-green-400"><?php echo e((float)$c->solde_credit > 0 ? number_format((int)$c->solde_credit, 0, ',', ' ') : '—'); ?></td>
                        <td class="px-5 py-2.5 text-right font-bold <?php echo e($c->solde_net > 0 ? 'text-white' : ($c->solde_net < 0 ? 'text-red-400' : 'text-slate-600')); ?>">
                            <?php echo e($c->solde_net != 0 ? number_format((int)$c->solde_net, 0, ',', ' ') : '0'); ?>

                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        
        
        
        <?php elseif($onglet === 'resultat'): ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
            <div class="bg-slate-900 border border-red-700/30 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-5 bg-red-500 rounded-full"></span>
                        <h3 class="text-sm font-bold text-white">Charges</h3>
                    </div>
                    <span class="text-red-400 font-black text-sm"><?php echo e(number_format((int)$totalCharges, 0, ',', ' ')); ?> FCFA</span>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $charges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((float)$c->solde_debit > 0): ?>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-5 py-2.5 font-mono text-xs text-violet-400"><?php echo e($c->code_compte); ?></td>
                            <td class="px-5 py-2.5 text-slate-300"><?php echo e($c->libelle); ?></td>
                            <td class="px-5 py-2.5 text-right text-red-400 font-bold"><?php echo e(number_format((int)$c->solde_debit, 0, ',', ' ')); ?></td>
                        </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($charges->every(fn($c) => (float)$c->solde_debit == 0)): ?>
                        <tr><td colspan="3" class="px-5 py-8 text-center text-slate-600 text-sm">Aucune charge enregistrée</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="bg-slate-900 border border-green-700/30 rounded-2xl overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-5 bg-green-500 rounded-full"></span>
                        <h3 class="text-sm font-bold text-white">Produits</h3>
                    </div>
                    <span class="text-green-400 font-black text-sm"><?php echo e(number_format((int)$totalProduits, 0, ',', ' ')); ?> FCFA</span>
                </div>
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-slate-800">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((float)$c->solde_credit > 0): ?>
                        <tr class="hover:bg-slate-800/30">
                            <td class="px-5 py-2.5 font-mono text-xs text-violet-400"><?php echo e($c->code_compte); ?></td>
                            <td class="px-5 py-2.5 text-slate-300"><?php echo e($c->libelle); ?></td>
                            <td class="px-5 py-2.5 text-right text-green-400 font-bold"><?php echo e(number_format((int)$c->solde_credit, 0, ',', ' ')); ?></td>
                        </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($produits->every(fn($c) => (float)$c->solde_credit == 0)): ?>
                        <tr><td colspan="3" class="px-5 py-8 text-center text-slate-600 text-sm">Aucun produit enregistré</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-slate-900 border <?php echo e($resultat >= 0 ? 'border-green-700/40' : 'border-red-700/40'); ?> rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Résultat net de la période</p>
                    <p class="text-xs text-slate-600">Produits (<?php echo e(number_format((int)$totalProduits, 0, ',', ' ')); ?>) — Charges (<?php echo e(number_format((int)$totalCharges, 0, ',', ' ')); ?>)</p>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-black <?php echo e($resultat >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
                        <?php echo e($resultat >= 0 ? '+' : ''); ?><?php echo e(number_format((int)$resultat, 0, ',', ' ')); ?>

                        <span class="text-lg font-normal text-slate-500">FCFA</span>
                    </p>
                    <p class="text-sm font-bold <?php echo e($resultat >= 0 ? 'text-green-500' : 'text-red-500'); ?> mt-1">
                        <?php echo e($resultat >= 0 ? '✅ Bénéfice' : '⚠️ Déficit'); ?>

                    </p>
                </div>
            </div>
        </div>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-violet-700/40 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">📒 <?php echo e($formId ? 'Modifier' : 'Nouvelle'); ?> écriture</h3>
                <button wire:click="$set('showModal', false)" class="text-slate-500 hover:text-white transition">✕</button>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
            <div class="bg-red-950/60 border border-red-500/40 rounded-xl p-3 text-red-300 text-sm">⚠️ <?php echo e($errorMessage); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Date <span class="text-red-400">*</span></label>
                    <input type="date" wire:model="formDate"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Type de pièce</label>
                    <select wire:model="formPiece"
                            class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pieces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($p->code); ?>"><?php echo e($p->code); ?> — <?php echo e($p->libelle); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Libellé <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="formLibelle" placeholder="Description de l'opération"
                           class="w-full bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-violet-500 transition">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Compte débit <span class="text-red-400">*</span></label>
                    <select wire:model="formCompteDebit"
                            class="w-full bg-slate-800 border border-blue-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $comptes->groupBy('type_compte'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <optgroup label="<?php echo e(ucfirst($type)); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($c->code_compte); ?>"><?php echo e($c->code_compte); ?> — <?php echo e($c->libelle); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </optgroup>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Compte crédit <span class="text-red-400">*</span></label>
                    <select wire:model="formCompteCredit"
                            class="w-full bg-slate-800 border border-green-700/40 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-green-500 transition">
                        <option value="">— Sélectionner —</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $comptes->groupBy('type_compte'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $groupe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <optgroup label="<?php echo e(ucfirst($type)); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($c->code_compte); ?>"><?php echo e($c->code_compte); ?> — <?php echo e($c->libelle); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </optgroup>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1.5">Montant (FCFA) <span class="text-red-400">*</span></label>
                    <input type="text" inputmode="decimal" wire:model="formMontant" placeholder="ex: 150000"
                           class="w-full bg-slate-800 border border-violet-700/40 text-white rounded-xl px-4 py-3 text-lg font-bold focus:outline-none focus:border-violet-500 transition text-right">
                </div>
            </div>

            <div class="flex gap-3">
                <button wire:click="$set('showModal', false)"
                        class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition">Annuler</button>
                <button wire:click="validerEcriture"
                        wire:loading.attr="disabled"
                        class="flex-1 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-bold rounded-xl transition active:scale-95">
                    <span wire:loading.remove wire:target="validerEcriture">✅ <?php echo e($formId ? 'Modifier' : 'Enregistrer'); ?></span>
                    <span wire:loading wire:target="validerEcriture">⏳</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/comptabilite/index.blade.php ENDPATH**/ ?>