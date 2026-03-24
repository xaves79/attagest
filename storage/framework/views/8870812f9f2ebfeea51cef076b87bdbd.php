<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white p-6">
        <div class="max-w-7xl mx-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
                <div class="bg-emerald-800/90 border border-emerald-600/50 backdrop-blur-sm text-emerald-200 px-6 py-4 rounded-xl mb-6 animate-pulse shadow-lg">
                    <i class="fas fa-check-circle mr-3"></i><?php echo e(session('message')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-blue-500 bg-clip-text text-transparent">
                        📦 Ventes & Factures
                    </h1>
                    <p class="text-gray-400 mt-2 text-lg">Création de factures simples (crédit)</p>
                </div>
                <button 
                    wire:click="create" 
                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    ➕ Nouvelle facture
                </button>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($showModal)): ?>
                <div class="space-y-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facturesRecentes->count() > 0): ?>
                        <!-- ✅ TABLEAU FACTURES RÉCENTES -->
                        <div class="bg-gradient-to-r from-gray-800/70 to-gray-900/50 border border-gray-700/50 rounded-2xl p-6 backdrop-blur-sm shadow-2xl">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-bold text-emerald-400 flex items-center">
                                    <i class="fas fa-file-invoice-dollar mr-3"></i>
                                    Factures récentes (<?php echo e($facturesRecentes->count()); ?>)
                                </h3>
                                <a href="#" class="text-emerald-400 hover:text-emerald-300 text-sm font-semibold underline">
                                    Voir toutes →
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-700 bg-gray-900/50">
                                            <th class="text-left py-4 pl-0">N° Facture</th>
                                            <th class="text-left py-4 px-4">Client</th>
                                            <th class="text-right py-4 px-4">Montant</th>
                                            <th class="text-center py-4 px-4">Statut</th>
                                            <th class="text-right py-4 pr-0">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facturesRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <tr class="hover:bg-gray-700/50 transition-all border-b border-gray-800/30 group">
                                                <td class="py-4 pl-0 font-mono text-emerald-400 font-bold">
                                                    #<?php echo e($facture->numero_facture); ?>

                                                </td>
                                                <td class="py-4 px-4 font-medium">
                                                    <?php echo e($facture->client->nom ?? 'N/A'); ?>

                                                </td>
                                                <td class="py-4 px-4 text-right font-bold text-yellow-400 text-lg">
                                                    <?php echo e(number_format($facture->montant_total, 0, ',', ' ')); ?>

                                                </td>
                                                <td class="py-4 px-4 text-center">
                                                    <span class="px-4 py-2 rounded-full text-xs font-bold inline-block shadow-lg
                                                        <?php echo e($facture->statut == 'paye' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-400/50' : 'bg-orange-500/20 text-orange-300 border-orange-400/50'); ?>

                                                        border-2">
                                                        <?php echo e($facture->statut == 'paye' ? '✅ Payée' : '⏳ Crédit'); ?>

                                                    </span>
                                                </td>
                                                <td class="py-4 pr-0 text-right text-gray-400">
                                                    <?php echo e(\Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y')); ?>

                                                </td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Message si pas de factures -->
                        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl p-16 text-center border border-gray-700/50 shadow-2xl">
                            <div class="text-7xl mb-8 text-gray-500">📋</div>
                            <h3 class="text-3xl font-bold text-gray-300 mb-4">Aucune facture créée</h3>
                            <p class="text-gray-400 text-xl mb-8">Créez votre première facture de vente !</p>
                            <p class="text-sm text-blue-400 font-semibold mt-6 bg-blue-500/20 p-4 rounded-xl border border-blue-500/30">
                                💡 Les paiements sont gérés dans un module séparé
                            </p>
                            <div class="w-40 h-40 bg-gradient-to-r from-emerald-500 to-blue-600 rounded-full mx-auto animate-pulse shadow-2xl mt-12 border-4 border-emerald-400/30"></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal && !$viewMode): ?>
                <div class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                    <div class="bg-gray-900/95 border-2 border-gray-700/50 rounded-3xl shadow-2xl max-w-7xl w-full max-h-[95vh] overflow-hidden">
                        <!-- Header -->
                        <div class="sticky top-0 bg-gradient-to-r from-gray-900/100 to-gray-800/50 border-b-2 border-emerald-500/30 p-8 z-20 backdrop-blur-sm">
                            <h2 class="text-3xl font-black text-white flex items-center">
                                <i class="fas fa-file-invoice-dollar mr-4 text-emerald-400 text-2xl"></i>
                                Nouvelle Facture (Crédit)
                            </h2>
                            <p class="text-gray-400 mt-2">Remplissez les informations puis validez</p>
                        </div>

                        <form wire:submit.prevent="save" class="p-8 space-y-8 overflow-y-auto max-h-[calc(95vh-200px)]">
                            <!-- FORMULAIRE PRINCIPAL -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="md:col-span-2">
                                    <label class="block text-lg font-semibold text-gray-300 mb-3">Point de vente *</label>
                                    <select wire:model.live="form.point_vente_id" class="w-full bg-gray-800/50 border-2 border-gray-600 rounded-2xl px-6 py-4 text-lg text-white focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all shadow-lg" required>
                                        <option value="">🔍 Sélectionner un point de vente</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pointsVente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($pv->id); ?>"><?php echo e($pv->nom); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.point_vente_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                        <span class="text-red-400 text-sm mt-2 block font-semibold"><?php echo e($message); ?></span> 
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-lg font-semibold text-gray-300 mb-3">Client *</label>
                                    <select wire:model="form.client_id" class="w-full bg-gray-800/50 border-2 border-gray-600 rounded-2xl px-6 py-4 text-lg text-white focus:ring-4 focus:ring-emerald-500/30" required>
                                        <option value="">🔍 Sélectionner un client</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($client->id); ?>"><?php echo e($client->nom); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                        <span class="text-red-400 text-sm mt-2 block font-semibold"><?php echo e($message); ?></span> 
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-lg font-semibold text-gray-300 mb-3">Agent</label>
                                    <select wire:model="form.agent_id" class="w-full bg-gray-800/50 border-2 border-gray-600 rounded-2xl px-6 py-4 text-lg text-white">
                                        <option value="">🔍 Sélectionner un agent</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($agent->id); ?>"><?php echo e($agent->nom); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>

                                <div class="lg:col-span-3">
                                    <label class="block text-lg font-semibold text-gray-300 mb-3">Date de vente *</label>
                                    <input type="date" wire:model="form.date_vente" class="w-full bg-gray-800/50 border-2 border-gray-600 rounded-2xl px-6 py-4 text-lg text-white focus:ring-4 focus:ring-emerald-500/30" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.date_vente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                        <span class="text-red-400 text-sm mt-2 block font-semibold"><?php echo e($message); ?></span> 
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- LIGNES D'ARTICLES -->
                            <div class="bg-gradient-to-r from-gray-800/70 to-gray-900/50 border-2 border-gray-700/50 rounded-3xl p-8 shadow-2xl">
                                <div class="flex justify-between items-center mb-8">
                                    <h3 class="text-2xl font-bold text-yellow-400 flex items-center">
                                        <i class="fas fa-boxes-stacked mr-3 text-2xl"></i>
                                        Articles vendus
                                    </h3>
                                    <button type="button" wire:click="addLigne" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 px-8 py-3 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all">
                                        ➕ Ajouter ligne
                                    </button>
                                </div>

                                <div class="space-y-4 max-h-96 overflow-y-auto pr-4 -mr-4 pb-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('ligne-{{ $index }}', get_defined_vars()); ?>wire:key="ligne-<?php echo e($index); ?>" class="group bg-gray-800/50 hover:bg-gray-700/50 border-2 border-gray-700/50 hover:border-emerald-500/30 rounded-2xl p-6 transition-all hover:shadow-xl">
                                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                                                <div class="lg:col-span-4">
                                                    <label class="block text-sm font-semibold text-gray-300 mb-3">Article *</label>
                                                    <select wire:model.live="lignes.<?php echo e($index); ?>.article_id" class="w-full bg-gray-900/50 border border-gray-600/50 rounded-xl px-4 py-3 text-base focus:ring-2 focus:ring-emerald-500">
                                                        <option value="">Choisir un article</option>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                            <?php 
                                                                $stock = $stockRestant[$article->id] ?? 0;
                                                                $isSelected = ($ligne['article_id'] ?? '') == $article->id;
                                                            ?>
                                                            <option value="<?php echo e($article->id); ?>" <?php echo e(($stock <= 0 && !$isSelected) ? 'disabled' : ''); ?>>
                                                                <?php echo e($article->nom); ?>

                                                                <span class="ml-2 px-2 py-1 rounded text-xs font-bold
                                                                    <?php echo e($stock > 0 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300'); ?>">
                                                                    <?php echo e($stock > 0 ? "($stock)" : 'épuisé'); ?>

                                                                </span>
                                                            </option>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                    </select>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["lignes.{$index}.article_id"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                                        <span class="text-red-400 text-xs mt-2 block"><?php echo e($message); ?></span> 
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>

                                                <div class="lg:col-span-2">
													<label class="block text-sm font-semibold text-gray-300 mb-3">Quantité (sacs) *</label>
													<?php
														$articleId = $ligne['article_id'] ?? null;
														$stockDispo = $articleId ? ($stockRestant[$articleId] ?? 0) : 0;
														$qteActuelle = (int) ($ligne['quantite'] ?? 1);
														$qteMax = $stockDispo > 0 ? $stockDispo : 1;
														$isOverStock = $qteActuelle > $stockDispo;
													?>
													<div class="relative">
														<input 
															type="number" 
															wire:model.live="lignes.<?php echo e($index); ?>.quantite" 
															min="1" 
															max="<?php echo e($qteMax); ?>"
															class="w-full bg-gray-900/50 border-2 <?php echo e($isOverStock ? 'border-red-500/70 bg-red-500/20 ring-2 ring-red-400/50' : 'border-gray-600/50'); ?> rounded-xl px-6 py-4 text-xl font-bold focus:ring-4 focus:ring-emerald-500/30 shadow-lg pr-20 transition-all"
															placeholder="1-<?php echo e($qteMax); ?>"
														>
														<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stockDispo > 0): ?>
															<div class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-bold <?php echo e($isOverStock ? 'text-red-400 bg-red-900/80' : 'text-orange-400 bg-black/50'); ?> px-3 py-1 rounded-lg border">
																<?php echo e($isOverStock ? 'Max: ' . $stockDispo : 'Stock: ' . $stockDispo); ?>

															</div>
														<?php else: ?>
															<div class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-bold text-red-400 bg-red-900/80 px-3 py-1 rounded-lg border">
																Épuisé
															</div>
														<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
													</div>
													<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["lignes.{$index}.quantite"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
														<span class="text-red-400 text-sm mt-2 block font-semibold bg-red-500/20 px-4 py-2 rounded-xl border-l-4 border-red-400">
															<?php echo e($message); ?>

														</span> 
													<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
												</div>

                                                <div class="lg:col-span-3">
                                                    <label class="block text-sm font-semibold text-gray-300 mb-3">Prix unitaire</label>
                                                    <div class="w-full bg-gradient-to-r from-blue-900/70 to-blue-800/40 border border-blue-600/50 rounded-xl px-6 py-4 text-right text-xl font-bold text-blue-300 shadow-lg">
                                                        <?php echo e(number_format($ligne['prix_unitaire'] ?? 0, 0, ',', ' ')); ?> FCFA
                                                    </div>
                                                </div>

                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-semibold text-gray-300 mb-3">Montant ligne</label>
                                                    <div class="w-full bg-gradient-to-r from-emerald-900/80 to-emerald-800/50 border-2 border-emerald-600/50 rounded-xl px-6 py-4 text-right text-2xl font-black text-emerald-400 shadow-2xl">
                                                        <?php echo e(number_format($ligne['montant'] ?? 0, 0, ',', ' ')); ?> FCFA
                                                    </div>
                                                </div>

                                                <div class="lg:col-span-1 flex justify-end pt-10 lg:pt-0">
                                                    <button type="button" wire:click="removeLigne(<?php echo e($index); ?>)" class="w-14 h-14 group-hover:bg-red-600 bg-red-500/80 hover:bg-red-600 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-xl hover:shadow-2xl transition-all group-hover:scale-110 hover:rotate-12">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <div class="col-span-full text-center py-20 bg-gray-800/30 rounded-2xl border-2 border-dashed border-gray-600/50">
                                            <div class="text-6xl mb-6 text-gray-500">📦</div>
                                            <p class="text-gray-400 text-xl font-semibold mb-2">Aucune ligne ajoutée</p>
                                            <p class="text-gray-500 text-lg">Cliquez sur "Ajouter ligne" pour commencer</p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- TOTAL FACTURE -->
                            <div class="bg-gradient-to-r from-emerald-900/90 to-yellow-900/70 border-4 border-emerald-500/60 rounded-3xl p-12 text-center shadow-2xl backdrop-blur-xl">
                                <div class="text-6xl font-black text-emerald-400 mb-6 animate-pulse drop-shadow-2xl">
                                    <?php echo e(number_format($form['montant_total'], 0, ',', ' ')); ?>

                                </div>
                                <div class="text-3xl font-bold text-yellow-300 uppercase tracking-widest mb-8">
                                    Montant Total Facture
                                </div>
                                <div class="text-2xl font-black text-emerald-300 bg-gradient-to-r from-emerald-500/40 to-yellow-500/30 px-12 py-6 rounded-2xl inline-block border-4 border-emerald-400/50 shadow-2xl">
                                    <?php echo e(number_format($form['montant_total'], 0, ',', ' ')); ?> FCFA
                                </div>
                                <div class="mt-8 p-6 bg-emerald-500/20 border-2 border-emerald-400/40 rounded-2xl">
                                    <p class="text-xl font-bold text-emerald-200">
                                        💳 Facture au crédit - Paiement géré séparément
                                    </p>
                                </div>
                            </div>

                            <!-- BOUTONS ACTION -->
                            <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-6 pt-8 border-t-2 border-emerald-500/30">
                                <button type="button" wire:click="$set('showModal', false)" class="px-12 py-6 bg-gradient-to-r from-gray-700/70 to-gray-800/70 hover:from-gray-600 hover:to-gray-700 text-xl font-bold rounded-2xl transition-all shadow-xl border-2 border-gray-600/50 hover:border-gray-500/50 flex-1 sm:flex-none">
                                    <i class="fas fa-times mr-3"></i>Annuler
                                </button>
                                <button type="submit" class="px-16 py-6 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-xl font-black rounded-2xl shadow-2xl hover:shadow-3xl transition-all transform hover:scale-105 flex-1 sm:flex-none">
                                    <i class="fas fa-save mr-3"></i>Créer Facture
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <style>
        .max-h-96::-webkit-scrollbar { width: 8px; }
        .max-h-96::-webkit-scrollbar-track { 
            background: #1f2937; 
            border-radius: 10px;
            border: 1px solid #374151;
        }
        .max-h-96::-webkit-scrollbar-thumb { 
            background: linear-gradient(to bottom, #10b981, #f59e0b); 
            border-radius: 10px;
            border: 1px solid #059669;
        }
        .max-h-96::-webkit-scrollbar-thumb:hover { 
            background: linear-gradient(to bottom, #059669, #d97706); 
        }
    </style>
</div>
<?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/ventes/index.blade.php ENDPATH**/ ?>