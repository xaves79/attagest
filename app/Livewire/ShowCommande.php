<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\CommandeVente;
use App\Models\FactureClient;
use App\Models\PaiementFacture;
use App\Models\LivraisonVente;
use App\Models\LigneCommandeVente;

class ShowCommande extends Component
{
    public CommandeVente $commande;

    // Modal paiement
    public $showPaiementModal = false;
    public $paiement_montant = 0;
    public $paiement_mode = 'espèces';
    public $paiement_notes = '';
    public $paiement_facture_id;

    public function mount($id)
    {
        $this->commande = CommandeVente::with(['client', 'agent', 'pointVente', 'lignes.sac', 'livraisons.agent', 'facture'])
            ->findOrFail($id);
    }

    public function ouvrirPaiementModal()
    {
        if (!$this->commande->facture_id) {
            session()->flash('error', 'Cette commande n’a pas de facture associée.');
            return;
        }
        $facture = FactureClient::find($this->commande->facture_id);
        $this->paiement_facture_id = $facture->id;
        $this->paiement_montant = $facture->solde_restant;
        $this->paiement_mode = 'espèces';
        $this->paiement_notes = '';
        $this->showPaiementModal = true;
    }

    public function enregistrerPaiement()
	{
		$this->validate([
			'paiement_montant' => 'required|numeric|min:1',
			'paiement_mode'    => 'required|in:espèces,mobile_money,chèque,virement',
		]);

		$facture = FactureClient::find($this->paiement_facture_id);

		if ($this->paiement_montant > $facture->solde_restant) {
			$this->addError('paiement_montant', 'Montant dépasse le solde restant.');
			return;
		}

		try {
			DB::transaction(function () use ($facture) {
				$numeroPaiement = 'PAY-' . now()->format('YmdHis') . '-' . uniqid();
				PaiementFacture::create([
					'facture_id'      => $facture->id,
					'numero_paiement' => $numeroPaiement,
					'montant_paye'    => $this->paiement_montant,
					'date_paiement'   => now()->format('Y-m-d'),
					'mode_paiement'   => $this->paiement_mode,
					'description'     => $this->paiement_notes ?? null,
					'statut'          => 'paye',
				]);

				$facture->increment('montant_paye', $this->paiement_montant);
				$facture->decrement('solde_restant', $this->paiement_montant);
				$facture->update(['statut' => $facture->solde_restant <= 0 ? 'payee' : 'partiel']);

				// Mettre à jour la commande (acompte seulement, pas le statut)
				$this->commande->update([
					'montant_acompte_fcfa' => $facture->montant_paye,
					// On ne modifie pas le statut de la commande
				]);
			});

			session()->flash('message', 'Paiement enregistré avec succès.');
			$this->showPaiementModal = false;
			$this->commande->refresh();

		} catch (\Exception $e) {
			\Log::error('Erreur paiement: ' . $e->getMessage());
			session()->flash('error', 'Erreur lors du paiement.');
		}
	}

    public function render()
    {
        return view('livewire.commandes.show-commande')->layout('layouts.app');
    }
}