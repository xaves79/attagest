<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Agent;
use App\Models\PointVente;
use App\Models\StockSac;
use App\Models\SacProduitFini;
use App\Models\ParametrePrix;
use App\Models\CommandeVente;
use App\Models\LigneCommandeVente;
use App\Models\LivraisonVente;
use App\Models\FactureClient;
use App\Models\PaiementFacture;
use App\Services\JournalActivite;
use App\Models\LigneFacture;

class NouvelleCommande extends Component
{
    // ----------------------------------------------------------------
    // Navigation par étapes
    // ----------------------------------------------------------------
    public int $etape = 1; // 1=Infos, 2=Lignes, 3=Confirmation

    // ----------------------------------------------------------------
    // Étape 1 — Infos générales
    // ----------------------------------------------------------------
    public ?int    $client_id           = null;
    public ?int    $agent_id            = null;
    public ?int    $point_vente_id      = null;
    public string  $type_vente          = 'comptant';
    public string  $date_commande       = '';
    public string  $date_livraison_prevue = '';
    public string  $date_echeance       = '';
    public string  $notes               = '';

    // ----------------------------------------------------------------
    // Étape 2 — Lignes de commande
    // ----------------------------------------------------------------
    public array $lignes = [];

    // Ligne en cours de saisie
    public mixed   $ligne_sac_id           = null;
    public string  $ligne_type_produit     = 'riz_blanc';
    public ?float  $ligne_poids_sac_kg     = null;
    public string  $ligne_quantite         = '1';
    public string  $ligne_unite            = 'sac';
    public string  $ligne_prix_unitaire    = '0';
    public string  $ligne_remise           = '0';

    // Stocks disponibles
    public array $stocksDisponibles = [];

    // ----------------------------------------------------------------
    // Étape 3 — Paiement & remise
    // ----------------------------------------------------------------
    public string $montant_acompte     = '0';
    public bool   $generer_facture     = false;
    public string $remise_globale      = '0';

    // ----------------------------------------------------------------
    // Feedback UI
    // ----------------------------------------------------------------
    public string  $successMessage = '';
    public string  $errorMessage   = '';
    public ?string $codeCommande   = null;

    // ----------------------------------------------------------------
    // Validation par étape
    // ----------------------------------------------------------------
    protected function rules(): array
    {
        return match($this->etape) {
            1 => $this->rulesEtape1(),
            2 => $this->rulesEtape2(),
            3 => $this->rulesEtape3(),
            default => []
        };
    }

    protected function rulesEtape1(): array
    {
        $rules = [
            'client_id'      => 'required|integer|exists:clients,id',
            'agent_id'       => 'required|integer|exists:agents,id',
            'point_vente_id' => 'required|integer|exists:points_vente,id',
            'type_vente'     => 'required|in:comptant,credit',
            'date_commande'  => 'required|date',
        ];

        if ($this->type_vente === 'credit') {
            $rules['date_echeance'] = 'required|date|after:date_commande';
        }

        return $rules;
    }

    protected function rulesEtape2(): array
    {
        return [
            'lignes'                    => 'required|array|min:1',
            'lignes.*.type_produit'     => 'required|in:riz_blanc,son,brisures',
            'lignes.*.quantite'         => 'required|numeric|min:1',
            'lignes.*.prix_unitaire'    => 'required|numeric|min:0',
        ];
    }

    protected function rulesEtape3(): array
    {
        return [
            'montant_acompte' => 'required|numeric|min:0|max:' . $this->getTotalAvecRemise(),
            'remise_globale'  => 'required|numeric|min:0|max:' . $this->getTotalCommande(),
        ];
    }

    // ----------------------------------------------------------------
    // Lifecycle
    // ----------------------------------------------------------------
    public function mount(): void
    {
        $this->date_commande = now()->format('Y-m-d');
    }

    // ----------------------------------------------------------------
    // Watchers (réactivité)
    // ----------------------------------------------------------------
    public function updatedPointVenteId(): void
    {
        $this->chargerStocksDisponibles();
        $this->lignes = [];
    }

    public function updatedLigneSacId(): void
    {
        $this->chargerDetailsSac();
    }

    public function updatedTypeVente(): void
    {
        if ($this->type_vente === 'comptant') {
            $this->montant_acompte = (string) $this->getTotalAvecRemise();
            $this->date_echeance = '';
        }
    }

    public function updatedRemiseGlobale(): void
    {
        if ($this->type_vente === 'comptant') {
            $this->montant_acompte = (string) $this->getTotalAvecRemise();
        }
    }

    // ----------------------------------------------------------------
    // Navigation
    // ----------------------------------------------------------------
    public function allerEtape2(): void
    {
        $this->resetErrorBag();
        $this->validate($this->rulesEtape1());

        $this->chargerStocksDisponibles();
        $this->etape = 2;
    }

    public function allerEtape3(): void
    {
        $this->resetErrorBag();

        if (empty($this->lignes)) {
            $this->errorMessage = 'Ajoutez au moins une ligne de produit.';
            return;
        }

        $this->validate($this->rulesEtape2());
        $this->etape = 3;
    }

    public function retourEtape(int $etape): void
    {
        $this->etape = $etape;
        $this->resetErrorBag();
    }

    // ----------------------------------------------------------------
    // Gestion lignes
    // ----------------------------------------------------------------
    public function ajouterLigne(): void
    {
        $this->resetErrorBag();

        if ((int)$this->ligne_quantite <= 0) {
            $this->addError('ligne_quantite', 'Quantité > 0');
            return;
        }

        if ($this->ligne_sac_id && $this->ligne_unite === 'sac') {
            $stock = collect($this->stocksDisponibles)
                ->firstWhere('sac_id', $this->ligne_sac_id);

            $reserve = collect($this->lignes)
                ->where('sac_id', $this->ligne_sac_id)
                ->sum('quantite');

            if (($stock['quantite'] ?? 0) < ($reserve + (int)$this->ligne_quantite)) {
                $this->addError('ligne_sac_id', 'Stock insuffisant');
                return;
            }
        }

        $sac = $this->ligne_sac_id ? SacProduitFini::find($this->ligne_sac_id) : null;

        $this->lignes[] = [
            'sac_id'          => $this->ligne_sac_id,
            'code_sac'        => $sac?->code_sac ?? '—',
            'type_produit'    => $this->ligne_type_produit,
            'poids_sac_kg'    => $this->ligne_poids_sac_kg,
            'quantite'        => (int)$this->ligne_quantite,
            'unite'           => $this->ligne_unite,
            'prix_unitaire'   => (int)$this->ligne_prix_unitaire,
            'remise'          => (int)$this->ligne_remise,
            'sous_total'      => ((int)$this->ligne_quantite * (int)$this->ligne_prix_unitaire) - (int)$this->ligne_remise,
            'stock_sac_id'    => $stock['id'] ?? null,
        ];

        $this->resetLigne();
        $this->chargerStocksDisponibles();
    }

    public function supprimerLigne(int $index): void
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
        $this->chargerStocksDisponibles();
    }

    private function resetLigne(): void
    {
        $this->ligne_sac_id = null;
        $this->ligne_type_produit = 'riz_blanc';
        $this->ligne_poids_sac_kg = null;
        $this->ligne_quantite = '1';
        $this->ligne_unite = 'sac';
        $this->ligne_prix_unitaire = '0';
        $this->ligne_remise = '0';
    }

    // ----------------------------------------------------------------
    // ENREGISTREMENT FINAL (avec création systématique de facture)
    // ----------------------------------------------------------------
    public function enregistrer(): void
    {
        $this->resetErrorBag();

        if (empty($this->lignes)) {
            $this->errorMessage = 'Ajoutez des lignes avant d\'enregistrer.';
            return;
        }

        try {
            DB::transaction(function () {
                $totalNet = $this->getTotalAvecRemise();

                // 1️⃣ Créer commande (trigger génère code_commande)
                $commande = CommandeVente::create([
                    'code_commande'         => '',
                    'type_vente'            => $this->type_vente,
                    'statut'                => 'confirmee',
                    'client_id'             => $this->client_id,
                    'agent_id'              => $this->agent_id,
                    'point_vente_id'        => $this->point_vente_id,
                    'date_commande'         => $this->date_commande,
                    'date_livraison_prevue' => $this->date_livraison_prevue ?: null,
                    'date_echeance'         => $this->type_vente === 'credit' ? $this->date_echeance : null,
                    'montant_total_fcfa'    => $totalNet,
                    'montant_acompte_fcfa'  => (int)$this->montant_acompte,
                    'remise_fcfa'           => (int)$this->remise_globale,
                    'notes'                 => $this->notes,
                ]);

                // 2️⃣ Lignes commande
                foreach ($this->lignes as $ligneCommande) {
                    LigneCommandeVente::create([
                        'commande_id'        => $commande->id,
                        'sac_id'             => $ligneCommande['sac_id'],
                        'type_produit'       => $ligneCommande['type_produit'],
                        'poids_sac_kg'       => $ligneCommande['poids_sac_kg'],
                        'quantite'           => $ligneCommande['quantite'],
                        'unite'              => $ligneCommande['unite'],
                        'prix_unitaire_fcfa' => $ligneCommande['prix_unitaire'],
                        'remise_ligne_fcfa'  => $ligneCommande['remise'],
                        'quantite_livree'    => 0,
                    ]);
                }

                // 3️⃣ Facture TOUJOURS créée (même pour comptant)
                $acompte = (int)$this->montant_acompte;
                $facture = $this->creerFacture($commande, $acompte);
                $commande->update(['facture_id' => $facture->id]);

                // 4️⃣ Lignes de facture
                foreach ($this->lignes as $ligneCommande) {
                    LigneFacture::create([
                        'facture_id'    => $facture->id,
                        'type_produit'  => $ligneCommande['type_produit'],
                        'poids_sac_kg'  => $ligneCommande['poids_sac_kg'],
                        'unite'         => $ligneCommande['unite'],
                        'description'   => $ligneCommande['type_produit'] . ' ' . ($ligneCommande['poids_sac_kg'] ?? '') . ' ' . $ligneCommande['unite'],
                        'quantite'      => $ligneCommande['quantite'],
                        'prix_unitaire' => $ligneCommande['prix_unitaire'],
                        'montant'       => $ligneCommande['sous_total'],
                    ]);
                }

                // 5️⃣ Livraison immédiate (comptant)
                if ($this->type_vente === 'comptant') {
                    $this->creerLivraisonImmediat($commande);
                }

                // ✅ Récupérer code généré par trigger
                $this->codeCommande = $commande->fresh()->code_commande;
            });

            $this->successMessage = "Commande " . $this->codeCommande . " enregistrée avec succès !";
            JournalActivite::creation('ventes', "Commande {$this->codeCommande} — Client {$this->client_id} — {$this->getTotalAvecRemise()} FCFA");

            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur enregistrement : ' . $e->getMessage();
        }
    }

    // ----------------------------------------------------------------
    // Helpers privés
    // ----------------------------------------------------------------
    private function chargerDetailsSac(): void
    {
        if (!$this->ligne_sac_id) return;

        $sac = DB::table('sacs_produits_finis as s')
            ->leftJoin('stocks_produits_finis as spf', 'spf.id', '=', 's.stock_produit_fini_id')
            ->where('s.id', $this->ligne_sac_id)
            ->select('s.type_sac', 's.poids_sac_kg', 'spf.variete_rice_id')
            ->first();

        if (!$sac) return;

        $this->ligne_type_produit = $sac->type_sac;
        $this->ligne_poids_sac_kg = $sac->poids_sac_kg;
        $this->ligne_unite = 'sac';

        $prix = DB::table('parametres_prix')
            ->where('type_produit', $sac->type_sac)
            ->where('unite', 'sac')
            ->where('actif', true)
            ->where(function ($q) use ($sac) {
                $q->where('poids_sac_kg', $sac->poids_sac_kg)
                  ->orWhereNull('poids_sac_kg');
            })
            ->orderByDesc('date_application')
            ->value('prix_unitaire_fcfa');

        $this->ligne_prix_unitaire = (string)(int)($prix ?? 0);
    }

    private function chargerStocksDisponibles(): void
    {
        if (!$this->point_vente_id) {
            $this->stocksDisponibles = [];
            return;
        }

        $reserve = collect($this->lignes)->groupBy('sac_id')->map->sum('quantite');

        $this->stocksDisponibles = StockSac::with(['sac.stockProduitFini.varieteRice'])
            ->where('point_vente_id', $this->point_vente_id)
            ->where('quantite', '>', 0)
            ->get()
            ->map(function ($ss) use ($reserve) {
                $dispo = $ss->quantite - ($reserve[$ss->sac_id] ?? 0);
                if ($dispo <= 0) return null;

                return [
                    'id'           => $ss->id,
                    'sac_id'       => $ss->sac_id,
                    'code_sac'     => $ss->sac->code_sac ?? '—',
                    'type_sac'     => $ss->sac->type_sac ?? '—',
                    'poids_sac_kg' => $ss->sac->poids_sac_kg ?? 0,
                    'variete'      => $ss->sac->stockProduitFini?->varieteRice?->nom ?? '—',
                    'quantite'     => $dispo,
                    'label'        => "{$ss->sac->code_sac} — {$ss->sac->type_sac} {$ss->sac->poids_sac_kg}kg ({$dispo} sacs)",
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function creerFacture(CommandeVente $commande, int $acompte): FactureClient
    {
        $nextNumero = (DB::table('factures_clients')->max('auto_numero') ?? 0) + 1;

        $facture = FactureClient::create([
            'numero_facture'  => 'FAC-' . now()->format('Y') . '-' . str_pad($commande->id, 4, '0', STR_PAD_LEFT),
            'client_id'       => $this->client_id,
            'date_facture'    => $this->date_commande,
            'montant_total'   => $commande->montant_total_fcfa,
            'montant_paye'    => $acompte,
            'solde_restant'   => $commande->montant_total_fcfa - $acompte,
            'statut'          => $acompte >= $commande->montant_total_fcfa ? 'payee' : 'partiel',
            'date_echeance'   => $this->date_echeance ?: null,
            'auto_numero'     => $nextNumero,
        ]);

        if ($acompte > 0) {
            $nextPaiement = (DB::table('paiements_factures')->max('id') ?? 0) + 1;
            PaiementFacture::create([
                'facture_id'      => $facture->id,
                'numero_paiement' => 'PAY-' . now()->format('Y') . '-' . str_pad($nextPaiement, 4, '0', STR_PAD_LEFT),
                'montant_paye'    => $acompte,
                'date_paiement'   => $this->date_commande,
                'mode_paiement'   => 'espèces',
                'description'     => 'Acompte commande ' . $this->codeCommande,
                'statut'          => 'paye',
            ]);
        }

        return $facture;
    }

    private function creerLivraisonImmediat(CommandeVente $commande): void
    {
        $lignesLivraison = collect($this->lignes)
            ->filter(fn($l) => $l['stock_sac_id'])
            ->map(fn($l, $i) => [
                'ligne_commande_id' => LigneCommandeVente::where('commande_id', $commande->id)
                    ->orderBy('id')->skip($i)->value('id'),
                'stock_sac_id'      => $l['stock_sac_id'],
                'quantite_livree'   => $l['quantite'],
            ])
            ->values()
            ->toArray();

        if (!empty($lignesLivraison)) {
            LivraisonVente::creer($commande, $lignesLivraison, $this->agent_id);
        }
    }

    // ----------------------------------------------------------------
    // Getters publics
    // ----------------------------------------------------------------
    public function getTotalCommande(): int
    {
        return collect($this->lignes)->sum('sous_total');
    }

    public function getTotalAvecRemise(): int
    {
        return max(0, $this->getTotalCommande() - (int)$this->remise_globale);
    }

    public function getSolde(): int
    {
        return max(0, $this->getTotalAvecRemise() - (int)$this->montant_acompte);
    }

    private function resetForm(): void
    {
        $this->etape = 1;
        $this->client_id = null;
        $this->agent_id = null;
        $this->point_vente_id = null;
        $this->type_vente = 'comptant';
        $this->date_commande = now()->format('Y-m-d');
        $this->lignes = [];
        $this->montant_acompte = '0';
        $this->generer_facture = false;
        $this->remise_globale = '0';
        $this->stocksDisponibles = [];
        $this->resetLigne();
        $this->codeCommande = null;
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------
    public function render()
    {
        return view('livewire.commandes.nouvelle-commande', [
            'clients'     => Client::orderBy('nom')->get(),
            'agents'      => Agent::orderBy('nom')->get(),
            'pointsVente' => PointVente::orderBy('nom')->get(),
        ])->layout('layouts.app');
    }
}