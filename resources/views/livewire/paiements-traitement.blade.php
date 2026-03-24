<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement {{ $t->code_traitement }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #1e293b; background: white; padding: 20px 25px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #0d9488; }
        .company-name { font-size: 15pt; font-weight: bold; color: #0d9488; }
        .company-info { font-size: 8pt; color: #64748b; line-height: 1.6; margin-top: 4px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 16pt; font-weight: bold; color: #0d9488; letter-spacing: 1px; }
        .doc-title .code { font-size: 11pt; font-weight: bold; color: #1e293b; margin-top: 4px; }
        .doc-title .date { font-size: 8pt; color: #64748b; margin-top: 2px; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-top: 6px; }
        .badge-attente  { background: #fef9c3; color: #713f12; border: 1px solid #fde047; }
        .badge-cours    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .badge-termine  { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-annule   { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        .info-grid { display: flex; gap: 12px; margin-bottom: 20px; }
        .info-box { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; }
        .info-box-title { font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 6px; }
        .info-box-value { font-size: 11pt; font-weight: bold; color: #1e293b; margin-bottom: 2px; }
        .info-box-sub { font-size: 8.5pt; color: #64748b; line-height: 1.5; }

        h2 { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 6px 10px; margin: 18px 0 10px 0; border-radius: 4px; background: #f0fdfa; color: #0d9488; border-left: 4px solid #0d9488; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        thead tr { background: #0d9488; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 8.5pt; font-weight: bold; text-transform: uppercase; }
        thead th.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f0fdfa; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; }
        tbody td.right { text-align: right; font-weight: bold; }
        tbody td.green { color: #166534; font-weight: bold; }
        tbody td.amber { color: #92400e; }

        .totaux { display: flex; justify-content: flex-end; margin-bottom: 15px; }
        .totaux-box { width: 260px; }
        .totaux-row { display: flex; justify-content: space-between; padding: 5px 10px; font-size: 9pt; }
        .totaux-row.total { background: #0d9488; color: white; font-weight: bold; border-radius: 5px; margin-top: 4px; padding: 9px 10px; font-size: 11pt; }
        .totaux-row.paye { background: #dcfce7; color: #166534; font-weight: bold; border-radius: 4px; }
        .totaux-row.solde { color: #dc2626; font-weight: bold; }

        .solde-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 14px; margin-bottom: 15px; font-size: 9pt; color: #78350f; }

        .paiement-row { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px dashed #e2e8f0; font-size: 8.5pt; color: #475569; }

        .signatures { display: flex; justify-content: space-between; margin-top: 25px; gap: 15px; }
        .signature-box { flex: 1; border-top: 1px solid #cbd5e1; padding-top: 8px; text-align: center; font-size: 8.5pt; color: #64748b; }
        .signature-label { font-weight: bold; font-size: 9pt; color: #1e293b; margin-bottom: 30px; }

        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 7.5pt; color: #94a3b8; }
        .footer strong { color: #0d9488; }
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
                @if($entreprise->email ?? null) Email : {{ $entreprise->email }} @endif
            </div>
        </div>
        <div class="doc-title">
            <h1>FICHE TRAITEMENT</h1>
            <div class="code">N° {{ $t->code_traitement }}</div>
            <div class="date">Date : {{ \Carbon\Carbon::parse($t->date_reception)->format('d/m/Y') }}</div>
            @php
                $badgeClass = match($t->statut) {
                    'en_attente' => 'badge-attente',
                    'en_cours'   => 'badge-cours',
                    'termine'    => 'badge-termine',
                    'annule'     => 'badge-annule',
                    default      => 'badge-attente',
                };
                $badgeLabel = match($t->statut) {
                    'en_attente' => 'En attente',
                    'en_cours'   => 'En cours',
                    'termine'    => 'Terminé',
                    'annule'     => 'Annulé',
                    default      => $t->statut,
                };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
        </div>
    </div>

    {{-- INFOS CLIENT + TRAITEMENT --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="info-box-title">Client</div>
            <div class="info-box-value">{{ $t->raison_sociale ?: $t->client_nom }}</div>
            <div class="info-box-sub">
                @if($t->client_tel) Tél : {{ $t->client_tel }}<br> @endif
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-title">Détails</div>
            <div class="info-box-value">{{ $t->variete_nom ?? '—' }}</div>
            <div class="info-box-sub">
                @if($t->localite_nom) Provenance : {{ $t->localite_nom }}<br> @endif
                @if($t->agent_nom) Agent : {{ $t->agent_prenom }} {{ $t->agent_nom }} @endif
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-title">Prix de prestation</div>
            <div class="info-box-value" style="color:#0d9488;">{{ number_format((float)$t->prix_kg, 0, ',', ' ') }} FCFA/kg</div>
            <div class="info-box-sub">
                Rendement : {{ number_format((float)$t->taux, 1) }}%
            </div>
        </div>
    </div>

    {{-- TABLEAU QUANTITÉS --}}
    <h2>📦 Bilan de transformation</h2>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="right">Quantité (kg)</th>
                <th class="right">% du paddy</th>
            </tr>
        </thead>
        <tbody>
            @php $paddy = (float)$t->qte_paddy; @endphp
            <tr>
                <td><strong>Paddy reçu</strong></td>
                <td class="right amber">{{ number_format($paddy, 0, ',', ' ') }}</td>
                <td class="right">100%</td>
            </tr>
            <tr>
                <td>🍚 Riz blanc produit</td>
                <td class="right green">{{ number_format((float)$t->qte_blanc, 0, ',', ' ') }}</td>
                <td class="right">{{ $paddy > 0 ? round((float)$t->qte_blanc / $paddy * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>🟤 Son de riz</td>
                <td class="right">{{ number_format((float)$t->qte_son, 0, ',', ' ') }}</td>
                <td class="right">{{ $paddy > 0 ? round((float)$t->qte_son / $paddy * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    {{-- TOTAUX --}}
    <div class="totaux">
        <div class="totaux-box">
            <div class="totaux-row total">
                <span>MONTANT PRESTATION</span>
                <span>{{ number_format((float)$t->montant, 0, ',', ' ') }} FCFA</span>
            </div>
            @if($totalPaye > 0)
            <div class="totaux-row paye" style="margin-top:5px;">
                <span>Déjà payé</span>
                <span>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
            @if($solde > 0)
            <div class="totaux-row solde">
                <span>Solde restant dû</span>
                <span>{{ number_format($solde, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ALERTE SOLDE --}}
    @if($solde > 0)
    <div class="solde-box">
        ⚠ <strong>Solde à régler :</strong> {{ number_format($solde, 0, ',', ' ') }} FCFA
    </div>
    @endif

    {{-- HISTORIQUE PAIEMENTS --}}
    @if($paiements->isNotEmpty())
    <h2>💳 Historique des paiements</h2>
    @foreach($paiements as $p)
    <div class="paiement-row">
        <span>{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</span>
        <span>{{ ucfirst(str_replace('_', ' ', $p->mode_paiement)) }}</span>
        @if($p->description) <span>{{ $p->description }}</span> @endif
        <strong>{{ number_format($p->montant_paye, 0, ',', ' ') }} FCFA</strong>
    </div>
    @endforeach
    @endif

    {{-- SIGNATURES --}}
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-label">Le Client</div>
            <div>{{ $t->raison_sociale ?: $t->client_nom }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Agent ATTAGEST</div>
            <div>{{ $t->agent_prenom ?? '' }} {{ $t->agent_nom ?? '—' }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Cachet & Signature</div>
            <div style="color:#0d9488;font-weight:bold;">{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</div>
        </div>
    </div>

    {{-- PIED DE PAGE --}}
    <div class="footer">
        <strong>{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</strong>
        — {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}
        @if($entreprise->telephone ?? null) — Tél : {{ $entreprise->telephone }} @endif<br>
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>