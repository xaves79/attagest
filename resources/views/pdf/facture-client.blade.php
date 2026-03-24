<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
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
        <div class="entreprise">{{ $facture->pointVente->nom ?? config('app.name') }}</div>
        <div class="sous-titre">Facture N° {{ $facture->numero_facture }}</div>
    </div>

    <div class="coords">
        <table style="border: none; width: 100%;">
            <tr style="background: none;">
                <td style="border: none; width: 50%;">
                    <strong>Client :</strong><br>
                    {{ $facture->client->nom }}<br>
                    @if($facture->client->telephone) Tél: {{ $facture->client->telephone }}<br> @endif
                    @if($facture->client->email) Email: {{ $facture->client->email }}<br> @endif
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    <strong>Date :</strong> {{ $facture->date_facture->format('d/m/Y') }}<br>
                    <strong>Échéance :</strong> {{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : 'Non définie' }}<br>
                    <strong>Point de vente :</strong> {{ $facture->pointVente->nom ?? '-' }}<br>
                    <strong>Agent :</strong> {{ $facture->agent->prenom ?? '' }} {{ $facture->agent->nom ?? '-' }}
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
            @foreach($facture->lignes as $ligne)
            <tr>
                <td>{{ $ligne->article->nom ?? 'N/A' }}</td>
                <td>{{ number_format($ligne->quantite, 0, ',', ' ') }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                <td class="total-ligne">{{ number_format($ligne->montant, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totaux">
        <p><strong>Montant total :</strong> {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</p>
        <p><strong>Montant payé :</strong> {{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</p>
        <p><strong>Solde à payer :</strong> {{ number_format($facture->solde_restant, 0, ',', ' ') }} FCFA</p>
        <p><strong>Statut :</strong> {{ ucfirst($facture->statut) }}</p>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Signature : ____________________
    </div>
</body>
</html>