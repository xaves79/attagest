<?php

namespace App\Livewire;

use App\Models\FactureClient;
use App\Models\Client;
use App\Models\PointVente;
use App\Models\Agent;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturesClients extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filtres
    public $search = '';
    public $filterClient = '';
    public $filterStatut = '';
    public $filterDate = '';
    public $perPage = 10;

    // Données listes
    public $clients = [];
    public $pointsVente = [];
    public $agents = [];
    public $articles = [];

    // Modal Facture
    public $showModal = false;
    public $viewMode = false;
    public $form = [];
    public $lignes = [];

    // Modal Paiement
    public $showPaiementModal = false;
    public $paiement_facture_id;
    public $paiement_montant = 0;
    public $paiement_mode = 'espèces';
    public $paiement_notes = '';

    public function mount()
    {
        $this->chargerDonnees();
        $this->resetForm();
    }

    protected function chargerDonnees()
    {
        $this->clients = Client::orderBy('nom')->get();
        $this->pointsVente = PointVente::where('actif', true)->get();
        $this->agents = Agent::where('actif', true)->get();
        $this->articles = Article::all();
    }

    public function render()
    {
        $query = FactureClient::with(['client', 'paiements'])
            ->when($this->search, function($q) {
                $q->where('numero_facture', 'like', "%{$this->search}%")
                  ->orWhereHas('client', fn($c) => $c->where('nom', 'like', "%{$this->search}%"));
            })
            ->when($this->filterClient, fn($q) => $q->where('client_id', $this->filterClient))
            ->when($this->filterStatut, fn($q) => $q->where('statut', $this->filterStatut))
            ->when($this->filterDate, fn($q) => $q->whereDate('date_facture', $this->filterDate))
            ->orderBy('created_at', 'desc');

        return view('livewire.factures-clients.index', [
            'factures' => $query->paginate($this->perPage)
        ]);
    }

    public function ouvrirModalPaiement($id)
    {
        $facture = FactureClient::findOrFail($id);
        $this->paiement_facture_id = $id;
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

		$facture = FactureClient::findOrFail($this->paiement_facture_id);

		if ($this->paiement_montant > $facture->solde_restant) {
			$this->addError('paiement_montant', 'Le montant dépasse le solde restant.');
			return;
		}

		try {
			DB::transaction(function () use ($facture) {
				$numeroPaiement = 'PAY-' . now()->format('YmdHis') . '-' . uniqid() . '-' . rand(100, 999);

				DB::table('paiements_factures')->insert([
					'facture_id'      => $facture->id,
					'numero_paiement' => $numeroPaiement,
					'montant_paye'    => $this->paiement_montant,
					'date_paiement'   => now()->format('Y-m-d'),
					'mode_paiement'   => $this->paiement_mode,
					'description'     => $this->paiement_notes ?? null,
					'statut'          => 'paye',
					'created_at'      => now(),
					'updated_at'      => now(),
				]);

				$facture->increment('montant_paye', $this->paiement_montant);
				$facture->decrement('solde_restant', $this->paiement_montant);
				$facture->refresh();

				if ($facture->solde_restant <= 0) {
					$facture->update(['statut' => 'payee']);
				} else {
					$facture->update(['statut' => 'partiel']);
				}
			});

			session()->flash('message', 'Paiement enregistré avec succès.');
			$this->dispatch('factures-mises-a-jour');
			$this->fermerModalPaiement();

		} catch (\Exception $e) {
			\Log::error('Erreur paiement: ' . $e->getMessage(), [
				'facture_id' => $this->paiement_facture_id,
				'montant'    => $this->paiement_montant,
			]);
			session()->flash('error', 'Erreur : ' . $e->getMessage());
		}
	}

    public function fermerModalPaiement()
    {
        $this->showPaiementModal = false;
        $this->reset(['paiement_montant', 'paiement_mode', 'paiement_notes', 'paiement_facture_id']);
    }

    public function resetForm()
	{
		$this->form = [
			'id' => null,  // 👈 Ajoutez cette ligne
			'numero_facture' => 'FACT-' . now()->format('Ymd-His'),
			'client_id'      => '',
			'date_facture'   => now()->format('Y-m-d'),
			'statut'         => 'credit',
		];
		$this->lignes = [];
	}

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }
	
	public function show($id)
	{
		$facture = FactureClient::with('lignes')->findOrFail($id);
		$this->form = $facture->toArray();
		$this->lignes = $facture->lignes->map(fn($l) => [
			'id'            => $l->id,
			'article_id'    => $l->article_id,
			'type_produit'  => $l->type_produit,
			'poids_sac_kg'  => $l->poids_sac_kg,
			'unite'         => $l->unite,
			'description'   => $l->description,
			'quantite'      => $l->quantite,
			'prix_unitaire' => $l->prix_unitaire,
			'montant'       => $l->montant,
		])->toArray();
		$this->showModal = true;
		$this->viewMode = true;
	}

	public function edit($id)
	{
		$facture = FactureClient::with('lignes')->findOrFail($id);
		// Empêcher modification si des paiements existent
		if ($facture->paiements()->exists()) {
			session()->flash('error', 'Impossible de modifier une facture avec des paiements.');
			return;
		}
		$this->form = $facture->toArray();
		$this->lignes = $facture->lignes->map(fn($l) => [
			'article_id' => $l->article_id,
			'quantite' => $l->quantite,
			'prix_unitaire' => $l->prix_unitaire,
			'montant' => $l->montant,
		])->toArray();
		$this->showModal = true;
		$this->viewMode = false;
	}

	public function delete($id)
	{
		$facture = FactureClient::findOrFail($id);
		if ($facture->paiements()->exists()) {
			session()->flash('error', 'Impossible de supprimer une facture avec des paiements.');
			return;
		}
		$facture->delete();
		session()->flash('message', 'Facture supprimée.');
	}

	public function telechargerPdf($id)
	{
		$facture = FactureClient::with(['client', 'pointVente', 'agent', 'lignes.article'])->findOrFail($id);
		$pdf = Pdf::loadView('pdf.facture-client', compact('facture'));
		$pdf->setPaper('A4', 'portrait');
		return response()->streamDownload(
			fn() => print($pdf->output()),
			"facture_{$facture->numero_facture}.pdf"
		);
	}

    public function resetFilters()
    {
        $this->reset(['search', 'filterClient', 'filterStatut', 'filterDate']);
    }

    // Stubs pour éviter les erreurs si appelés
    //public function show($id) { /* logique détail */ }
    //public function edit($id) { /* logique edit */ }
    //public function delete($id) { /* logique delete */ }
    //public function telechargerPdf($id) { /* logique pdf */ }
}