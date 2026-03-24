<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="bg-green-800 border border-green-600 text-green-200 px-4 py-3 rounded mb-6 animate-pulse">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold">Agents</h2>
            <p class="text-gray-400 mt-1">
                <?php echo e($agents->total()); ?> agent<?php echo e($agents->total() > 1 ? 's' : ''); ?> trouvé<?php echo e($agents->total() > 1 ? 's' : ''); ?>

            </p>
        </div>
        <button
            wire:click="create"
            class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-200"
        >
            ➕ Nouvel agent
        </button>
    </div>

    
    <div class="mb-8">
        <div class="relative max-w-md">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Rechercher par nom, prénom ou matricule..."
                class="w-full bg-gray-800/50 border border-gray-600 rounded-xl px-5 py-3 text-white pl-12 focus:border-green-500 focus:ring-2 focus:ring-green-500/50 transition-all backdrop-blur-sm"
            />
            <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($agents->isEmpty()): ?>
        <div class="bg-gray-800 rounded-xl p-12 shadow-2xl text-center border-2 border-dashed border-gray-600">
            <div class="text-6xl mb-4">👥</div>
            <h3 class="text-2xl font-bold mb-2">Aucun agent trouvé</h3>
            <p class="text-gray-400 mb-6">Créez votre premier agent ou vérifiez vos critères de recherche.</p>
            <button
                wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold"
            >
                Ajouter un agent
            </button>
        </div>
    <?php else: ?>
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-900 border-b-2 border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Photo</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Matricule</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Nom complet</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Poste</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Entreprise</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Contact</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-200">Embauché le</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200">Actif</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-200 w-52">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr class="hover:bg-gray-800/50 transition-all duration-200 group">
                                <td class="px-6 py-4">
                                    <img src="<?php echo e($a->photo_url); ?>" alt="Photo" class="w-10 h-10 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 font-mono"><?php echo e($a->matricule); ?></td>
                                <td class="px-6 py-4">
                                    <?php echo e($a->nom_complet ?? ($a->prenom . ' ' . $a->nom)); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($a->poste?->libelle ?? '-'); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($a->entreprise?->nom ?? '-'); ?>

                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($a->telephone): ?>
                                        <div><?php echo e($a->telephone); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($a->whatsapp): ?>
                                        <div class="text-green-400">WhatsApp: <?php echo e($a->whatsapp); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($a->email): ?>
                                        <div class="text-blue-400"><?php echo e($a->email); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($a->date_embauche?->format('d/m/Y') ?? '-'); ?>

                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?php echo e($a->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($a->actif ? 'Actif' : 'Inactif'); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center space-x-1">
                                    <button
                                        wire:click="show(<?php echo e($a->id); ?>)"
                                        class="px-3 py-1.5 bg-blue-600/90 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Voir"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="edit(<?php echo e($a->id); ?>)"
                                        class="px-3 py-1.5 bg-yellow-600/90 hover:bg-yellow-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Modifier"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="delete(<?php echo e($a->id); ?>)"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cet agent ?"
                                        class="px-3 py-1.5 bg-red-600/90 hover:bg-red-500 text-white text-xs font-semibold rounded-lg shadow-md hover:shadow-lg transition-all"
                                        title="Supprimer"
                                    >
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center text-sm text-gray-400">
            <div>
                <?php echo e(($agents->currentPage() - 1) * $agents->perPage() + 1); ?> -
                <?php echo e(min($agents->currentPage() * $agents->perPage(), $agents->total())); ?>

                sur <?php echo e($agents->total()); ?>

            </div>
            <div><?php echo e($agents->links()); ?></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
            <div class="bg-gray-900 border border-gray-700 rounded-lg shadow-xl max-w-4xl w-full p-6 mx-4 text-white max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4">
                    <?php echo e($viewMode ? 'Détails de l\'agent' : ($form['id'] ? 'Modifier l\'agent' : 'Nouvel agent')); ?>

                </h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($viewMode): ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2 flex justify-center mb-4">
                            <?php
                                $agent = $agents->firstWhere('id', $form['id']);
                            ?>
                            <img src="<?php echo e($agent?->photo_url ?? asset('images/default-avatar.png')); ?>" alt="Photo" class="w-32 h-32 rounded-full object-cover border-4 border-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Matricule</label>
                            <p class="mt-1"><?php echo e($form['matricule']); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Nom</label>
                            <p class="mt-1"><?php echo e($form['nom']); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Prénom</label>
                            <p class="mt-1"><?php echo e($form['prenom']); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Poste</label>
                            <p class="mt-1"><?php echo e($postes->find($form['poste_id'])?->libelle ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Entreprise</label>
                            <p class="mt-1"><?php echo e($entreprises->find($form['entreprise_id'])?->nom ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Téléphone</label>
                            <p class="mt-1"><?php echo e($form['telephone'] ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">WhatsApp</label>
                            <p class="mt-1"><?php echo e($form['whatsapp'] ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Email</label>
                            <p class="mt-1"><?php echo e($form['email'] ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Date embauche</label>
                            <p class="mt-1"><?php echo e($form['date_embauche'] ? \Carbon\Carbon::parse($form['date_embauche'])->format('d/m/Y') : '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400">Actif</label>
                            <p class="mt-1"><?php echo e($form['actif'] ? 'Oui' : 'Non'); ?></p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-400">Nom complet (affiché)</label>
                            <p class="mt-1"><?php echo e($form['nom_complet'] ?? ($form['prenom'] . ' ' . $form['nom'])); ?></p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button
                            type="button"
                            wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600"
                        >
                            Fermer
                        </button>
                    </div>
                <?php else: ?>
                    
                    <form wire:submit.prevent="save" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Nom *</label>
                                <input
                                    type="text"
                                    wire:model="form.nom"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-sm text-red-400"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Prénom *</label>
                                <input
                                    type="text"
                                    wire:model="form.prenom"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    required
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-sm text-red-400"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Matricule *</label>
                                <input
                                    type="text"
                                    wire:model="form.matricule"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    <?php echo e($form['id'] ? 'readonly' : ''); ?>

                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.matricule'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-sm text-red-400"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">WhatsApp</label>
                                <input
                                    type="text"
                                    wire:model="form.whatsapp"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Téléphone</label>
                                <input
                                    type="text"
                                    wire:model="form.telephone"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Email</label>
                                <input
                                    type="email"
                                    wire:model="form.email"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Photo</label>
                                <input
                                    type="file"
                                    wire:model="photo"
                                    wire:loading.attr="disabled"
                                    accept="image/*"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                                >
                                <div wire:loading wire:target="photo" class="text-sm text-blue-400 mt-1">
                                    ⏳ Téléchargement en cours...
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-sm text-red-400"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photo): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo e($photo->temporaryUrl()); ?>" class="h-20 rounded-lg">
                                    </div>
                                <?php elseif($form['photo']): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo e(asset('storage/' . $form['photo'])); ?>" class="h-20 rounded-lg">
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Entreprise</label>
                                <select
                                    wire:model="form.entreprise_id"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                                    <option value="">Sélectionner</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $entreprises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($e->id); ?>"><?php echo e($e->nom); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Date d'embauche</label>
                                <input
                                    type="date"
                                    wire:model="form.date_embauche"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Actif</label>
                                <select
                                    wire:model="form.actif"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                                    <option value="1">Oui</option>
                                    <option value="0">Non</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Poste</label>
                                <select
                                    wire:model="form.poste_id"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                                    <option value="">Sélectionner</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $postes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->libelle); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>

                            <div class="lg:col-span-3">
                                <label class="block text-sm font-medium text-gray-300">Nom complet (optionnel, sinon nom + prénom)</label>
                                <input
                                    type="text"
                                    wire:model="form.nom_complet"
                                    class="mt-1 block w-full rounded-md border-gray-600 bg-gray-800 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                >
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                type="button"
                                wire:click="$set('showModal', false)"
                                class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                Annuler
                            </button>
                            
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <?php echo e($form['id'] ? 'Mettre à jour' : 'Créer'); ?>

                            </button>
                        </div>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/agents/index.blade.php ENDPATH**/ ?>