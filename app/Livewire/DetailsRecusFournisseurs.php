<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\DetailsRecuFournisseur;

#[Layout('components.layouts.app')]
class DetailsRecusFournisseurs extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $isEditing = false;

    public $form = [
        'id' => null,
        'recu_id' => '',
        'description' => '',
        'variete_rice_id' => '',
        'quantite' => '',
        'prix_unitaire' => '',
        'sous_total' => '',
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
        $details = DetailsRecuFournisseur::with([
                'recu',
                'variete',
            ])
            ->where(function ($query) {
                $query->whereHas('recu', fn ($q) => $q->where('numero_recu', 'like', "%{$this->search}%"))
                      ->orWhereHas('variete', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"));
            })
            ->latest()
            ->paginate(10);

        $recus    = \App\Models\RecuFournisseur::all();
        $varietes = \App\Models\VarieteRice::all();

        return view('livewire.details-recus-fournisseurs.index', compact(
            'details',
            'recus',
            'varietes'
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
        $detail = DetailsRecuFournisseur::findOrFail($id);
        $this->form = $detail->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $detail = DetailsRecuFournisseur::findOrFail($id);
        $this->form = $detail->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        DetailsRecuFournisseur::findOrFail($id)->delete();
        session()->flash('message', 'Détail reçu fournisseur supprimé avec succès.');
        $this->resetPage();
    }
	
	public function closeModal()
	{
		$this->showModal = false;
		$this->viewMode   = false;
		$this->isEditing  = false;
		$this->resetForm();
	}

    public function updatedFormQuantite()
    {
        if ($this->form['quantite'] && $this->form['prix_unitaire']) {
            $this->form['sous_total'] = $this->form['quantite'] * $this->form['prix_unitaire'];
        }
    }

    public function updatedFormPrixUnitaire()
    {
        if ($this->form['quantite'] && $this->form['prix_unitaire']) {
            $this->form['sous_total'] = $this->form['quantite'] * $this->form['prix_unitaire'];
        }
    }

    public function save()
    {
        $this->validate([
            'form.recu_id'          => 'required|exists:recus_fournisseurs,id',
            'form.variete_rice_id'  => 'required|exists:varietes_rice,id',
            'form.quantite'         => 'required|numeric|min:0.01',
            'form.prix_unitaire'    => 'required|numeric|min:0.01',
            'form.sous_total'       => 'required|integer|min:1',
            'form.description'      => 'nullable|string',
        ]);

        DetailsRecuFournisseur::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Détail reçu mis à jour.' : 'Détail reçu créé.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'recu_id' => '',
            'description' => '',
            'variete_rice_id' => '',
            'quantite' => '',
            'prix_unitaire' => '',
            'sous_total' => '',
        ];
    }
}
