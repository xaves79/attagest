<!DOCTYPE html>
<html>
<head>
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Facture</h1>
        <p>{{ $facture->pointVente?->nom ?? 'Entreprise' }}</p>
        <p>{{ $facture->pointVente?->adresse ?? '' }}</p>
        <p>{{ $facture->pointVente?->telephone ?? '' }}</p>
    </div>

    <div class="info">
        <p><strong>Client :</strong> {{ $facture->client?->prenom ? $facture->client->prenom . ' ' : '' }}{{ $facture->client?->nom ?? '' }}</p>
        <p><strong>Vendeur :</strong> {{ $facture->agent?->prenom ? $facture->agent->prenom . ' ' : '' }}{{ $facture->agent?->nom ?? '' }}</p>
        <p><strong>Date facture :</strong> {{ $facture->date_facture?->format('d/m/Y') ?? '' }}</p>
        <p><strong>Numéro facture :</strong> {{ $facture->numero_facture }}</p>
        <p><strong>Statut :</strong> {{ $facture->statut }}</p>
        <p><strong>Montant total :</strong> {{ number_format($facture->montant_total, 2) }} FCFA</p>
        <p><strong>Montant payé :</strong> {{ number_format($facture->montant_paye, 2) }} FCFA</p>
        <p><strong>Solde restant :</strong> {{ number_format($facture->solde_restant, 2) }} FCFA</p>
        <p><strong>Date échéance :</strong> {{ $facture->date_echeance?->format('d/m/Y') ?? '' }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Article</th>
                <th>Quantité</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facture->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->article?->nom ?? '' }}</td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>{{ number_format($ligne->prix_unitaire, 2) }}</td>
                    <td>{{ number_format($ligne->montant, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info">
        <p><strong>Total facture :</strong> {{ number_format($facture->montant_total, 2) }} FCFA</p>
    </div>
</body>
</html>
