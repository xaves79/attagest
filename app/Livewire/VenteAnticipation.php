<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Agent;
use App\Models\PointVente;
use App\Models\StockSac;
use App\Models\SacProduitFini;
use App\Models\CommandeVente;
use App\Models\LigneCommandeVente;
use App\Models\ReservationStock;

class VenteAnticipation extends Component
{
    // ----------------------------------------------------------------
    // Étapes : 1=Infos, 2=Produits+Réservation, 3=Confirmation paiement
    // ----------------------------------------------------------------
    public int $etape = 1;

    // Étape 1
    public ?int   $client_id             = null;
    public ?int   $agent_id              = null;
    public ?int   $point_vente_id        = null;
    public string $date_commande         = '';
    public string $date_livraison_prevue = '';
    public string $notes                 = '';

    // Étape 2 — lignes
    public array  $lignes            = [];
    public array  $stocksDisponibles = [];

    // Ligne en cours
    public ?int   $ligne_sac_id        = null;
    public int    $ligne_quantite       = 1;
    public int    $ligne_prix_unitaire  = 0;
    public int    $ligne_remise         = 0;

    // Étape 3 — paiement anticipé
    public int    $montant_acompte   = 0;
    public string $mode_paiement     = 'especes';
    public bool   $generer_facture   = true;

    // Feedback
    public string $successMessage = '';
    public string $errorMessage   = '';
    public ?string $codeCommande  = null;

    // ----------------------------------------------------------------
    // Mount
    // ----------------------------------------------------------------
    public function mount(): void
    {
        $this->date_commande = now()->format('Y-m-d');
        $this->date_livraison_prevue = now()->addDays(7)->format('Y-m-d');
    }

    // ----------------------------------------------------------------
    // Watchers
    // ----------------------------------------------------------------
    public function updatedPointVenteId(): void
    {
        $this->chargerStocksDisponibles();
        $this->lignes = [];
    }

    public function updatedLigneSacId(): void
    {
        if (!$this->ligne_sac_id) return;
        $sac = SacProduitFini::find($this->ligne_sac_id);
        if (!$sac) return;

        $this->ligne_prix_unitaire = LigneCommandeVente::getPrixSuggere(
            $sac->type_sac, $sac->poids_sac_kg,
            $sac->stockProduitFini?->variete_rice_id, 'sac'
        );
    }

    // ----------------------------------------------------------------
    // Navigation
    // ----------------------------------------------------------------
    public function allerEtape2(): void
    {
        $this->errorMessage = '';
        $this->validate([
            'client_id'             => 'required|integer|exists:clients,id',
            'agent_id'              => 'required|integer|exists:agents,id',
            'point_vente_id'        => 'required|integer|exists:points_vente,id',
            'date_commande'         => 'required|date',
            'date_livraison_prevue' => 'required|date|after_or_equal:date_commande',
        ], [
            'client_id.required'             => 'Veuillez sélectionner un client.',
            'agent_id.required'              => 'Veuillez sélectionner un agent.',
            'point_vente_id.required'        => 'Veuillez sélectionner un point de vente.',
            'date_livraison_prevue.required' => 'La date de livraison prévue est obligatoire.',
            'date_livraison_prevue.after_or_equal' => 'La date de livraison doit être après la commande.',
        ]);

        $this->chargerStocksDisponibles();
        $this->etape = 2;
    }

    public function allerEtape3(): void
    {
        $this->errorMessage = '';
        if (empty($this->lignes)) {
            $this->errorMessage = 'Ajoutez au moins une ligne de produit.';
            return;
        }
        // Pré-remplir acompte à 50% par défaut (anticipation)
        $this->montant_acompte = (int) round($this->getTotalCommande() * 0.5);
        $this->etape = 3;
    }

    public function retourEtape(int $n): void
    {
        $this->etape = $n;
        $this->errorMessage = '';
    }

    // ----------------------------------------------------------------
    // Gestion lignes
    // ----------------------------------------------------------------
    public function ajouterLigne(): void
    {
        $this->errorMessage = '';

        if (!$this->ligne_sac_id) {
            $this->errorMessage = 'Veuillez sélectionner un sac.';
            return;
        }
        if ($this->ligne_quantite <= 0) {
            $this->errorMessage = 'La quantité doit être supérieure à 0.';
            return;
        }

        $stock = collect($this->stocksDisponibles)->firstWhere('sac_id', $this->ligne_sac_id);
        $dejaDansLignes = collect($this->lignes)->where('sac_id', $this->ligne_sac_id)->sum('quantite');
        $stockDispo = ($stock['quantite'] ?? 0) - $dejaDansLignes;

        if ($stockDispo < $this->ligne_quantite) {
            $this->errorMessage = "Stock insuffisant pour réservation. Disponible : {$stockDispo} sac(s).";
            return;
        }

        $sac = SacProduitFini::find($this->ligne_sac_id);

        $this->lignes[] = [
            'sac_id'         => $this->ligne_sac_id,
            'code_sac'       => $sac?->code_sac ?? '—',
            'type_produit'   => $sac?->type_sac ?? '—',
            'poids_sac_kg'   => $sac?->poids_sac_kg,
            'quantite'       => $this->ligne_quantite,
            'prix_unitaire'  => $this->ligne_prix_unitaire,
            'remise'         => $this->ligne_remise,
            'sous_total'     => ($this->ligne_quantite * $this->ligne_prix_unitaire) - $this->ligne_remise,
            'stock_sac_id'   => $stock['id'] ?? null,
        ];

        $this->ligne_sac_id       = null;
        $this->ligne_quantite     = 1;
        $this->ligne_prix_unitaire = 0;
        $this->ligne_remise       = 0;
    }

    public function supprimerLigne(int $index): void
    {
        array_splice($this->lignes, $index, 1);
        $this->lignes = array_values($this->lignes);
    }

    // ----------------------------------------------------------------
    // Enregistrement
    // ----------------------------------------------------------------
    public function enregistrer(): void
    {
        $this->errorMessage  = '';
        $this->successMessage = '';

        if ($this->montant_acompte <= 0) {
            $this->errorMessage = 'Un acompte est obligatoire pour une vente par anticipation.';
            return;
        }

        if ($this->montant_acompte > $this->getTotalCommande()) {
            $this->errorMessage = 'L\'acompte ne peut pas dépasser le total de la commande.';
            return;
        }

        try {
            DB::transaction(function () {
                // 1. Créer la commande
                $commande = CommandeVente::create([
                    'code_commande'         => '',
                    'type_vente'            => 'anticipation',
                    'statut'                => 'en_attente_livraison',
                    'client_id'             => $this->client_id,
                    'agent_id'              => $this->agent_id,
                    'point_vente_id'        => $this->point_vente_id,
                    'date_commande'         => $this->date_commande,
                    'date_livraison_prevue' => $this->date_livraison_prevue,
                    'montant_total_fcfa'    => $this->getTotalCommande(),
                    'montant_acompte_fcfa'  => $this->montant_acompte,
                    'remise_fcfa'           => 0,
                    'notes'                 => $this->notes,
                ]);

                // 2. Lignes + réservations de stock
                foreach ($this->lignes as $l) {
                    $ligne = LigneCommandeVente::create([
                        'commande_id'        => $commande->id,
                        'sac_id'             => $l['sac_id'],
                        'type_produit'       => $l['type_produit'],
                        'poids_sac_kg'       => $l['poids_sac_kg'],
                        'quantite'           => $l['quantite'],
                        'unite'              => 'sac',
                        'prix_unitaire_fcfa' => $l['prix_unitaire'],
                        'remise_ligne_fcfa'  => $l['remise'],
                        'quantite_livree'    => 0,
                    ]);

                    // Réserver le stock
                    if ($l['stock_sac_id']) {
                        ReservationStock::create([
                            'commande_id'      => $commande->id,
                            'ligne_commande_id'=> $ligne->id,
                            'stock_sac_id'     => $l['stock_sac_id'],
                            'quantite_reservee'=> $l['quantite'],
                            'statut'           => 'active',
                            'date_expiration'  => $this->date_livraison_prevue,
                        ]);

                        // Décrémenter le stock disponible (réservation)
                        StockSac::find($l['stock_sac_id'])?->decrement('quantite', $l['quantite']);
                    }
                }

                // 3. Générer la facture
                if ($this->generer_facture) {
                    $facture = \App\Models\FactureClient::create([
                        'numero_facture' => 'FAC-ANT-' . now()->format('Y') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT),
                        'client_id'      => $this->client_id,
                        'date_facture'   => $this->date_commande,
                        'montant_total'  => $commande->montant_total_fcfa,
                        'montant_paye'   => $this->montant_acompte,
                        'solde_restant'  => $commande->montant_total_fcfa - $this->montant_acompte,
                        'statut'         => $this->montant_acompte >= $commande->montant_total_fcfa
                            ? 'payee' : 'partiellement_payee',
                        'date_echeance'  => $this->date_livraison_prevue,
                    ]);
                    $commande->update(['facture_id' => $facture->id]);
                }

                $this->codeCommande = $commande->fresh()->code_commande;
            });

            $this->successMessage = "Commande {$this->codeCommande} enregistrée. Stock réservé jusqu'au " .
                now()->parse($this->date_livraison_prevue)->format('d/m/Y') . ".";
            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------
    private function chargerStocksDisponibles(): void
    {
        if (!$this->point_vente_id) {
            $this->stocksDisponibles = [];
            return;
        }
        $this->stocksDisponibles = StockSac::with(['sac.stockProduitFini.varieteRice'])
            ->where('point_vente_id', $this->point_vente_id)
            ->where('quantite', '>', 0)
            ->get()
            ->map(fn($ss) => [
                'id'           => $ss->id,
                'sac_id'       => $ss->sac_id,
                'code_sac'     => $ss->sac->code_sac ?? '—',
                'type_sac'     => $ss->sac->type_sac ?? '—',
                'poids_sac_kg' => $ss->sac->poids_sac_kg ?? 0,
                'variete'      => $ss->sac->stockProduitFini?->varieteRice?->nom ?? '—',
                'quantite'     => $ss->quantite,
                'label'        => ($ss->sac->code_sac ?? '—') . ' — ' .
                                  ($ss->sac->type_sac ?? '') . ' ' .
                                  ($ss->sac->poids_sac_kg ?? '') . 'kg · Stock : ' . $ss->quantite,
            ])
            ->toArray();
    }

    public function getTotalCommande(): int
    {
        return (int) collect($this->lignes)->sum('sous_total');
    }

    public function getSolde(): int
    {
        return max(0, $this->getTotalCommande() - $this->montant_acompte);
    }

    private function resetForm(): void
    {
        $this->etape              = 1;
        $this->client_id          = null;
        $this->point_vente_id     = null;
        $this->date_commande      = now()->format('Y-m-d');
        $this->date_livraison_prevue = now()->addDays(7)->format('Y-m-d');
        $this->notes              = '';
        $this->lignes             = [];
        $this->montant_acompte    = 0;
        $this->generer_facture    = true;
        $this->stocksDisponibles  = [];
        $this->ligne_sac_id       = null;
        $this->ligne_quantite     = 1;
        $this->ligne_prix_unitaire = 0;
        $this->ligne_remise       = 0;
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------
    public function render()
    {
        return view('livewire.commandes.vente-anticipation', [
            'clients'     => Client::orderBy('nom')->get(),
            'agents'      => Agent::where('actif', true)->orderBy('nom')->get(),
            'pointsVente' => PointVente::where('actif', true)->orderBy('nom')->get(),
        ])->layout('layouts.app');
    }
}