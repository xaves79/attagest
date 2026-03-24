<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture <?php echo e($facture->numero_facture); ?></title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .entreprise {
            font-size: 18pt;
            font-weight: bold;
            color: #2c3e50;
        }
        .sous-titre {
            font-size: 12pt;
            color: #7f8c8d;
        }
        .coords {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #34495e;
            color: white;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .totaux {
            margin-top: 20px;
            text-align: right;
        }
        .total-ligne {
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #95a5a6;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="entreprise"><?php echo e($facture->pointVente->nom ?? config('app.name')); ?></div>
        <div class="sous-titre">Facture N° <?php echo e($facture->numero_facture); ?></div>
    </div>

    <div class="coords">
        <table style="border: none; width: 100%;">
            <tr style="background: none;">
                <td style="border: none; width: 50%;">
                    <strong>Client :</strong><br>
                    <?php echo e($facture->client->nom); ?><br>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facture->client->telephone): ?> Tél: <?php echo e($facture->client->telephone); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($facture->client->email): ?> Email: <?php echo e($facture->client->email); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    <strong>Date :</strong> <?php echo e($facture->date_facture->format('d/m/Y')); ?><br>
                    <strong>Échéance :</strong> <?php echo e($facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : 'Non définie'); ?><br>
                    <strong>Point de vente :</strong> <?php echo e($facture->pointVente->nom ?? '-'); ?><br>
                    <strong>Agent :</strong> <?php echo e($facture->agent->prenom ?? ''); ?> <?php echo e($facture->agent->nom ?? '-'); ?>

                </td>
            </tr>
        </table>
    </div>

    <h4>Détail de la facture</h4>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Quantité</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $facture->lignes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr>
                <td><?php echo e($ligne->article->nom ?? 'N/A'); ?></td>
                <td><?php echo e(number_format($ligne->quantite, 0, ',', ' ')); ?></td>
                <td><?php echo e(number_format($ligne->prix_unitaire, 0, ',', ' ')); ?></td>
                <td class="total-ligne"><?php echo e(number_format($ligne->montant, 0, ',', ' ')); ?></td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </tbody>
    </table>

    <div class="totaux">
        <p><strong>Montant total :</strong> <?php echo e(number_format($facture->montant_total, 0, ',', ' ')); ?> FCFA</p>
        <p><strong>Montant payé :</strong> <?php echo e(number_format($facture->montant_paye, 0, ',', ' ')); ?> FCFA</p>
        <p><strong>Solde à payer :</strong> <?php echo e(number_format($facture->solde_restant, 0, ',', ' ')); ?> FCFA</p>
        <p><strong>Statut :</strong> <?php echo e(ucfirst($facture->statut)); ?></p>
    </div>

    <div class="footer">
        Document généré le <?php echo e(now()->format('d/m/Y à H:i')); ?> - Signature : ____________________
    </div>
</body>
</html><?php /**PATH C:\Users\diexa\attagest\resources\views/pdf/facture-client.blade.php ENDPATH**/ ?>