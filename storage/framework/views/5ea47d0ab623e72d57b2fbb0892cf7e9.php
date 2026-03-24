<div class="py-12 bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bannière de bienvenue avec stats rapides -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 overflow-hidden shadow-xl sm:rounded-lg mb-8">
            <div class="p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">
                            Tableau de bord Attagest
                        </h2>
                        <p class="text-emerald-100">
                            Bienvenue <strong><?php echo e(Auth::user()->name); ?></strong>. Voici un aperçu de votre activité.
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-emerald-100">Dernière mise à jour</p>
                        <p class="text-xl font-semibold"><?php echo e(now()->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille de résumés rapides (KPI) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stock Paddy -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400 mb-1">Stock Paddy</p>
                        <p class="text-2xl font-bold text-emerald-400">
                            <?php echo e(number_format($stockPaddyKg ?? 0, 0, ',', ' ')); ?> kg
                        </p>
                    </div>
                    <div class="bg-emerald-900/30 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                </div>
                <a href="<?php echo e(route('stocks-paddy.index')); ?>" class="mt-3 inline-flex items-center text-xs text-emerald-400 hover:text-emerald-300">
                    Voir détails
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Stock Produits finis -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400 mb-1">Produits finis</p>
                        <p class="text-2xl font-bold text-emerald-400">
                            <?php echo e(number_format($stockProduitsFinisKg ?? 0, 0, ',', ' ')); ?> kg
                        </p>
                        <p class="text-xs text-slate-500 mt-1"><?php echo e($nbProduitsFinis ?? 0); ?> article(s)</p>
                    </div>
                    <div class="bg-emerald-900/30 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <a href="<?php echo e(route('stocks-produits-finis')); ?>" class="mt-3 inline-flex items-center text-xs text-emerald-400 hover:text-emerald-300">
                    Voir détails
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Ventes du mois -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400 mb-1">Ventes du mois</p>
                        <p class="text-2xl font-bold text-emerald-400">
                            <?php echo e(number_format($ventesMois ?? 0, 0, ',', ' ')); ?> F
                        </p>
                        <p class="text-xs text-slate-500 mt-1"><?php echo e($nbVentesMois ?? 0); ?> facture(s)</p>
                    </div>
                    <div class="bg-emerald-900/30 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <a href="<?php echo e(route('ventes')); ?>" class="mt-3 inline-flex items-center text-xs text-emerald-400 hover:text-emerald-300">
                    Voir détails
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Achats du mois -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-400 mb-1">Achats du mois</p>
                        <p class="text-2xl font-bold text-emerald-400">
                            <?php echo e(number_format($achatsMois ?? 0, 0, ',', ' ')); ?> F
                        </p>
                        <p class="text-xs text-slate-500 mt-1"><?php echo e($nbAchatsMois ?? 0); ?> achat(s)</p>
                    </div>
                    <div class="bg-emerald-900/30 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <a href="<?php echo e(route('achats-paddy.index')); ?>" class="mt-3 inline-flex items-center text-xs text-emerald-400 hover:text-emerald-300">
                    Voir détails
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Accès rapides (raccourcis) -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-slate-100 mb-4">Accès rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <a href="<?php echo e(route('achats-paddy.index')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">📦</span>
                    <span class="text-xs text-slate-300">Achats Paddy</span>
                </a>
                <a href="<?php echo e(route('stocks-paddy.index')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">📊</span>
                    <span class="text-xs text-slate-300">Stocks Paddy</span>
                </a>
                <a href="<?php echo e(route('ventes')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">💰</span>
                    <span class="text-xs text-slate-300">Ventes</span>
                </a>
                <a href="<?php echo e(route('factures-clients')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">📄</span>
                    <span class="text-xs text-slate-300">Factures</span>
                </a>
                <a href="<?php echo e(route('recus-fournisseurs.crud')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">🧾</span>
                    <span class="text-xs text-slate-300">Reçus fournisseurs</span>
                </a>
                <a href="<?php echo e(route('agents')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">👥</span>
                    <span class="text-xs text-slate-300">Agents</span>
                </a>
                <a href="<?php echo e(route('clients.index')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">🤝</span>
                    <span class="text-xs text-slate-300">Clients</span>
                </a>
                <a href="<?php echo e(route('fournisseurs.index')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">🚚</span>
                    <span class="text-xs text-slate-300">Fournisseurs</span>
                </a>
                <a href="<?php echo e(route('bilans.globaux')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">📈</span>
                    <span class="text-xs text-slate-300">Bilans globaux</span>
                </a>
                <a href="<?php echo e(route('rapports')); ?>" class="bg-slate-800 border border-slate-700 rounded-lg p-3 text-center hover:bg-slate-700 transition">
                    <span class="block text-2xl mb-1">📑</span>
                    <span class="text-xs text-slate-300">Rapports</span>
                </a>
				
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->is_super_admin): ?>
        <div class="mt-6">
            <a href="<?php echo e(route('admin.panel')); ?>" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-purple-500/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                ⚙️ Panneau d'administration
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section activité récente -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Derniers achats -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-lg">
                <div class="p-5 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-100">Derniers achats Paddy</h3>
                    <a href="<?php echo e(route('achats-paddy.index')); ?>" class="text-xs text-emerald-400 hover:text-emerald-300">Voir tout</a>
                </div>
                <div class="p-5">
                    <ul class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $derniersAchats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $achat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-slate-300"><?php echo e($achat->created_at->format('d/m')); ?> • <?php echo e($achat->fournisseur?->nom ?? '-'); ?></span>
                                <span class="font-mono text-emerald-400"><?php echo e(number_format($achat->montant_achat_total_fcfa, 0, ',', ' ')); ?> F</span>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-sm text-slate-400">Aucun achat récent.</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Dernières ventes -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-lg">
                <div class="p-5 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-100">Dernières ventes</h3>
                    <a href="<?php echo e(route('ventes')); ?>" class="text-xs text-emerald-400 hover:text-emerald-300">Voir tout</a>
                </div>
                <div class="p-5">
                    <ul class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $dernieresVentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li class="flex items-center justify-between text-sm">
                                <span class="text-slate-300"><?php echo e($vente->created_at->format('d/m')); ?> • <?php echo e($vente->client?->nom ?? '-'); ?></span>
                                <span class="font-mono text-emerald-400"><?php echo e(number_format($vente->montant_vente_total_fcfa, 0, ',', ' ')); ?> F</span>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-sm text-slate-400">Aucune vente récente.</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section graphiques (placeholder amélioré) -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl shadow-lg">
            <div class="p-5 border-b border-slate-700">
                <h3 class="text-lg font-semibold text-slate-100">Évolution des ventes (30 derniers jours)</h3>
            </div>
            <div class="p-5">
                <div class="h-64 flex items-center justify-center bg-slate-700/50 rounded border border-slate-600">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-slate-400 text-sm">Graphique à implémenter avec Chart.js ou autre</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\diexa\attagest\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>