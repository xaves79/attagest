<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\CommandeVente;
use App\Models\LigneCommandeVente;
use App\Models\LivraisonVente;
use App\Models\StockSac;

class LivrerCommande extends Component
{
    public int    $commandeId;
    public array  $lignesLivraison = [];
    public string $notes           = '';
    public string $successMessage  = '';
    public string $errorMessage    = '';
    public bool   $estEnregistree  = false;

    public function mount(int $id): void
    {
        $this->commandeId = $id;
        $this->initialiserLignes();
    }

    private function initialiserLignes(): void
    {
        $commande = CommandeVente::with(['lignes.sac'])->findOrFail($this->commandeId);

        if (!in_array($commande->statut, ['confirmee', 'en_attente_livraison', 'partiellement_livree'])) {
            $this->estEnregistree = true;
            return;
        }

        foreach ($commande->lignes as $ligne) {
            $restante = max(0, $ligne->quantite - $ligne->quantite_livree);
            if ($restante <= 0) continue;

            $stockSac = null;
            if ($ligne->sac_id) {
                $stockSac = StockSac::where('sac_id', $ligne->sac_id)
                    ->where('point_vente_id', $commande->point_vente_id)
                    ->first();
            }

            $this->lignesLivraison[$ligne->id] = [
                'ligne_id'          => $ligne->id,
                'code_sac'          => $ligne->sac?->code_sac ?? '—',
                'type_produit'      => $ligne->type_produit,
                'poids_sac_kg'      => $ligne->poids_sac_kg,
                'unite'             => $ligne->unite ?? 'sac',
                'quantite_cmd'      => $ligne->quantite,
                'quantite_livree'   => $ligne->quantite_livree,
                'quantite_restante' => $restante,
                'quantite_a_livrer' => $restante,
                'stock_sac_id'      => $stockSac?->id,
                'stock_disponible'  => $stockSac?->quantite ?? 0,
                'sac_id'            => $ligne->sac_id,
            ];
        }
    }

    public function toutLivrer(): void
    {
        foreach ($this->lignesLivraison as $ligneId => &$data) {
            $data['quantite_a_livrer'] = min(
                $data['quantite_restante'],
                max($data['stock_disponible'], $data['quantite_restante'])
            );
        }
    }

    public function enregistrer(): void
    {
        $this->errorMessage   = '';
        $this->successMessage = '';

        $lignesALivrer = [];
        foreach ($this->lignesLivraison as $ligneId => $data) {
            $qte = (int) $data['quantite_a_livrer'];
            if ($qte <= 0) continue;

            if ($qte > $data['quantite_restante']) {
                $this->errorMessage = "Quantité ({$qte}) > restant ({$data['quantite_restante']}) pour {$data['code_sac']}.";
                return;
            }

            if ($data['stock_sac_id'] && $qte > $data['stock_disponible']) {
                $this->errorMessage = "Stock insuffisant pour {$data['code_sac']} : dispo {$data['stock_disponible']}, demandé {$qte}.";
                return;
            }

            $lignesALivrer[] = [
                'ligne_commande_id' => $ligneId,
                'stock_sac_id'      => $data['stock_sac_id'],
                'quantite_livree'   => $qte,
            ];
        }

        if (empty($lignesALivrer)) {
            $this->errorMessage = 'Aucune quantité à livrer saisie.';
            return;
        }

        try {
            $commande  = CommandeVente::findOrFail($this->commandeId);
            $livraison = LivraisonVente::creer(
                $commande,
                $lignesALivrer,
                $commande->agent_id,
                $this->notes ?: null
            );

            $this->successMessage = "Livraison {$livraison->code_livraison} enregistrée. Stock débité automatiquement.";
            $this->lignesLivraison = [];
            $this->initialiserLignes();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function render()
    {
        $commande = CommandeVente::with([
            'client',
            'pointVente',
            'livraisons.lignes',
        ])->find($this->commandeId);

        return view('livewire.commandes.livrer-commande', compact('commande'))
            ->layout('layouts.app');
    }
}