<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CommandeVente;
use App\Models\FactureClient;
use App\Models\PaiementFacture;
use App\Models\LigneFacture;
use Illuminate\Support\Facades\DB;

class CreateMissingInvoices extends Command
{
    protected $signature = 'factures:create-missing';
    protected $description = 'Crée une facture pour chaque commande sans facture associée';

    public function handle()
    {
        $commandes = CommandeVente::whereNull('facture_id')->get();

        if ($commandes->isEmpty()) {
            $this->info('Aucune commande sans facture trouvée.');
            return;
        }

        $bar = $this->output->createProgressBar($commandes->count());
        $bar->start();

        foreach ($commandes as $commande) {
            DB::transaction(function () use ($commande) {
                // Créer la facture
                $nextAutoNumero = (FactureClient::max('auto_numero') ?? 0) + 1;
                $facture = FactureClient::create([
                    'numero_facture' => 'FAC-' . now()->format('Y') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT),
                    'client_id'      => $commande->client_id,
                    'date_facture'   => $commande->date_commande,
                    'montant_total'  => $commande->montant_total_fcfa,
                    'montant_paye'   => $commande->montant_acompte_fcfa,
                    'solde_restant'  => $commande->montant_total_fcfa - $commande->montant_acompte_fcfa,
                    'statut'         => $commande->montant_acompte_fcfa >= $commande->montant_total_fcfa ? 'payee' : ($commande->montant_acompte_fcfa > 0 ? 'partiel' : 'credit'),
                    'date_echeance'  => $commande->date_echeance,
                    'auto_numero'    => $nextAutoNumero,
                ]);

                $commande->update(['facture_id' => $facture->id]);

                // Créer les lignes de facture à partir des lignes de commande
                foreach ($commande->lignes as $ligneCommande) {
                    LigneFacture::create([
                        'facture_id'    => $facture->id,
                        'type_produit'  => $ligneCommande->type_produit,
                        'poids_sac_kg'  => $ligneCommande->poids_sac_kg,
                        'unite'         => $ligneCommande->unite,
                        'description'   => $ligneCommande->type_produit . ' ' . ($ligneCommande->poids_sac_kg ?? '') . ' ' . $ligneCommande->unite,
                        'quantite'      => $ligneCommande->quantite,
                        'prix_unitaire' => $ligneCommande->prix_unitaire_fcfa,
                        'montant'       => $ligneCommande->quantite * $ligneCommande->prix_unitaire_fcfa,
                    ]);
                }

                // Si un acompte a déjà été payé, créer un paiement correspondant
                if ($commande->montant_acompte_fcfa > 0) {
                    $nextPaiementNum = (PaiementFacture::max('id') ?? 0) + 1;
                    PaiementFacture::create([
                        'facture_id'      => $facture->id,
                        'numero_paiement' => 'PAY-' . now()->format('Ymd') . '-' . str_pad($nextPaiementNum, 4, '0', STR_PAD_LEFT),
                        'montant_paye'    => $commande->montant_acompte_fcfa,
                        'date_paiement'   => $commande->date_commande,
                        'mode_paiement'   => 'espèces',
                        'description'     => 'Acompte à la commande',
                        'statut'          => 'paye',
                    ]);
                }
            });
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Factures créées avec succès pour ' . $commandes->count() . ' commandes.');
    }
}