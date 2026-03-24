<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .periode {
            text-align: center;
            font-style: italic;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total {
            margin-top: 25px;
            font-weight: bold;
            font-size: 14px;
            text-align: right;
        }
        .total span {
            display: inline-block;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <div class="periode">Période : {{ $periode }}</div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Date</th>
                <th>Libellé</th>
                <th>Compte débit</th>
                <th class="text-right">Montant débit (FCFA)</th>
                <th>Compte crédit</th>
                <th class="text-right">Montant crédit (FCFA)</th>
                <th>Pièce</th>
                <th>Validé</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ecritures as $e)
            <tr>
                <td>{{ $e->code_ecriture }}</td>
                <td>{{ $e->date_ecriture->format('d/m/Y') }}</td>
                <td>{{ $e->libelle }}</td>
                <td>{{ $e->compteDebit?->libelle ?? $e->compte_debit }}</td>
                <td class="text-right">{{ number_format($e->montant_debit, 0, ',', ' ') }}</td>
                <td>{{ $e->compteCredit?->libelle ?? $e->compte_credit }}</td>
                <td class="text-right">{{ number_format($e->montant_credit, 0, ',', ' ') }}</td>
                <td>{{ $e->pieceComptable?->libelle ?? $e->piece_comptable }}</td>
                <td>{{ $e->valide ? 'Oui' : 'Non' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Aucune écriture pour cette période.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        <div><span>Total débit :</span> {{ number_format($ecritures->sum('montant_debit'), 0, ',', ' ') }} FCFA</div>
        <div><span>Total crédit :</span> {{ number_format($ecritures->sum('montant_credit'), 0, ',', ' ') }} FCFA</div>
    </div>
</body>
</html>