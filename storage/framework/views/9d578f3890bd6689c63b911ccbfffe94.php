<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu <?php echo e($recu->numero_recu); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            background: white;
            padding: 20px 25px;
        }

        /* EN-TÊTE */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 3px solid #92400e;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 4px;
        }

        .company-info {
            font-size: 8px;
            color: #64748b;
            line-height: 1.6;
        }

        .recu-title { text-align: right; }

        .recu-title h1 {
            font-size: 18px;
            font-weight: bold;
            color: #92400e;
            letter-spacing: 2px;
        }

        .recu-numero {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 4px;
        }

        .recu-date {
            font-size: 8px;
            color: #64748b;
            margin-top: 2px;
        }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }
        .badge-solde   { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-encours { background: #fef9c3; color: #713f12; border: 1px solid #fde047; }
        .badge-credit  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* GRILLE INFOS */
        .info-grid {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 22px;
        }

        .info-box {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .info-box-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .info-box-value {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .info-box-sub {
            font-size: 9px;
            color: #64748b;
            line-height: 1.5;
        }

        /* TABLEAU */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        thead tr { background: #92400e; color: white; }

        thead th {
            padding: 9px 11px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.right  { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #fefce8; }
        tbody tr:nth-child(odd)  { background: white; }

        tbody td {
            padding: 9px 11px;
            font-size: 10px;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody td.right  { text-align: right; }
        tbody td.center { text-align: center; }
        tbody td.small  { font-size: 9px; color: #64748b; }

        /* TOTAUX */
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .totals-box { width: 270px; }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            font-size: 10px;
        }

        .totals-row.subtotal { color: #64748b; }

        .totals-row.total {
            background: #92400e;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            margin-top: 4px;
            padding: 9px 12px;
        }

        .totals-row.paye {
            color: #166534;
            font-weight: bold;
            background: #dcfce7;
            border-radius: 4px;
            margin-top: 4px;
        }

        .totals-row.solde {
            color: #dc2626;
            font-weight: bold;
        }

        /* CRÉDIT BOX */
        .credit-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 18px;
            font-size: 9px;
            color: #78350f;
        }

        /* HISTORIQUE */
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .paiement-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 9px;
            color: #475569;
        }

        /* SIGNATURES */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }

        .signature-box {
            flex: 1;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }

        .signature-label {
            font-weight: bold;
            font-size: 9px;
            color: #1e293b;
            margin-bottom: 35px;
        }

        /* PIED DE PAGE */
        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer strong { color: #92400e; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 16px 0; }
    </style>
</head>
<body>

    
    <div class="header">
        <div>
            <div class="company-name"><?php echo e($entreprise->nom ?? 'ATTAGEST SARL'); ?></div>
            <div class="company-info">
                <?php echo e($entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire'); ?><br>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->telephone ?? null): ?> Tél : <?php echo e($entreprise->telephone); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->email ?? null): ?> Email : <?php echo e($entreprise->email); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->rccm ?? null): ?> RCCM : <?php echo e($entreprise->rccm); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="recu-title">
            <h1>REÇU FOURNISSEUR</h1>
            <div class="recu-numero">N° <?php echo e($recu->numero_recu); ?></div>
            <div class="recu-date">Date : <?php echo e(\Carbon\Carbon::parse($recu->date_recu)->format('d/m/Y')); ?></div>
            <?php
                $badgeClass = $recu->paye ? 'badge-solde' : ($recu->mode_paiement === 'credit' ? 'badge-credit' : 'badge-encours');
                $badgeLabel = $recu->paye ? 'Soldé' : ($recu->mode_paiement === 'credit' ? 'Crédit' : 'En cours');
            ?>
            <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($badgeLabel); ?></span>
        </div>
    </div>

    
    <div class="info-grid">

        <div class="info-box">
            <div class="info-box-title">Fournisseur</div>
            <div class="info-box-value">
                <?php echo e($recu->fournisseur_nom); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->fournisseur_prenom && $recu->type_personne !== 'MORALE'): ?> <?php echo e($recu->fournisseur_prenom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="info-box-sub">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->fournisseur_tel): ?> Tél : <?php echo e($recu->fournisseur_tel); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->fournisseur_email): ?> Email : <?php echo e($recu->fournisseur_email); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->fournisseur_whatsapp): ?> WhatsApp : <?php echo e($recu->fournisseur_whatsapp); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="info-box">
            <div class="info-box-title">Lot paddy</div>
            <div class="info-box-value"><?php echo e($recu->code_lot ?? '—'); ?></div>
            <div class="info-box-sub">
                Variété : <?php echo e($recu->variete_nom ?? '—'); ?><br>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->localite_nom): ?> Provenance : <?php echo e($recu->localite_nom); ?><br> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->agent_nom): ?> Agent : <?php echo e($recu->agent_prenom); ?> <?php echo e($recu->agent_nom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->date_limite_paiement): ?>
        <div class="info-box">
            <div class="info-box-title">Échéance</div>
            <div class="info-box-value" style="color: #dc2626;">
                <?php echo e(\Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y')); ?>

            </div>
            <div class="info-box-sub">
                <?php $jours = now()->diffInDays(\Carbon\Carbon::parse($recu->date_limite_paiement), false); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jours < 0): ?>
                    <span style="color:#dc2626;font-weight:bold;">En retard de <?php echo e(abs($jours)); ?> j.</span>
                <?php elseif($jours === 0): ?>
                    <span style="color:#d97706;">Échéance aujourd'hui</span>
                <?php else: ?>
                    Dans <?php echo e($jours); ?> jour(s)
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->jours_credit): ?> <br>Crédit : <?php echo e($recu->jours_credit); ?> jours <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="center">Quantité</th>
                <th class="right">Prix unitaire</th>
                <th class="right">Montant total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Paddy — <?php echo e($recu->variete_nom ?? 'Variété inconnue'); ?></strong><br>
                    <span class="small">
                        Lot : <?php echo e($recu->code_lot ?? '—'); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->localite_nom): ?> · Provenance : <?php echo e($recu->localite_nom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->reference_entreprise): ?> · Réf : <?php echo e($recu->reference_entreprise); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </span>
                </td>
                <td class="center">
                    <?php echo e(number_format($recu->quantite_achat_kg, 0, ',', ' ')); ?> kg
                </td>
                <td class="right">
                    <?php echo e(number_format($recu->prix_achat_unitaire_fcfa, 0, ',', ' ')); ?> FCFA/kg
                </td>
                <td class="right">
                    <strong><?php echo e(number_format($recu->montant_total, 0, ',', ' ')); ?> FCFA</strong>
                </td>
            </tr>
        </tbody>
    </table>

    
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row total">
                <span>MONTANT TOTAL</span>
                <span><?php echo e(number_format($recu->montant_total, 0, ',', ' ')); ?> FCFA</span>
            </div>
            <div class="totals-row paye" style="margin-top:6px;">
                <span>Acompte versé</span>
                <span><?php echo e(number_format($recu->acompte, 0, ',', ' ')); ?> FCFA</span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->solde_du > 0): ?>
            <div class="totals-row solde">
                <span>Solde restant dû</span>
                <span><?php echo e(number_format($recu->solde_du, 0, ',', ' ')); ?> FCFA</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="totals-row subtotal" style="margin-top:4px;">
                <span>Mode de paiement</span>
                <span><?php echo e(ucfirst(str_replace('_', ' ', $recu->mode_paiement))); ?></span>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->solde_du > 0): ?>
    <div class="credit-box">
        ⚠ <strong>Solde à régler :</strong>
        <?php echo e(number_format($recu->solde_du, 0, ',', ' ')); ?> FCFA
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->date_limite_paiement): ?>
            avant le <strong><?php echo e(\Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y')); ?></strong>.
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paiements->isNotEmpty()): ?>
    <hr class="divider">
    <div class="section-title">Historique des paiements</div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <div class="paiement-row">
        <span><?php echo e(\Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y')); ?></span>
        <span><?php echo e(ucfirst(str_replace('_', ' ', $p->mode_paiement))); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->notes): ?> <span><?php echo e($p->notes); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <strong><?php echo e(number_format($p->montant, 0, ',', ' ')); ?> FCFA</strong>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-label">Le Fournisseur</div>
            <div><?php echo e($recu->fournisseur_nom); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recu->fournisseur_prenom && $recu->type_personne !== 'MORALE'): ?> <?php echo e($recu->fournisseur_prenom); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Responsable Achats</div>
            <div><?php echo e($recu->agent_prenom ?? ''); ?> <?php echo e($recu->agent_nom ?? 'ATTAGEST'); ?></div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Cachet & Signature</div>
            <div style="color:#92400e;font-weight:bold;"><?php echo e($entreprise->nom ?? 'ATTAGEST SARL'); ?></div>
        </div>
    </div>

    
    <div class="footer">
        <strong><?php echo e($entreprise->nom ?? 'ATTAGEST SARL'); ?></strong>
        — <?php echo e($entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire'); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->telephone ?? null): ?> — Tél : <?php echo e($entreprise->telephone); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><br>
        Ce document tient lieu de reçu d'achat de paddy.
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($entreprise->rccm ?? null): ?> — RCCM : <?php echo e($entreprise->rccm); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</body>
</html><?php /**PATH C:\Users\diexa\attagest\resources\views/pdf/recu-fournisseur.blade.php ENDPATH**/ ?>