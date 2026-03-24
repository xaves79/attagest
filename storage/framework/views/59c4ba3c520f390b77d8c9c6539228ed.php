<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bilan global ATTAGEST</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #1e293b; background: white; padding: 20px 25px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #4f46e5; }
        .company-name { font-size: 16pt; font-weight: bold; color: #4f46e5; }
        .company-info { font-size: 8pt; color: #64748b; line-height: 1.6; margin-top: 4px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 18pt; font-weight: bold; color: #4f46e5; letter-spacing: 1px; }
        .doc-title .periode { font-size: 9pt; color: #64748b; margin-top: 4px; }
        .doc-title .genere { font-size: 8pt; color: #94a3b8; margin-top: 2px; }

        h2 { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 6px 10px; margin: 20px 0 10px 0; border-radius: 4px; }
        .h2-achats    { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }
        .h2-prod      { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
        .h2-ventes    { background: #dcfce7; color: #166534; border-left: 4px solid #22c55e; }
        .h2-treso     { background: #ede9fe; color: #5b21b6; border-left: 4px solid #8b5cf6; }

        .kpis { display: flex; gap: 8px; margin-bottom: 15px; }
        .kpi { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; text-align: center; }
        .kpi .label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 4px; }
        .kpi .value { font-size: 14pt; font-weight: bold; color: #1e293b; line-height: 1.1; }
        .kpi .unit  { font-size: 7pt; color: #94a3b8; }
        .kpi.highlight .value { color: #4f46e5; }
        .kpi.green .value { color: #166534; }
        .kpi.amber .value { color: #92400e; }
        .kpi.red .value { color: #dc2626; }

        table { width: 100%; border-collapse: collapse; margin: 10px 0 20px 0; font-size: 8.5pt; }
        thead tr { background: #334155; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        thead th.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tbody td.right { text-align: right; }
        tbody td.green { color: #166534; font-weight: bold; }
        tbody td.amber { color: #92400e; font-weight: bold; }
        tbody td.blue  { color: #1e40af; font-weight: bold; }

        .footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 7.5pt; color: #94a3b8; }
        .flux-box { background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 12px 16px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center; }
        .flux-box .flux-label { font-size: 9pt; font-weight: bold; color: #166534; }
        .flux-box .flux-value { font-size: 14pt; font-weight: bold; color: #166534; }
    </style>
</head>
<body>

    
    <div class="header">
        <div>
            <div class="company-name"><?php echo e($entreprise->nom ?? 'ATTAGEST SARL'); ?></div>
            <div class="company-info">
                <?php echo e($entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire'); ?><br>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->telephone ?? null): ?> Tél : <?php echo e($entreprise->telephone); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->rccm ?? null): ?> RCCM : <?php echo e($entreprise->rccm); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="doc-title">
            <h1>BILAN GLOBAL</h1>
            <div class="periode">
                Du <?php echo e(\Carbon\Carbon::parse($date_debut)->format('d/m/Y')); ?>

                au <?php echo e(\Carbon\Carbon::parse($date_fin)->format('d/m/Y')); ?>

            </div>
            <div class="genere">Généré le <?php echo e(now()->format('d/m/Y à H:i')); ?></div>
        </div>
    </div>

    
    <h2 class="h2-achats">🌾 Achats paddy</h2>
    <div class="kpis">
        <div class="kpi amber">
            <div class="label">Lots achetés</div>
            <div class="value"><?php echo e($achats->nb ?? 0); ?></div>
        </div>
        <div class="kpi amber">
            <div class="label">Quantité totale</div>
            <div class="value"><?php echo e(number_format($achats->total_kg ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">kg</div>
        </div>
        <div class="kpi amber">
            <div class="label">Montant total</div>
            <div class="value"><?php echo e(number_format($achats->total_fcfa ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA</div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($achatsFournisseurs->isNotEmpty()): ?>
    <table>
        <thead><tr>
            <th>Fournisseur</th>
            <th class="right">Nb lots</th>
            <th class="right">Quantité (kg)</th>
            <th class="right">Montant (FCFA)</th>
        </tr></thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $achatsFournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td><?php echo e($f->nom); ?></td>
                <td class="right"><?php echo e($f->nb_achats); ?></td>
                <td class="right amber"><?php echo e(number_format($f->total_kg, 0, ',', ' ')); ?></td>
                <td class="right"><?php echo e(number_format($f->total_fcfa, 0, ',', ' ')); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <h2 class="h2-prod">🏭 Production</h2>
    <div class="kpis">
        <div class="kpi blue">
            <div class="label">Paddy étuvé</div>
            <div class="value"><?php echo e(number_format($etuvages->total_entree ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">kg · <?php echo e($etuvages->nb ?? 0); ?> étuvage(s)</div>
        </div>
        <div class="kpi blue">
            <div class="label">Riz étuvé produit</div>
            <div class="value"><?php echo e(number_format($rizEtuve->total_kg ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">kg · Rdt : <?php echo e(number_format($rizEtuve->rdt_moy ?? 0, 1)); ?>%</div>
        </div>
        <div class="kpi green">
            <div class="label">Riz blanc produit</div>
            <div class="value"><?php echo e(number_format($decorticages->total_blanc ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">kg · <?php echo e($decorticages->nb ?? 0); ?> décorticage(s)</div>
        </div>
        <div class="kpi">
            <div class="label">Rendement décorti.</div>
            <div class="value"><?php echo e(number_format($decorticages->rdt_moy ?? 0, 1)); ?>%</div>
            <div class="unit">Son : <?php echo e(number_format($decorticages->total_son ?? 0, 0, ',', ' ')); ?> kg</div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prodParVariete->isNotEmpty()): ?>
    <table>
        <thead><tr>
            <th>Variété</th>
            <th class="right">Riz blanc (kg)</th>
            <th class="right">Son (kg)</th>
            <th class="right">Rendement moy.</th>
        </tr></thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $prodParVariete; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td><?php echo e($v->variete); ?></td>
                <td class="right green"><?php echo e(number_format($v->riz_blanc_kg, 0, ',', ' ')); ?></td>
                <td class="right amber"><?php echo e(number_format($v->son_kg, 0, ',', ' ')); ?></td>
                <td class="right"><?php echo e(number_format($v->rdt_moy, 1)); ?>%</td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <h2 class="h2-ventes">🛒 Ventes</h2>
    <div class="kpis">
        <div class="kpi green">
            <div class="label">Commandes</div>
            <div class="value"><?php echo e($commandes->nb ?? 0); ?></div>
            <div class="unit"><?php echo e($commandes->nb_comptant ?? 0); ?> comptant · <?php echo e($commandes->nb_credit ?? 0); ?> crédit</div>
        </div>
        <div class="kpi green">
            <div class="label">CA total</div>
            <div class="value"><?php echo e(number_format($commandes->total_fcfa ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA</div>
        </div>
        <div class="kpi green">
            <div class="label">Encaissé clients</div>
            <div class="value"><?php echo e(number_format($paiementsClients->total_encaisse ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA · <?php echo e($paiementsClients->nb ?? 0); ?> paiement(s)</div>
        </div>
        <div class="kpi <?php echo e(($soldesClients->total_solde ?? 0) > 0 ? 'red' : 'green'); ?>">
            <div class="label">Soldes clients dus</div>
            <div class="value"><?php echo e(number_format($soldesClients->total_solde ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA · <?php echo e($soldesClients->nb_factures ?? 0); ?> facture(s)</div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ventesClients->isNotEmpty()): ?>
    <table>
        <thead><tr>
            <th>Client</th>
            <th class="right">Nb commandes</th>
            <th class="right">Montant (FCFA)</th>
        </tr></thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ventesClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td><?php echo e($c->raison_sociale ?: $c->nom); ?></td>
                <td class="right"><?php echo e($c->nb_commandes); ?></td>
                <td class="right green"><?php echo e(number_format($c->total_fcfa, 0, ',', ' ')); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <h2 class="h2-treso">💰 Trésorerie</h2>
    <div class="kpis">
        <div class="kpi green">
            <div class="label">Encaissé clients</div>
            <div class="value">+ <?php echo e(number_format($paiementsClients->total_encaisse ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA</div>
        </div>
        <div class="kpi red">
            <div class="label">Payé fournisseurs</div>
            <div class="value">- <?php echo e(number_format($paiementsFournisseurs->total_paye ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA</div>
        </div>
        <div class="kpi <?php echo e(($soldesFournisseurs->total_solde ?? 0) > 0 ? 'amber' : 'green'); ?>">
            <div class="label">Solde dû fournisseurs</div>
            <div class="value"><?php echo e(number_format($soldesFournisseurs->total_solde ?? 0, 0, ',', ' ')); ?></div>
            <div class="unit">FCFA · <?php echo e($soldesFournisseurs->nb_recus ?? 0); ?> reçu(s)</div>
        </div>
    </div>

    <?php $flux = ($paiementsClients->total_encaisse ?? 0) - ($paiementsFournisseurs->total_paye ?? 0); ?>
    <div class="flux-box" style="<?php echo e($flux < 0 ? 'background:#fef2f2;border-color:#fca5a5;' : ''); ?>">
        <div class="flux-label" style="<?php echo e($flux < 0 ? 'color:#dc2626;' : ''); ?>">Flux net de trésorerie sur la période</div>
        <div class="flux-value" style="<?php echo e($flux < 0 ? 'color:#dc2626;' : ''); ?>">
            <?php echo e($flux >= 0 ? '+' : ''); ?><?php echo e(number_format($flux, 0, ',', ' ')); ?> FCFA
        </div>
    </div>

    
    <div class="footer">
        <span><?php echo e($entreprise->nom ?? 'ATTAGEST SARL'); ?> — <?php echo e($entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire'); ?></span>
        <span>Document confidentiel — Usage interne</span>
    </div>

</body>
</html><?php /**PATH C:\Users\diexa\attagest\resources\views/pdf/bilan-global.blade.php ENDPATH**/ ?>