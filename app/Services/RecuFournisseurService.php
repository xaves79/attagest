<?php

namespace App\Services;

use App\Models\RecuFournisseur;
use App\Models\PaiementFournisseur;

class RecuFournisseurService
{
    /**
     * Met à jour un reçu et ses lignes.
     */
    public function mettreAJour(RecuFournisseur $recu, array $data, array $lignes): void
    {
        $recu->update($data);

        // Supprimer les anciennes lignes
        $recu->lignes()->delete();

        // Recréer les lignes
        foreach ($lignes as $ligne) {
            if (!empty($ligne['variete_rice_id']) && ($ligne['quantite_kg'] ?? 0) > 0) {
                $recu->lignes()->create([
                    'achat_paddy_id'   => $ligne['achat_paddy_id'] ?? null,
                    'variete_rice_id'  => $ligne['variete_rice_id'],
                    'quantite_kg'      => $ligne['quantite_kg'],
                    'prix_unitaire'    => $ligne['prix_unitaire'],
                    'sous_total'       => $ligne['quantite_kg'] * $ligne['prix_unitaire'],
                ]);
            }
        }

        // Recalculer le montant total (sécurité)
        $recu->montant_total = $recu->lignes()->sum('sous_total');
        $recu->save();
    }

    /**
     * Enregistre un paiement et met à jour le statut du reçu.
     */
    public function enregistrerPaiement(
        RecuFournisseur $recu,
        float $montant,
        string $mode,
        ?string $reference = null,
        ?string $notes = null
    ): PaiementFournisseur {
        // Créer le paiement
        $paiement = $recu->paiements()->create([
            'montant'       => $montant,
            'mode_paiement' => $mode,
            'reference'     => $reference,
            'notes'         => $notes,
            'date_paiement' => now(),
        ]);

        // Mettre à jour le champ 'paye' du reçu si le total des paiements atteint le montant
        $totalPaye = $recu->paiements()->sum('montant');
        if ($totalPaye >= $recu->montant_total) {
            $recu->paye = true;
            $recu->save();
        }

        return $paiement;
    }
}