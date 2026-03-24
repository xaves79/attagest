<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu {{ $recu->numero_recu }}</title>
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

    {{-- EN-TÊTE --}}
    <div class="header">
        <div>
            <div class="company-name">{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</div>
            <div class="company-info">
                {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}<br>
                @if($entreprise->telephone ?? null) Tél : {{ $entreprise->telephone }}<br> @endif
                @if($entreprise->email ?? null) Email : {{ $entreprise->email }}<br> @endif
                @if($entreprise->rccm ?? null) RCCM : {{ $entreprise->rccm }} @endif
            </div>
        </div>
        <div class="recu-title">
            <h1>REÇU FOURNISSEUR</h1>
            <div class="recu-numero">N° {{ $recu->numero_recu }}</div>
            <div class="recu-date">Date : {{ \Carbon\Carbon::parse($recu->date_recu)->format('d/m/Y') }}</div>
            @php
                $badgeClass = $recu->paye ? 'badge-solde' : ($recu->mode_paiement === 'credit' ? 'badge-credit' : 'badge-encours');
                $badgeLabel = $recu->paye ? 'Soldé' : ($recu->mode_paiement === 'credit' ? 'Crédit' : 'En cours');
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>
    </div>

    {{-- INFOS FOURNISSEUR + LOT --}}
    <div class="info-grid">

        <div class="info-box">
            <div class="info-box-title">Fournisseur</div>
            <div class="info-box-value">
                {{ $recu->fournisseur_nom }}
                @if($recu->fournisseur_prenom && $recu->type_personne !== 'MORALE') {{ $recu->fournisseur_prenom }} @endif
            </div>
            <div class="info-box-sub">
                @if($recu->fournisseur_tel) Tél : {{ $recu->fournisseur_tel }}<br> @endif
                @if($recu->fournisseur_email) Email : {{ $recu->fournisseur_email }}<br> @endif
                @if($recu->fournisseur_whatsapp) WhatsApp : {{ $recu->fournisseur_whatsapp }} @endif
            </div>
        </div>

        <div class="info-box">
            <div class="info-box-title">Lot paddy</div>
            <div class="info-box-value">{{ $recu->code_lot ?? '—' }}</div>
            <div class="info-box-sub">
                Variété : {{ $recu->variete_nom ?? '—' }}<br>
                @if($recu->localite_nom) Provenance : {{ $recu->localite_nom }}<br> @endif
                @if($recu->agent_nom) Agent : {{ $recu->agent_prenom }} {{ $recu->agent_nom }} @endif
            </div>
        </div>

        @if($recu->date_limite_paiement)
        <div class="info-box">
            <div class="info-box-title">Échéance</div>
            <div class="info-box-value" style="color: #dc2626;">
                {{ \Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y') }}
            </div>
            <div class="info-box-sub">
                @php $jours = now()->diffInDays(\Carbon\Carbon::parse($recu->date_limite_paiement), false); @endphp
                @if($jours < 0)
                    <span style="color:#dc2626;font-weight:bold;">En retard de {{ abs($jours) }} j.</span>
                @elseif($jours === 0)
                    <span style="color:#d97706;">Échéance aujourd'hui</span>
                @else
                    Dans {{ $jours }} jour(s)
                @endif
                @if($recu->jours_credit) <br>Crédit : {{ $recu->jours_credit }} jours @endif
            </div>
        </div>
        @endif

    </div>

    {{-- TABLEAU DÉTAIL ACHAT --}}
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
                    <strong>Paddy — {{ $recu->variete_nom ?? 'Variété inconnue' }}</strong><br>
                    <span class="small">
                        Lot : {{ $recu->code_lot ?? '—' }}
                        @if($recu->localite_nom) · Provenance : {{ $recu->localite_nom }} @endif
                        @if($recu->reference_entreprise) · Réf : {{ $recu->reference_entreprise }} @endif
                    </span>
                </td>
                <td class="center">
                    {{ number_format($recu->quantite_achat_kg, 0, ',', ' ') }} kg
                </td>
                <td class="right">
                    {{ number_format($recu->prix_achat_unitaire_fcfa, 0, ',', ' ') }} FCFA/kg
                </td>
                <td class="right">
                    <strong>{{ number_format($recu->montant_total, 0, ',', ' ') }} FCFA</strong>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <div class="totals">
        <div class="totals-box">
            <div class="totals-row total">
                <span>MONTANT TOTAL</span>
                <span>{{ number_format($recu->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row paye" style="margin-top:6px;">
                <span>Acompte versé</span>
                <span>{{ number_format($recu->acompte, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($recu->solde_du > 0)
            <div class="totals-row solde">
                <span>Solde restant dû</span>
                <span>{{ number_format($recu->solde_du, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="totals-row subtotal" style="margin-top:4px;">
                <span>Mode de paiement</span>
                <span>{{ ucfirst(str_replace('_', ' ', $recu->mode_paiement)) }}</span>
            </div>
        </div>
    </div>

    {{-- MENTION CRÉDIT --}}
    @if($recu->solde_du > 0)
    <div class="credit-box">
        ⚠ <strong>Solde à régler :</strong>
        {{ number_format($recu->solde_du, 0, ',', ' ') }} FCFA
        @if($recu->date_limite_paiement)
            avant le <strong>{{ \Carbon\Carbon::parse($recu->date_limite_paiement)->format('d/m/Y') }}</strong>.
        @endif
    </div>
    @endif

    {{-- HISTORIQUE PAIEMENTS --}}
    @if($paiements->isNotEmpty())
    <hr class="divider">
    <div class="section-title">Historique des paiements</div>
    @foreach($paiements as $p)
    <div class="paiement-row">
        <span>{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</span>
        <span>{{ ucfirst(str_replace('_', ' ', $p->mode_paiement)) }}</span>
        @if($p->notes) <span>{{ $p->notes }}</span> @endif
        <strong>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</strong>
    </div>
    @endforeach
    @endif

    {{-- SIGNATURES --}}
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-label">Le Fournisseur</div>
            <div>{{ $recu->fournisseur_nom }} @if($recu->fournisseur_prenom && $recu->type_personne !== 'MORALE') {{ $recu->fournisseur_prenom }} @endif</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Responsable Achats</div>
            <div>{{ $recu->agent_prenom ?? '' }} {{ $recu->agent_nom ?? 'ATTAGEST' }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Cachet & Signature</div>
            <div style="color:#92400e;font-weight:bold;">{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</div>
        </div>
    </div>

    {{-- PIED DE PAGE --}}
    <div class="footer">
        <strong>{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</strong>
        — {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}
        @if($entreprise->telephone ?? null) — Tél : {{ $entreprise->telephone }} @endif<br>
        Ce document tient lieu de reçu d'achat de paddy.
        @if($entreprise->rccm ?? null) — RCCM : {{ $entreprise->rccm }} @endif
    </div>

</body>
</html>