<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PaiementFacture;
use App\Services\FactureClientService;

#[Layout('components.layouts.app')]
class PaiementsFactures extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'              => null,
        'facture_id'      => '',
        'numero_paiement' => '',
        'montant_paye'    => '',
        'date_paiement'   => '',
        'mode_paiement'   => 'espèces',
        'description'     => '',
        'statut'          => 'paye',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $paiements = PaiementFacture::with(['facture'])
            ->where(function ($query) {
                $query->where('numero_paiement', 'like', "%{$this->search}%")
                    ->orWhereHas('facture', fn ($q) => $q->where('numero_facture', 'like', "%{$this->search}%"));
            })
            ->latest()
            ->paginate(10);

        $factures = \App\Models\FactureClient::all();

        return view('livewire.paiements-factures.index', compact(
            'paiements',
            'factures'
        ));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $paiement = PaiementFacture::findOrFail($id);
        $this->form = $paiement->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $paiement = PaiementFacture::findOrFail($id);
        $this->form = $paiement->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        PaiementFacture::findOrFail($id)->delete();
        session()->flash('message', 'Paiement supprimé avec succès.');
        $this->resetPage();
    }
	
	public function enregistrerPaiement()
	{
		$this->validate([
			'montant' => 'required|numeric|min:0.01',
			'mode'    => 'required|in:espèces,mobile_money,chèque,virement',
		]);

		$facture = FactureClient::findOrFail($this->facture_id);
		$service = new FactureClientService();

		try {
			$service->enregistrerPaiement(
				$facture,
				$this->montant,
				$this->mode,
				$this->reference,
				$this->description
			);
			session()->flash('message', 'Paiement enregistré.');
		} catch (\Exception $e) {
			session()->flash('error', $e->getMessage());
		}

		$this->reset(['montant', 'mode', 'reference', 'description']);
		$this->showModal = false;
	}

    public function save()
    {
        $this->validate([
            'form.facture_id'      => 'required|exists:factures_clients,id',
            'form.numero_paiement' => 'required|unique:paiements_factures,numero_paiement,' . ($this->form['id'] ?? 'null'),
            'form.montant_paye'    => 'required|numeric|min:0.01',
            'form.date_paiement'   => 'required|date',
            'form.mode_paiement'   => 'required|in:espèces,mobile_money,chèque,virement',
            'form.statut'          => 'required|in:paye,annule,reporte',
            'form.description'     => 'nullable|string',
        ]);

        PaiementFacture::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Paiement mis à jour.' : 'Paiement créé.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id'              => null,
            'facture_id'      => '',
            'numero_paiement' => '',
            'montant_paye'    => '',
            'date_paiement'   => now()->format('Y-m-d'),
            'mode_paiement'   => 'espèces',
            'description'     => '',
            'statut'          => 'paye',
        ];
    }
}
