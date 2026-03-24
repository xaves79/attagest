<?php

namespace App\Services;

use App\Models\FactureClient;
use App\Models\LigneFacture;
use App\Models\PaiementFacture;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FactureClientService
{
    /**
     * Crée une nouvelle facture avec ses lignes
     */
    public function creerFacture(array $data, array $lignes): FactureClient
    {
        return DB::transaction(function () use ($data, $lignes) {
            // Générer un numéro de facture unique si non fourni
            if (empty($data['numero_facture'])) {
                $data['numero_facture'] = $this->genererNumeroFacture();
            }

            // Initialiser les montants à 0 (seront recalculés par le trigger après insertion des lignes)
            $data['montant_total'] = 0;
            $data['montant_paye'] = 0;
            $data['solde_restant'] = 0;
            $data['statut'] = 'credit'; // par défaut

            $facture = FactureClient::create($data);

            foreach ($lignes as $ligne) {
                if (!empty($ligne['article_id']) && ($ligne['quantite'] ?? 0) > 0) {
                    $facture->lignes()->create([
                        'article_id'    => $ligne['article_id'],
                        'quantite'      => $ligne['quantite'],
                        'prix_unitaire' => $ligne['prix_unitaire'],
                        'montant'       => $ligne['quantite'] * $ligne['prix_unitaire'],
                    ]);
                }
            }

            // Le trigger après insertion recalcule le montant_total
            // On rafraîchit le modèle pour avoir les valeurs à jour
            $facture->refresh();

            return $facture;
        });
    }

    /**
     * Met à jour une facture et ses lignes (uniquement si aucun paiement n'existe)
     */
    public function mettreAJour(FactureClient $facture, array $data, array $lignes): FactureClient
    {
        return DB::transaction(function () use ($facture, $data, $lignes) {
            // Vérifier que la facture n'a pas de paiements
            if ($facture->paiements()->exists()) {
                throw ValidationException::withMessages([
                    'facture' => 'Impossible de modifier une facture qui a déjà des paiements.'
                ]);
            }

            $facture->update($data);

            // Remplacer les lignes
            $facture->lignes()->delete();
            foreach ($lignes as $ligne) {
                if (!empty($ligne['article_id']) && ($ligne['quantite'] ?? 0) > 0) {
                    $facture->lignes()->create([
                        'article_id'    => $ligne['article_id'],
                        'quantite'      => $ligne['quantite'],
                        'prix_unitaire' => $ligne['prix_unitaire'],
                        'montant'       => $ligne['quantite'] * $ligne['prix_unitaire'],
                    ]);
                }
            }

            $facture->refresh();
            return $facture;
        });
    }

    /**
     * Enregistre un paiement sur une facture et met à jour les montants
     */
    public function enregistrerPaiement(FactureClient $facture, float $montant, string $mode, ?string $reference = null, ?string $description = null): PaiementFacture
    {
        return DB::transaction(function () use ($facture, $montant, $mode, $reference, $description) {
            // Vérifier que le montant ne dépasse pas le solde restant
            if ($montant > $facture->solde_restant) {
                throw ValidationException::withMessages([
                    'montant' => 'Le paiement ne peut pas dépasser le solde dû.'
                ]);
            }

            // Créer le paiement
            $paiement = $facture->paiements()->create([
                'numero_paiement' => $this->genererNumeroPaiement(),
                'montant_paye'    => $montant,
                'date_paiement'   => now(),
                'mode_paiement'   => $mode,
                'description'     => $description,
                'statut'          => 'paye',
            ]);

            // Mettre à jour les montants de la facture
            $facture->montant_paye += $montant;
            $facture->solde_restant -= $montant;

            // Mettre à jour le statut
            if ($facture->solde_restant <= 0) {
                $facture->statut = 'payee';
            } elseif ($facture->montant_paye > 0) {
                $facture->statut = 'partiel';
            } else {
                $facture->statut = 'credit';
            }

            $facture->save();

            return $paiement;
        });
    }

    /**
     * Annule une facture (si aucun paiement)
     */
    public function annulerFacture(FactureClient $facture): FactureClient
    {
        return DB::transaction(function () use ($facture) {
            if ($facture->paiements()->exists()) {
                throw ValidationException::withMessages([
                    'facture' => 'Impossible d\'annuler une facture qui a des paiements.'
                ]);
            }

            $facture->statut = 'annulee';
            $facture->save();

            return $facture;
        });
    }

    /**
     * Génère un numéro de facture unique (format FACT-YYYY-XXXX)
     */
    private function genererNumeroFacture(): string
    {
        $annee = now()->format('Y');
        $prefixe = "FACT-{$annee}-";
        $dernier = FactureClient::where('numero_facture', 'like', $prefixe . '%')
            ->orderBy('numero_facture', 'desc')
            ->first();

        if ($dernier) {
            $num = (int) substr($dernier->numero_facture, -4) + 1;
        } else {
            $num = 1;
        }

        return $prefixe . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Génère un numéro de paiement unique (format PAY-YYYYMMDD-XXXX)
     */
    private function genererNumeroPaiement(): string
    {
        $date = now()->format('Ymd');
        $prefixe = "PAY-{$date}-";
        $dernier = PaiementFacture::where('numero_paiement', 'like', $prefixe . '%')
            ->orderBy('numero_paiement', 'desc')
            ->first();

        if ($dernier) {
            $num = (int) substr($dernier->numero_paiement, -4) + 1;
        } else {
            $num = 1;
        }

        return $prefixe . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}