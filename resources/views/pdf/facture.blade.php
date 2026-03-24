<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1e293b;
            background: white;
            padding: 20px 25px;
        }

        /* ---------------------------------------------------------- */
        /* En-tête                                                      */
        /* ---------------------------------------------------------- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #166534;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #166534;
            margin-bottom: 4px;
        }

        .company-info {
            font-size: 8px;
            color: #64748b;
            line-height: 1.6;
        }

        .facture-title {
            text-align: right;
        }

        .facture-title h1 {
            font-size: 20px;
            font-weight: bold;
            color: #166534;
            letter-spacing: 2px;
        }

        .facture-numero {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 4px;
        }

        .facture-date {
            font-size: 8px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ---------------------------------------------------------- */
        /* Statut badge                                                  */
        /* ---------------------------------------------------------- */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
        }

        .badge-payee       { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-partielle   { background: #fef9c3; color: #713f12; border: 1px solid #fde047; }
        .badge-attente     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ---------------------------------------------------------- */
        /* Infos client + commande                                      */
        /* ---------------------------------------------------------- */
        .info-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .info-box {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .info-box-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .info-box-value {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .info-box-sub {
            font-size: 10px;
            color: #64748b;
            line-height: 1.5;
        }

        /* ---------------------------------------------------------- */
        /* Tableau produits                                             */
        /* ---------------------------------------------------------- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead tr {
            background: #166534;
            color: white;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:nth-child(odd)  { background: white; }

        tbody td {
            padding: 9px 12px;
            font-size: 11px;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }

        tbody td.right  { text-align: right; }
        tbody td.center { text-align: center; }
        tbody td.small  { font-size: 10px; color: #64748b; }

        /* ---------------------------------------------------------- */
        /* Totaux                                                       */
        /* ---------------------------------------------------------- */
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }

        .totals-box {
            width: 280px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            font-size: 11px;
        }

        .totals-row.subtotal { color: #64748b; }

        .totals-row.total {
            background: #166534;
            color: white;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            margin-top: 4px;
            padding: 10px 12px;
        }

        .totals-row.encaisse {
            color: #166534;
            font-weight: bold;
            background: #dcfce7;
            border-radius: 4px;
        }

        .totals-row.solde {
            color: #dc2626;
            font-weight: bold;
        }

        /* ---------------------------------------------------------- */
        /* Historique paiements                                         */
        /* ---------------------------------------------------------- */
        .paiements-title {
            font-size: 10px;
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
            font-size: 10px;
            color: #475569;
        }

        /* ---------------------------------------------------------- */
        /* Pied de page                                                  */
        /* ---------------------------------------------------------- */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer strong { color: #166534; }

        /* ---------------------------------------------------------- */
        /* Mention crédit                                               */
        /* ---------------------------------------------------------- */
        .credit-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 10px;
            color: #78350f;
        }

        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
    </style>
</head>
<body>

    {{-- ============================================================ --}}
    {{-- EN-TÊTE                                                       --}}
    {{-- ============================================================ --}}
    <div class="header">
        <div>
            <div class="company-name">{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</div>
            <div class="company-info">
                {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}<br>
                @if($entreprise?->telephone) Tél : {{ $entreprise->telephone }}<br> @endif
                @if($entreprise?->email) Email : {{ $entreprise->email }}<br> @endif
                @if($entreprise?->rccm) RCCM : {{ $entreprise->rccm }} @endif
            </div>
        </div>

        <div class="facture-title">
            <h1>FACTURE</h1>
            <div class="facture-numero">N° {{ $facture->numero_facture }}</div>
            <div class="facture-date">
                Date : {{ $facture->date_facture?->format('d/m/Y') }}
            </div>
            @php
                $badgeClass = match($facture->statut) {
                    'payee'             => 'badge-payee',
                    'partiellement_payee' => 'badge-partielle',
                    default             => 'badge-attente',
                };
                $badgeLabel = match($facture->statut) {
                    'payee'               => 'Payée',
                    'partiellement_payee' => 'Part. payée',
                    default               => 'En attente',
                };
            @endphp
            <div>
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- INFOS CLIENT + COMMANDE                                       --}}
    {{-- ============================================================ --}}
    <div class="info-grid">

        <div class="info-box">
            <div class="info-box-title">Client</div>
            <div class="info-box-value">
                {{ $facture->client->raison_sociale ?? ($facture->client->nom . ' ' . $facture->client->prenom) }}
            </div>
            <div class="info-box-sub">
                Code : {{ $facture->client->code_client }}<br>
                @if($facture->client->telephone) Tél : {{ $facture->client->telephone }}<br> @endif
                @if($facture->client->localite) {{ $facture->client->localite->nom }} @endif
            </div>
        </div>

        @if($facture->commande)
        <div class="info-box">
            <div class="info-box-title">Commande</div>
            <div class="info-box-value">{{ $facture->commande->code_commande }}</div>
            <div class="info-box-sub">
                Type : {{ ucfirst($facture->commande->type_vente) }}<br>
                Agent : {{ $facture->commande->agent?->prenom }} {{ $facture->commande->agent?->nom }}<br>
                Point de vente : {{ $facture->commande->pointVente?->nom }}
            </div>
        </div>
        @endif

        @if($facture->date_echeance)
        <div class="info-box">
            <div class="info-box-title">Échéance</div>
            <div class="info-box-value" style="color: #dc2626;">
                {{ $facture->date_echeance->format('d/m/Y') }}
            </div>
            <div class="info-box-sub">
                @php $joursRestants = now()->diffInDays($facture->date_echeance, false); @endphp
                @if($joursRestants < 0)
                    <span style="color: #dc2626; font-weight: bold;">
                        En retard de {{ abs($joursRestants) }} jour(s)
                    </span>
                @elseif($joursRestants === 0)
                    <span style="color: #d97706;">Échéance aujourd'hui</span>
                @else
                    Dans {{ $joursRestants }} jour(s)
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ============================================================ --}}
    {{-- TABLEAU PRODUITS                                               --}}
    {{-- ============================================================ --}}
    @if($facture->commande)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Désignation</th>
                <th class="center">Quantité</th>
                <th class="right">Prix unitaire</th>
                <th class="right">Remise</th>
                <th class="right">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->commande->lignes as $i => $ligne)
            <tr>
                <td class="small">{{ $i + 1 }}</td>
                <td>
                    <strong>
                        {{ match($ligne->type_produit) {
                            'riz_blanc' => 'Riz blanc',
                            'son'       => 'Son de riz',
                            'brisures'  => 'Brisures de riz',
                            default     => ucfirst($ligne->type_produit)
                        } }}
                        @if($ligne->poids_sac_kg) — Sac de {{ $ligne->poids_sac_kg }}kg @endif
                    </strong><br>
                    <span class="small">
                        Réf : {{ $ligne->sac?->code_sac ?? '—' }}
                        @if($ligne->sac?->stockProduitFini?->varieteRice)
                            · Variété : {{ $ligne->sac->stockProduitFini->varieteRice->nom }}
                        @endif
                    </span>
                </td>
                <td class="center">{{ $ligne->quantite }} {{ $ligne->unite }}</td>
                <td class="right">{{ number_format($ligne->prix_unitaire_fcfa, 0, ',', ' ') }} F</td>
                <td class="right small">
                    {{ $ligne->remise_ligne_fcfa > 0 ? '- ' . number_format($ligne->remise_ligne_fcfa, 0, ',', ' ') . ' F' : '—' }}
                </td>
                <td class="right"><strong>{{ number_format($ligne->sous_total_fcfa, 0, ',', ' ') }} F</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- ============================================================ --}}
    {{-- TOTAUX                                                        --}}
    {{-- ============================================================ --}}
    <div class="totals">
        <div class="totals-box">
            @if($facture->commande?->remise_fcfa > 0)
            <div class="totals-row subtotal">
                <span>Sous-total HT</span>
                <span>{{ number_format($facture->commande->lignes->sum('sous_total_fcfa') + $facture->commande->remise_fcfa, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row subtotal">
                <span>Remise globale</span>
                <span>- {{ number_format($facture->commande->remise_fcfa, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            <div class="totals-row total">
                <span>TOTAL NET</span>
                <span>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="totals-row encaisse" style="margin-top: 8px;">
                <span>Montant encaissé</span>
                <span>{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($facture->solde_restant > 0)
            <div class="totals-row solde">
                <span>Solde restant dû</span>
                <span>{{ number_format($facture->solde_restant, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MENTION CRÉDIT                                                --}}
    {{-- ============================================================ --}}
    @if($facture->solde_restant > 0)
    <div class="credit-box">
        ⚠ <strong>Solde à régler :</strong>
        {{ number_format($facture->solde_restant, 0, ',', ' ') }} FCFA
        @if($facture->date_echeance)
            avant le <strong>{{ $facture->date_echeance->format('d/m/Y') }}</strong>.
        @endif
        Tout retard de paiement pourra entraîner des pénalités.
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- HISTORIQUE PAIEMENTS                                          --}}
    {{-- ============================================================ --}}
    @if($facture->paiements->isNotEmpty())
    <hr class="divider">
    <div class="paiements-title">Historique des paiements</div>
    @foreach($facture->paiements->sortBy('date_paiement') as $p)
    <div class="paiement-row">
        <span>{{ $p->date_paiement?->format('d/m/Y') }}</span>
        <span>{{ ucfirst(str_replace('_', ' ', $p->mode_paiement)) }}</span>
        @if($p->description) <span>{{ $p->description }}</span> @endif
        <strong>{{ number_format($p->montant_paye, 0, ',', ' ') }} FCFA</strong>
    </div>
    @endforeach
    @endif

    {{-- ============================================================ --}}
    {{-- PIED DE PAGE                                                  --}}
    {{-- ============================================================ --}}
    <div class="footer">
        <strong>{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</strong>
        — {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}
        @if($entreprise?->telephone) — Tél : {{ $entreprise->telephone }} @endif<br>
        Merci de votre confiance. Ce document tient lieu de facture.
        @if($entreprise?->rccm) — RCCM : {{ $entreprise->rccm }} @endif
    </div>

</body>
</html>