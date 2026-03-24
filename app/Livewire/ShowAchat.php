<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ShowAchat extends Component
{
    public int    $id;
    public string $successMessage = '';
    public string $errorMessage   = '';

    // Paiement fournisseur
    public bool   $showPaiementForm  = false;
    public string $montant_paiement  = '0';
    public string $mode_paiement     = 'espece';
    public string $date_paiement     = '';
    public string $note_paiement     = '';

    public function mount(int $id): void
    {
        $this->id            = $id;
        $this->date_paiement = now()->format('Y-m-d');
    }

    private function getLot(): object|null
    {
        return DB::table('lots_paddy as lp')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->leftJoin('agents as a', 'a.id', '=', 'lp.agent_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->leftJoin('localites as l', 'l.id', '=', 'lp.localite_id')
            ->select(
                'lp.*',
                'f.nom as fournisseur_nom', 'f.prenom as fournisseur_prenom',
                'f.telephone as fournisseur_tel', 'f.type_personne',
                'a.nom as agent_nom', 'a.prenom as agent_prenom',
                'v.nom as variete_nom',
                'l.nom as localite_nom'
            )
            ->where('lp.id', $this->id)
            ->first();
    }

    private function getRecu(): object|null
    {
        return DB::table('recus_fournisseurs as r')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'r.fournisseur_id')
            ->select('r.*', 'f.nom as fournisseur_nom')
            ->where('r.achat_paddy_id', $this->id)
            ->first();
    }

    private function getStock(): object|null
    {
        return DB::table('stocks_paddy as s')
            ->leftJoin('agents as a', 'a.id', '=', 's.agent_id')
            ->select('s.*', 'a.nom as agent_nom', 'a.prenom as agent_prenom')
            ->where('s.lot_paddy_id', $this->id)
            ->first();
    }

    private function getPaiements(): \Illuminate\Support\Collection
    {
        $recu = $this->getRecu();
        if (!$recu) return collect();
        return DB::table('paiements_fournisseurs')
            ->where('recu_fournisseur_id', $recu->id)
            ->orderByDesc('date_paiement')
            ->get();
    }

    public function marquerLivre(): void
    {
        DB::table('lots_paddy')->where('id', $this->id)->update([
            'statut'     => 'disponible',
            'updated_at' => now(),
        ]);
        DB::table('stocks_paddy')->where('lot_paddy_id', $this->id)->update([
            'updated_at' => now(),
        ]);
        $this->successMessage = 'Lot marqué comme livré et disponible.';
    }

    public function enregistrerPaiement(): void
    {
        $this->errorMessage = '';
        $recu = $this->getRecu();
        if (!$recu) {
            $this->errorMessage = 'Aucun reçu associé.';
            return;
        }

        $montant = (int)$this->montant_paiement;
        if ($montant <= 0) {
            $this->errorMessage = 'Montant invalide.';
            return;
        }

        $solde = (float)$recu->solde_du;
        if ($montant > $solde) {
            $this->errorMessage = "Montant ({$montant}) supérieur au solde ({$solde}).";
            return;
        }

        try {
            DB::transaction(function () use ($recu, $montant, $solde) {
                DB::table('paiements_fournisseurs')->insert([
                    'recu_fournisseur_id' => $recu->id,
                    'montant'             => $montant,
                    'date_paiement'       => $this->date_paiement,
                    'mode_paiement'       => $this->mode_paiement,
                    'notes'               => $this->note_paiement ?: null,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                $nouveauSolde = max(0, $solde - $montant);
                DB::table('recus_fournisseurs')->where('id', $recu->id)->update([
                    'acompte'    => $recu->acompte + $montant,
                    'solde_du'   => $nouveauSolde,
                    'paye'       => $nouveauSolde <= 0,
                    'updated_at' => now(),
                ]);
            });

            $this->successMessage    = "Paiement de " . number_format($montant, 0, ',', ' ') . " FCFA enregistré.";
            $this->showPaiementForm  = false;
            $this->montant_paiement  = '0';

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function render()
    {
        $lot      = $this->getLot();
        $recu     = $this->getRecu();
        $stock    = $this->getStock();
        $paiements = $this->getPaiements();

        if (!$lot) abort(404);

        // Calculs
        $pct_consomme = $lot->quantite_achat_kg > 0
            ? round((1 - $lot->quantite_restante_kg / $lot->quantite_achat_kg) * 100)
            : 0;

        $pct_paye = $recu && $recu->montant_total > 0
            ? round(($recu->acompte / $recu->montant_total) * 100)
            : 0;

        return view('livewire.achats.show-achat', compact(
            'lot', 'recu', 'stock', 'paiements', 'pct_consomme', 'pct_paye'
        ))->layout('layouts.app');
    }
}