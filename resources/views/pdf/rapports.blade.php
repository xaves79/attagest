<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport ATTAGEST</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #1e293b; padding: 20px 25px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid #4f46e5; }
        .company-name { font-size: 15pt; font-weight: bold; color: #4f46e5; }
        .company-info { font-size: 8pt; color: #64748b; line-height: 1.6; margin-top: 4px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 16pt; font-weight: bold; color: #4f46e5; }
        .doc-title .periode { font-size: 9pt; color: #64748b; margin-top: 4px; }
        .doc-title .genere { font-size: 8pt; color: #94a3b8; margin-top: 2px; }

        .kpis { display: flex; gap: 8px; margin-bottom: 20px; }
        .kpi { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; text-align: center; }
        .kpi .label { font-size: 7.5pt; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px; }
        .kpi .value { font-size: 12pt; font-weight: bold; color: #1e293b; }
        .kpi .sub { font-size: 8pt; color: #64748b; margin-top: 2px; }

        h2 { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 6px 10px; margin: 18px 0 10px 0; border-radius: 4px; }
        .h2-achats { background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; }
        .h2-ventes { background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
        .h2-traitements { background: #fff7ed; color: #9a3412; border-left: 4px solid #f97316; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 8.5pt; }
        thead tr { background: #334155; color: white; }
        thead th { padding: 7px 10px; text-align: left; font-size: 8pt; font-weight: bold; }
        thead th.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; }
        tbody td.right { text-align: right; font-weight: bold; }
        tfoot tr { background: #e2e8f0; font-weight: bold; }
        tfoot td { padding: 7px 10px; border-top: 2px solid #94a3b8; }
        tfoot td.right { text-align: right; }

        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 7.5pt; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="company-name">{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</div>
            <div class="company-info">
                {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }}<br>
                @if($entreprise->telephone ?? null) Tél : {{ $entreprise->telephone }} @endif
            </div>
        </div>
        <div class="doc-title">
            <h1>RAPPORT D'ACTIVITÉ</h1>
            <div class="periode">{{ $global['periode_label'] }}</div>
            <div class="genere">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="kpis">
        <div class="kpi">
            <div class="label">Achats paddy</div>
            <div class="value">{{ number_format((int)$global['paddy_achete_kg'], 0, ',', ' ') }} kg</div>
            <div class="sub">{{ number_format((int)$global['paddy_achete_fcfa'], 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="kpi">
            <div class="label">Ventes riz</div>
            <div class="value">{{ number_format((int)$global['riz_vendu_kg'], 0, ',', ' ') }} kg</div>
            <div class="sub">{{ number_format((int)$global['riz_vendu_fcfa'], 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="kpi">
            <div class="label">Paiements reçus</div>
            <div class="value">{{ number_format((int)$global['paiements_fcfa'], 0, ',', ' ') }} FCFA</div>
        </div>
    </div>

    {{-- ACHATS --}}
    <h2 class="h2-achats">🌾 Achats Paddy</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Fournisseur</th>
                <th class="right">Quantité (kg)</th>
                <th class="right">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achats as $a)
            <tr>
                <td>{{ \Carbon\Carbon::parse($a->date_achat)->format('d/m/Y') }}</td>
                <td>{{ $a->fournisseur ?? '—' }}</td>
                <td class="right">{{ number_format((int)$a->qte, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$a->montant, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:12px;">Aucun achat dans cette période</td></tr>
            @endforelse
        </tbody>
        @if($achats->count() > 0)
        <tfoot>
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td class="right">{{ number_format((int)$global['paddy_achete_kg'], 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$global['paddy_achete_fcfa'], 0, ',', ' ') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- VENTES --}}
    <h2 class="h2-ventes">🛒 Ventes Riz</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th class="right">Quantité (kg)</th>
                <th class="right">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventes as $v)
            <tr>
                <td>{{ \Carbon\Carbon::parse($v->date_vente)->format('d/m/Y') }}</td>
                <td>{{ $v->raison_sociale ?: $v->client }}</td>
                <td class="right">{{ number_format((int)$v->qte, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$v->montant, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:12px;">Aucune vente dans cette période</td></tr>
            @endforelse
        </tbody>
        @if($ventes->count() > 0)
        <tfoot>
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td class="right">{{ number_format((int)$global['riz_vendu_kg'], 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$global['riz_vendu_fcfa'], 0, ',', ' ') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- TRAITEMENTS --}}
    @if(isset($traitements) && $traitements->count() > 0)
    <h2 class="h2-traitements">🧪 Traitements Clients</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th class="right">Paddy (kg)</th>
                <th class="right">Riz blanc (kg)</th>
                <th class="right">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($traitements as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->date_reception)->format('d/m/Y') }}</td>
                <td>{{ $t->raison_sociale ?: $t->client }}</td>
                <td class="right">{{ number_format((int)$t->qte_paddy, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$t->qte_blanc, 0, ',', ' ') }}</td>
                <td class="right">{{ number_format((int)$t->montant, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <strong>{{ $entreprise->nom ?? 'ATTAGEST SARL' }}</strong> —
        {{ $entreprise->adresse ?? 'Bouaké, Côte d\'Ivoire' }} — Document confidentiel
    </div>

</body>
</html>