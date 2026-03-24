<div class="min-h-screen bg-slate-950 text-white">

    <div class="sticky top-0 z-10 bg-slate-900/95 backdrop-blur border-b border-slate-700/60">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-sm">📋</div>
                <div>
                    <h1 class="text-sm font-bold text-white leading-none">Journal des intervenants</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Traçabilité complète des actions sur le système</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        
        <?php
            $actionConfig = [
                'creation'     => ['🟢', 'text-green-400',  'Créations'],
                'modification' => ['🟡', 'text-yellow-400', 'Modifications'],
                'suppression'  => ['🔴', 'text-red-400',    'Suppressions'],
                'paiement'     => ['🔵', 'text-blue-400',   'Paiements'],
                'connexion'    => ['⚪', 'text-slate-400',  'Connexions'],
                'export'       => ['🟣', 'text-purple-400', 'Exports'],
            ];
        ?>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $actionConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="bg-slate-900 border border-slate-700/60 rounded-xl p-3 text-center">
                <p class="text-lg mb-1"><?php echo e($cfg[0]); ?></p>
                <p class="text-xl font-black <?php echo e($cfg[1]); ?>"><?php echo e($stats->get($action)?->nb ?? 0); ?></p>
                <p class="text-xs text-slate-500"><?php echo e($cfg[2]); ?></p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl p-4 flex flex-wrap gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="🔍 Description, utilisateur..."
                   class="flex-1 min-w-48 bg-slate-800 border border-slate-600 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
            <select wire:model.live="filtreModule"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
                <option value="">Tous modules</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($m); ?>"><?php echo e(ucfirst($m)); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <select wire:model.live="filtreAction"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
                <option value="">Toutes actions</option>
                <option value="creation">🟢 Créations</option>
                <option value="modification">🟡 Modifications</option>
                <option value="suppression">🔴 Suppressions</option>
                <option value="paiement">🔵 Paiements</option>
                <option value="connexion">⚪ Connexions</option>
                <option value="export">🟣 Exports</option>
            </select>
            <select wire:model.live="filtreUser"
                    class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
                <option value="">Tous utilisateurs</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
            <input type="date" wire:model.live="filtreDateDeb"
                   class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
            <input type="date" wire:model.live="filtreDateFin"
                   class="bg-slate-800 border border-slate-600 text-white rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-cyan-500 transition">
        </div>

        
        <div class="bg-slate-900 border border-slate-700/60 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/60 text-xs text-slate-400 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Date/Heure</th>
                        <th class="text-left px-5 py-3">Utilisateur</th>
                        <th class="text-left px-5 py-3">Action</th>
                        <th class="text-left px-5 py-3">Module</th>
                        <th class="text-left px-5 py-3" style="width:40%">Description</th>
                        <th class="text-left px-5 py-3">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php
                        $actionBadge = [
                            'creation'     => 'bg-green-900/50 text-green-300 border-green-700/50',
                            'modification' => 'bg-yellow-900/50 text-yellow-300 border-yellow-700/50',
                            'suppression'  => 'bg-red-900/50 text-red-300 border-red-700/50',
                            'paiement'     => 'bg-blue-900/50 text-blue-300 border-blue-700/50',
                            'connexion'    => 'bg-slate-800 text-slate-400 border-slate-700',
                            'export'       => 'bg-purple-900/50 text-purple-300 border-purple-700/50',
                        ];
                        $roleBadge = [
                            'admin'      => 'text-red-400',
                            'dg'         => 'text-amber-400',
                            'comptable'  => 'text-violet-400',
                            'commercial' => 'text-green-400',
                            'production' => 'text-blue-400',
                            'magasinier' => 'text-teal-400',
                            'operateur'  => 'text-slate-400',
                        ];
                    ?>
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-5 py-3 text-xs text-slate-500">
                            <?php echo e(\Carbon\Carbon::parse($a->created_at)->format('d/m/Y')); ?><br>
                            <span class="text-slate-600"><?php echo e(\Carbon\Carbon::parse($a->created_at)->format('H:i:s')); ?></span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-slate-300 text-xs font-semibold"><?php echo e($a->user_name ?? 'Système'); ?></p>
                            <p class="text-xs <?php echo e($roleBadge[$a->user_role] ?? 'text-slate-500'); ?>"><?php echo e(ucfirst($a->user_role)); ?></p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs border <?php echo e($actionBadge[$a->action] ?? 'bg-slate-800 text-slate-400 border-slate-700'); ?>">
                                <?php echo e(ucfirst($a->action)); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-mono text-cyan-400"><?php echo e($a->module); ?></span>
                        </td>
                        <td class="px-5 py-3 text-slate-300 text-xs"><?php echo e($a->description); ?></td>
                        <td class="px-5 py-3 text-xs text-slate-600 font-mono"><?php echo e($a->ip_address); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <tr><td colspan="6" class="px-5 py-16 text-center text-slate-600">
                        <p class="text-4xl mb-3">📋</p>
                        <p class="text-sm">Aucune activité enregistrée pour cette période.</p>
                    </td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activites->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-800"><?php echo e($activites->links()); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/journal-intervenants.blade.php ENDPATH**/ ?>