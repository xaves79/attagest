<div class="max-w-7xl mx-auto px-4 py-8">

    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">💵 Commandes de vente</h1>
            <p class="text-slate-400 text-sm mt-1">Suivi de toutes les commandes</p>
        </div>
        <a href="<?php echo e(route('commandes.nouvelle')); ?>"
           class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold rounded-lg transition flex items-center gap-2">
            ➕ Nouvelle commande
        </a>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($successMessage): ?>
        <div class="mb-4 p-4 bg-green-900/50 border border-green-600 rounded-lg text-green-300 flex items-center gap-3">
            <span>✅</span> <?php echo e($successMessage); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
        <div class="mb-4 p-4 bg-red-900/50 border border-red-600 rounded-lg text-red-300 flex items-center gap-3">
            <span>⚠️</span> <?php echo e($errorMessage); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Total commandes</p>
            <p class="text-2xl font-bold text-white"><?php echo e(number_format($stats->total)); ?></p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">CA total</p>
            <p class="text-xl font-bold text-green-400">
                <?php echo e(number_format($stats->ca_total, 0, ',', ' ')); ?> <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">CA livré</p>
            <p class="text-xl font-bold text-blue-400">
                <?php echo e(number_format($stats->ca_livre, 0, ',', ' ')); ?> <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
        <div class="bg-slate-800 border border-slate-600 rounded-xl p-4">
            <p class="text-xs text-slate-400 mb-1">Solde en attente</p>
            <p class="text-xl font-bold text-yellow-400">
                <?php echo e(number_format($stats->en_attente, 0, ',', ' ')); ?> <span class="text-sm font-normal">FCFA</span>
            </p>
        </div>
    </div>

    
    <div class="bg-slate-800 border border-slate-600 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">

            
            <div class="lg:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="🔍 Code commande, client..."
                       class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 placeholder-slate-400">
            </div>

            
            <div>
                <select wire:model.live="filtreStatut"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous statuts</option>
                    <option value="brouillon">Brouillon</option>
                    <option value="confirmee">Confirmée</option>
                    <option value="en_attente_livraison">En attente livraison</option>
                    <option value="partiellement_livree">Part. livrée</option>
                    <option value="livree">Livrée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>

            
            <div>
                <select wire:model.live="filtreType"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous types</option>
                    <option value="comptant">Comptant</option>
                    <option value="credit">Crédit</option>
                    <option value="anticipation">Anticipation</option>
                    <option value="gros">Gros</option>
                </select>
            </div>

            
            <div>
                <select wire:model.live="filtrePointVente"
                        class="w-full bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    <option value="">Tous points</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pointsVente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($pv->id); ?>"><?php echo e($pv->nom); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>

            
            <div class="flex items-center">
                <button wire:click="resetFiltres"
                        class="w-full px-3 py-2 bg-slate-600 hover:bg-slate-500 text-slate-300 text-sm rounded-lg transition">
                    🔄 Réinitialiser
                </button>
            </div>
        </div>

        
        <div class="grid grid-cols-2 gap-3 mt-3">
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm whitespace-nowrap">Du</span>
                <input type="date" wire:model.live="filtreDateDu"
                       class="flex-1 bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-slate-400 text-sm whitespace-nowrap">Au</span>
                <input type="date" wire:model.live="filtreDateAu"
                       class="flex-1 bg-slate-700 border border-slate-500 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
            </div>
        </div>
    </div>

    
    <div class="bg-slate-800 border border-slate-600 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-700 text-slate-300 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Code</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-center">Type</th>
                        <th class="px-4 py-3 text-center">Statut</th>
                        <th class="px-4 py-3 text-center">Date</th>
                        <th class="px-4 py-3 text-right">Montant</th>
                        <th class="px-4 py-3 text-right">Solde</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $commandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr class="text-slate-200 hover:bg-slate-700/40 transition">

                        
                        <td class="px-4 py-3">
                            <p class="font-mono font-semibold text-white text-xs">
                                <?php echo e($commande->code_commande); ?>

                            </p>
                            <p class="text-slate-400 text-xs">
                                <?php echo e($commande->lignes_count ?? $commande->lignes->count()); ?> ligne(s)
                            </p>
                        </td>

                        
                        <td class="px-4 py-3">
                            <p class="font-medium text-white">
                                <?php echo e($commande->client->raison_sociale ?? $commande->client->nom . ' ' . $commande->client->prenom); ?>

                            </p>
                            <p class="text-slate-400 text-xs">
                                <?php echo e($commande->pointVente->nom ?? '—'); ?>

                            </p>
                        </td>

                        
                        <td class="px-4 py-3 text-center">
                            <?php
                                $typeLabels = [
                                    'comptant'    => ['label' => 'Comptant',    'class' => 'bg-blue-900/50 text-blue-300 border-blue-700'],
                                    'credit'      => ['label' => 'Crédit',      'class' => 'bg-orange-900/50 text-orange-300 border-orange-700'],
                                    'anticipation'=> ['label' => 'Anticipation','class' => 'bg-purple-900/50 text-purple-300 border-purple-700'],
                                    'gros'        => ['label' => 'Gros',        'class' => 'bg-cyan-900/50 text-cyan-300 border-cyan-700'],
                                ];
                                $t = $typeLabels[$commande->type_vente] ?? ['label' => $commande->type_vente, 'class' => 'bg-slate-700 text-slate-300'];
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-xs border <?php echo e($t['class']); ?>">
                                <?php echo e($t['label']); ?>

                            </span>
                        </td>

                        
                        <td class="px-4 py-3 text-center">
                            <?php
                                $statutLabels = [
                                    'brouillon'              => ['label' => '✏ Brouillon',        'class' => 'bg-slate-700 text-slate-300'],
                                    'confirmee'              => ['label' => '✅ Confirmée',         'class' => 'bg-green-900/50 text-green-300'],
                                    'en_attente_livraison'   => ['label' => '⏳ Att. livraison',   'class' => 'bg-yellow-900/50 text-yellow-300'],
                                    'partiellement_livree'   => ['label' => '📦 Part. livrée',     'class' => 'bg-blue-900/50 text-blue-300'],
                                    'livree'                 => ['label' => '🚚 Livrée',           'class' => 'bg-emerald-900/50 text-emerald-300'],
                                    'annulee'                => ['label' => '❌ Annulée',           'class' => 'bg-red-900/50 text-red-300'],
                                ];
                                $s = $statutLabels[$commande->statut] ?? ['label' => $commande->statut, 'class' => 'bg-slate-700 text-slate-300'];
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs <?php echo e($s['class']); ?>">
                                <?php echo e($s['label']); ?>

                            </span>
                        </td>

                        
                        <td class="px-4 py-3 text-center text-slate-300 text-xs">
                            <p><?php echo e($commande->date_commande?->format('d/m/Y')); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->date_echeance): ?>
                                <p class="text-yellow-400 mt-0.5">
                                    Éch. <?php echo e($commande->date_echeance->format('d/m/Y')); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>

                        
                        <td class="px-4 py-3 text-right">
                            <p class="font-semibold text-white">
                                <?php echo e(number_format($commande->montant_total_fcfa, 0, ',', ' ')); ?>

                            </p>
                            <p class="text-slate-400 text-xs">FCFA</p>
                        </td>

                        
                        <td class="px-4 py-3 text-right">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->montant_solde_fcfa > 0 && $commande->statut !== 'annulee'): ?>
                                <p class="font-semibold text-yellow-400">
                                    <?php echo e(number_format($commande->montant_solde_fcfa, 0, ',', ' ')); ?>

                                </p>
                                <p class="text-slate-400 text-xs">FCFA</p>
                            <?php elseif($commande->statut === 'annulee'): ?>
                                <span class="text-slate-500 text-xs">—</span>
                            <?php else: ?>
                                <span class="text-green-400 text-xs font-semibold">✅ Soldé</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>

                        
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                        class="px-3 py-1 bg-slate-600 hover:bg-slate-500 text-white text-xs rounded-lg transition">
                                    Actions ▾
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     x-transition
                                     class="absolute right-0 mt-1 w-52 bg-slate-700 border border-slate-500 rounded-lg shadow-xl z-20">
                                    <div class="py-1">

                                        
                                        <a href="<?php echo e(route('commandes.show', $commande->id)); ?>"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-200 hover:bg-slate-600">
                                            🔍 Voir le détail
                                        </a>

                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($commande->statut, ['confirmee', 'partiellement_livree', 'en_attente_livraison'])): ?>
                                            <a href="<?php echo e(route('commandes.livrer', $commande->id)); ?>"
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-blue-300 hover:bg-slate-600">
                                                🚚 Enregistrer livraison
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->facture_id): ?>
                                            <a href="<?php echo e(route('factures.imprimer', $commande->facture_id)); ?>"
                                               target="_blank"
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-yellow-300 hover:bg-slate-600">
                                                🖨 Imprimer facture
                                            </a>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($commande->statut, ['livree', 'annulee'])): ?>
                                            <div class="border-t border-slate-600 mt-1 pt-1">
                                                <button wire:click="annulerCommande(<?php echo e($commande->id); ?>)"
                                                        wire:confirm="Confirmer l'annulation de <?php echo e($commande->code_commande); ?> ?"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-red-900/30 w-full text-left">
                                                    ❌ Annuler la commande
                                                </button>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center text-slate-400">
                            <p class="text-4xl mb-3">📭</p>
                            <p class="font-medium">Aucune commande trouvée</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $filtreStatut || $filtreType): ?>
                                <p class="text-sm mt-1">Essayez de modifier vos filtres</p>
                            <?php else: ?>
                                <a href="<?php echo e(route('commandes.nouvelle')); ?>"
                                   class="inline-block mt-4 px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-sm transition">
                                    ➕ Créer la première commande
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commandes->hasPages()): ?>
        <div class="px-4 py-3 border-t border-slate-600">
            <?php echo e($commandes->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <p class="text-slate-500 text-xs mt-3">
        <?php echo e($commandes->total()); ?> commande(s) trouvée(s)
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?> pour « <?php echo e($search); ?> » <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </p>

</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/commandes/liste-commandes.blade.php ENDPATH**/ ?>