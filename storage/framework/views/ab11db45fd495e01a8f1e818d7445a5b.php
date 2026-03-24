<div class="bg-slate-900 min-h-screen text-slate-100 p-6">
    <div class="max-w-screen-2xl mx-auto">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
            <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <span class="text-3xl">🤝</span> Fournisseurs
            </h2>
            <button
                wire:click="create"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau fournisseur
            </button>
        </div>

        
        <div class="mb-6">
            <div class="relative max-w-md">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par nom, code ou email..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                />
                <svg class="absolute left-3 top-3 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fournisseurs->isEmpty()): ?>
            <div class="bg-slate-800 rounded-xl p-12 text-center border-2 border-dashed border-slate-600">
                <div class="text-6xl mb-4">🤝</div>
                <h3 class="text-2xl font-bold mb-2">Aucun fournisseur trouvé</h3>
                <p class="text-slate-400 mb-6">Créez votre premier fournisseur ou modifiez votre recherche.</p>
                <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold">
                    + Nouveau fournisseur
                </button>
            </div>
        <?php else: ?>
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-slate-200 border-b border-slate-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Nom / Raison sociale</th>
                                <th class="px-4 py-3 text-left font-semibold">Contact</th>
                                <th class="px-4 py-3 text-left font-semibold">Localité</th>
                                <th class="px-4 py-3 text-left font-semibold">Type</th>
                                <th class="px-4 py-3 text-center font-semibold w-48">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr class="hover:bg-slate-700/30 transition-colors">
                                    <td class="px-4 py-3 font-mono text-blue-400"><?php echo e($f->code_fournisseur); ?></td>
                                    <td class="px-4 py-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f->type_personne == 'PHYSIQUE'): ?>
                                            <?php echo e($f->prenom); ?> <?php echo e($f->nom); ?>

                                        <?php else: ?>
                                            <?php echo e($f->raison_sociale); ?> <?php echo e($f->sigle ? '('.$f->sigle.')' : ''); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f->telephone): ?><div><?php echo e($f->telephone); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f->whatsapp): ?><div class="text-green-400 text-xs">WhatsApp: <?php echo e($f->whatsapp); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($f->email): ?><div class="text-blue-400 text-xs"><?php echo e($f->email); ?></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3"><?php echo e($f->localite?->nom ?? '-'); ?></td>
                                    <td class="px-4 py-3"><?php echo e($f->type_fournisseur); ?></td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="show(<?php echo e($f->id); ?>)" class="p-1.5 bg-blue-600/90 hover:bg-blue-500 text-white rounded-md transition" title="Voir">
                                                👁️
                                            </button>
                                            <button wire:click="edit(<?php echo e($f->id); ?>)" class="p-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white rounded-md transition" title="Modifier">
                                                ✏️
                                            </button>
                                            <button wire:click="delete(<?php echo e($f->id); ?>)" wire:confirm="Supprimer ce fournisseur ?" class="p-1.5 bg-red-600/90 hover:bg-red-500 text-white rounded-md transition" title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    <?php echo e(($fournisseurs->currentPage() - 1) * $fournisseurs->perPage() + 1); ?> - <?php echo e(min($fournisseurs->currentPage() * $fournisseurs->perPage(), $fournisseurs->total())); ?> sur <?php echo e($fournisseurs->total()); ?>

                </div>
                <div><?php echo e($fournisseurs->links()); ?></div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">
                <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-white mb-4">
                            <?php echo e($viewMode ? 'Détails du fournisseur' : ($form['id'] ? 'Modifier le fournisseur' : 'Nouveau fournisseur')); ?>

                        </h3>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($viewMode): ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div><label class="block text-xs text-slate-400">Code</label><p class="text-white"><?php echo e($form['code_fournisseur']); ?></p></div>
                                <div><label class="block text-xs text-slate-400">Type personne</label><p class="text-white"><?php echo e($form['type_personne']); ?></p></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($form['type_personne'] == 'PHYSIQUE'): ?>
                                    <div><label class="block text-xs text-slate-400">Nom</label><p class="text-white"><?php echo e($form['nom']); ?></p></div>
                                    <div><label class="block text-xs text-slate-400">Prénom</label><p class="text-white"><?php echo e($form['prenom']); ?></p></div>
                                <?php else: ?>
                                    <div><label class="block text-xs text-slate-400">Raison sociale</label><p class="text-white"><?php echo e($form['raison_sociale']); ?></p></div>
                                    <div><label class="block text-xs text-slate-400">Sigle</label><p class="text-white"><?php echo e($form['sigle']); ?></p></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div><label class="block text-xs text-slate-400">Téléphone</label><p class="text-white"><?php echo e($form['telephone'] ?? '-'); ?></p></div>
                                <div><label class="block text-xs text-slate-400">WhatsApp</label><p class="text-white"><?php echo e($form['whatsapp'] ?? '-'); ?></p></div>
                                <div><label class="block text-xs text-slate-400">Email</label><p class="text-white"><?php echo e($form['email'] ?? '-'); ?></p></div>
                                <div><label class="block text-xs text-slate-400">Localité</label><p class="text-white"><?php echo e($localites->firstWhere('id', $form['localite_id'])?->nom ?? '-'); ?></p></div>
                                <div><label class="block text-xs text-slate-400">Type fournisseur</label><p class="text-white"><?php echo e($form['type_fournisseur'] ?? '-'); ?></p></div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500">
                                    Fermer
                                </button>
                            </div>
                        <?php else: ?>
                            
                            <form wire:submit.prevent="save" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Type personne -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Type *</label>
                                        <select wire:model.live="form.type_personne" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="PHYSIQUE">Personne physique</option>
                                            <option value="MORALE">Personne morale</option>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.type_personne'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-400"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <!-- Code fournisseur -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Code *</label>
                                        <input type="text" wire:model="form.code_fournisseur" readonly class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.code_fournisseur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-400"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <!-- Champs conditionnels avec wire:key pour forcer le re-rendu -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($form['type_personne'] == 'PHYSIQUE'): ?>
                                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('physique-nom', get_defined_vars()); ?>wire:key="physique-nom">
                                            <label class="block text-sm font-medium text-slate-300">Nom *</label>
                                            <input type="text" wire:model="form.nom" required class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-400"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('physique-prenom', get_defined_vars()); ?>wire:key="physique-prenom">
                                            <label class="block text-sm font-medium text-slate-300">Prénom</label>
                                            <input type="text" wire:model="form.prenom" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                    <?php else: ?>
                                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('morale-raison', get_defined_vars()); ?>wire:key="morale-raison">
                                            <label class="block text-sm font-medium text-slate-300">Raison sociale *</label>
                                            <input type="text" wire:model="form.raison_sociale" required class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.raison_sociale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-400"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('morale-sigle', get_defined_vars()); ?>wire:key="morale-sigle">
                                            <label class="block text-sm font-medium text-slate-300">Sigle</label>
                                            <input type="text" wire:model="form.sigle" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.sigle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-xs text-red-400"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Téléphone -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Téléphone</label>
                                        <input type="text" wire:model="form.telephone" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- WhatsApp -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">WhatsApp</label>
                                        <input type="text" wire:model="form.whatsapp" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Email</label>
                                        <input type="email" wire:model="form.email" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Localité -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Localité</label>
                                        <select wire:model="form.localite_id" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">Sélectionner</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $localites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <option value="<?php echo e($localite->id); ?>"><?php echo e($localite->nom); ?> (<?php echo e($localite->region); ?>)</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- Type fournisseur -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300">Type fournisseur</label>
                                        <input type="text" wire:model="form.type_fournisseur" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Ex: Producteur, Grossiste">
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500 transition">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        <?php echo e($form['id'] ? 'Mettre à jour' : 'Créer'); ?>

                                    </button>
                                </div>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/fournisseurs/index.blade.php ENDPATH**/ ?>