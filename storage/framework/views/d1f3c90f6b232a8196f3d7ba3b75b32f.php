<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visible && count($alertes) > 0): ?>
        <div class="bg-red-950/80 border-b border-red-700/50 px-6 py-2.5">
            <div class="max-w-screen-2xl mx-auto flex items-center gap-4 flex-wrap">
                <span class="text-red-400 font-bold text-xs flex items-center gap-1.5 flex-shrink-0">
                    ⚠️ Stocks critiques :
                </span>
                <div class="flex items-center gap-2 flex-wrap flex-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $alertes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alerte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <a href="<?php echo e(route($alerte['route'])); ?>"
                           class="flex items-center gap-1.5 px-2.5 py-1 bg-red-900/60 hover:bg-red-900 border border-red-700/50 rounded-lg text-xs text-red-300 hover:text-white transition">
                            <span><?php echo e($alerte['icon']); ?></span>
                            <span class="font-semibold"><?php echo e($alerte['label']); ?></span>
                            <span class="text-red-500"><?php echo e(number_format($alerte['stock'], 0, ',', ' ')); ?> kg</span>
                            <span class="text-red-600">/</span>
                            <span class="text-red-600"><?php echo e(number_format($alerte['seuil'], 0, ',', ' ')); ?> kg</span>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="<?php echo e(route('parametres.index')); ?>"
                       class="text-xs text-red-400 hover:text-red-300 underline transition">
                        Modifier seuils
                    </a>
                    <button wire:click="dismiss"
                            class="text-red-600 hover:text-red-400 transition text-sm leading-none">✕</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/alertes-stock.blade.php ENDPATH**/ ?>