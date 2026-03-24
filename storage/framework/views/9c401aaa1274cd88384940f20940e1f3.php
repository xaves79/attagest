<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-6">
        
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Commande <?php echo e($commande->code_commande); ?></h1>
                <div class="flex gap-2 mt-2">
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        <?php echo e($commande->statut === 'livree' ? 'bg-green-900/50 text-green-300 border border-green-600' : 'bg-yellow-900/50 text-yellow-300 border border-yellow-600'); ?>">
                        🚚 <?php echo e(ucfirst($commande->statut)); ?>

                    </span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-900/50 text-blue-300 border border-blue-600">
                        <?php echo e(ucfirst($commande->type_vente)); ?>

                    </span>
                </div>
            </div>
            <a href="<?php echo e(route('commandes.index')); ?>" class="text-slate-400 hover:text-white">← Retour à la liste</a>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-700/50 rounded-lg p-4">
                <h2 class="text-sm font-semibold text-slate-400 uppercase mb-2">📅 Dates</h2>
                <div class="space-y-1 text-slate-200">
                    <p><span class="text-slate-400">Commande :</span> <?php echo e($commande->date_commande->format('d/m/Y')); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->date_livraison_prevue): ?>
                        <p><span class="text-slate-400">Livraison prévue :</span> <?php echo e(\Carbon\Carbon::parse($commande->date_livraison_prevue)->format('d/m/Y')); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->date_livraison_effective): ?>
                        <p><span class="text-slate-400">Livré le :</span> <?php echo e(\Carbon\Carbon::parse($commande->date_livraison_effective)->format('d/m/Y')); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="bg-slate-700/50 rounded-lg p-4">
                <h2 class="text-sm font-semibold text-slate-400 uppercase mb-2">🏪 Point de vente</h2>
                <p class="text-slate-200"><?php echo e($commande->pointVente->nom ?? '—'); ?></p>
                <p class="text-slate-400 text-sm">Agent : <?php echo e($commande->agent->prenom); ?> <?php echo e($commande->agent->nom); ?></p>
            </div>

            <div class="bg-slate-700/50 rounded-lg p-4">
                <h2 class="text-sm font-semibold text-slate-400 uppercase mb-2">👤 Client</h2>
                <p class="text-slate-200 font-medium"><?php echo e($commande->client->nom); ?> <?php echo e($commande->client->prenom); ?></p>
                <p class="text-slate-400 text-sm"><?php echo e($commande->client->code_client); ?></p>
                <p class="text-slate-400 text-sm"><?php echo e($commande->client->telephone); ?></p>
                <p class="text-slate-400 text-sm"><?php echo e($commande->client->ville ?? '—'); ?></p>
            </div>

            <div class="bg-slate-700/50 rounded-lg p-4">
                <h2 class="text-sm font-semibold text-slate-400 uppercase mb-2">📝 Notes</h2>
                <p class="text-slate-200"><?php echo e($commande->notes ?: 'Aucune note'); ?></p>
            </div>
        </div>

        
        <div>
            <h2 class="text-lg font-semibold text-white mb-3">📋 Produits commandés</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Produit</th>
                            <th class="px-4 py-2 text-center">Qté cmd</th>
                            <th class="px-4 py-2 text-center">Livré</th>
                            <th class="px-4 py-2 text-center">Restant</th>
                            <th class="px-4 py-2 text-right">Prix unit.</th>
                            <th class="px-4 py-2 text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $commande->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php
                                $quantiteLivree = $ligne->quantite_livree;
                                $reste = $ligne->quantite - $quantiteLivree;
                            ?>
                            <tr class="hover:bg-slate-700/30">
                                <td class="px-4 py-3">
                                    <div><?php echo e($ligne->sac->code_sac ?? $ligne->type_produit); ?></div>
                                    <div class="text-xs text-slate-400">
                                        <?php echo e(match($ligne->type_produit) {
                                            'riz_blanc' => 'Riz blanc',
                                            'son' => 'Son',
                                            'brisures' => 'Brisures',
                                            default => $ligne->type_produit
                                        }); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ligne->poids_sac_kg): ?> · <?php echo e($ligne->poids_sac_kg); ?>kg <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center"><?php echo e($ligne->quantite); ?> <?php echo e($ligne->unite); ?></td>
                                <td class="px-4 py-3 text-center"><?php echo e($quantiteLivree); ?></td>
                                <td class="px-4 py-3 text-center"><?php echo e($reste); ?></td>
                                <td class="px-4 py-3 text-right"><?php echo e(number_format($ligne->prix_unitaire_fcfa, 0, ',', ' ')); ?> FCFA</td>
                                <td class="px-4 py-3 text-right"><?php echo e(number_format($ligne->quantite * $ligne->prix_unitaire_fcfa, 0, ',', ' ')); ?> FCFA</td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                    <tfoot class="bg-slate-700">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-semibold">Total</td>
                            <td class="px-4 py-3 text-right font-bold"><?php echo e(number_format($commande->montant_total_fcfa, 0, ',', ' ')); ?> FCFA</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($commande->livraisons->count()): ?>
            <div>
                <h2 class="text-lg font-semibold text-white mb-3">🚚 Livraisons</h2>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $commande->livraisons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $liv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="bg-slate-700/30 rounded p-3 flex justify-between items-center">
                            <div>
                                <span class="font-mono text-blue-400"><?php echo e($liv->code_livraison); ?></span>
                                <span class="text-slate-400 text-sm ml-2"><?php echo e($liv->date_livraison->format('d/m/Y H:i')); ?></span>
                                <div class="text-sm text-slate-300">Agent : <?php echo e($liv->agent->prenom); ?> <?php echo e($liv->agent->nom); ?></div>
                            </div>
                            <div class="text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $liv->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div><?php echo e($ll->quantite); ?> × <?php echo e($ll->stockSac->sac->code_sac ?? '—'); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div>
            <h2 class="text-lg font-semibold text-white mb-3">💰 Paiements</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-sm">Total commande</p>
                    <p class="text-xl font-bold text-white"><?php echo e(number_format($commande->montant_total_fcfa, 0, ',', ' ')); ?> FCFA</p>
                </div>
                <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-sm">Payé</p>
                    <p class="text-xl font-bold text-green-400"><?php echo e(number_format($commande->montant_acompte_fcfa, 0, ',', ' ')); ?> FCFA</p>
                </div>
                <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-sm">Solde</p>
                    <p class="text-xl font-bold <?php echo e($commande->montant_total_fcfa - $commande->montant_acompte_fcfa > 0 ? 'text-yellow-400' : 'text-green-400'); ?>">
                        <?php echo e(number_format($commande->montant_total_fcfa - $commande->montant_acompte_fcfa, 0, ',', ' ')); ?> FCFA
                    </p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($commande->montant_total_fcfa - $commande->montant_acompte_fcfa) > 0): ?>
                <div class="flex justify-end">
                    <button wire:click="ouvrirPaiementModal" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-lg flex items-center gap-2">
                        💰 Enregistrer un paiement
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPaiementModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
            <div class="w-full max-w-md bg-slate-800 rounded-lg shadow-xl border border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        💰 Enregistrer un paiement
                    </h3>

                    <form wire:submit="enregistrerPaiement">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-300 mb-1">Montant (FCFA)</label>
                            <input
                                type="number"
                                wire:model="paiement_montant"
                                step="1"
                                min="1"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:ring-2 focus:ring-green-500"
                                required
                            />
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['paiement_montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-red-400 mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-300 mb-1">Mode de paiement</label>
                            <select wire:model="paiement_mode" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white" required>
                                <option value="espèces">💵 Espèces</option>
                                <option value="mobile_money">📱 Mobile Money</option>
                                <option value="chèque">📄 Chèque</option>
                                <option value="virement">🏦 Virement</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                wire:click="$set('showPaiementModal', false)"
                                class="px-6 py-2.5 bg-slate-600 hover:bg-slate-500 text-white font-medium rounded-lg"
                            >
                                Annuler
                            </button>
                            <button
                                type="submit"
                                class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg"
                            >
                                ✅ Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="fixed bottom-4 right-4 bg-green-900/90 border border-green-600 rounded-lg px-4 py-2 text-green-300">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
        <div class="fixed bottom-4 right-4 bg-red-900/90 border border-red-600 rounded-lg px-4 py-2 text-red-300">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/commandes/show-commande.blade.php ENDPATH**/ ?>