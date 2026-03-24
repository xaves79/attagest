<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\TransfertPointsVente;

#[Layout('components.layouts.app')]
class TransfertsPointsVente extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id' => null,
        'code_transfert' => '',
        'stock_riz_id' => '',
        'point_vente_id' => '',
        'agent_id' => '',
        'quantite_transferee_kg' => '',
        'date_transfert' => '',
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
        $transferts = TransfertPointsVente::with([
                'stockRiz',
                'pointVente',
                'agent',
            ])
            ->where(function ($query) {
                $query->where('code_transfert', 'like', "%{$this->search}%")
                    ->orWhereHas('stockRiz', fn ($q) => $q->where('code_stock', 'like', "%{$this->search}%"))
                    ->orWhereHas('pointVente', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"))
                    ->orWhereHas('agent', fn ($q) => $q->whereRaw("concat(prenom, ' ', nom) ilike ?", ["%{$this->search}%"]));
            })
            ->latest()
            ->paginate(10);

        $stocks_riz = \App\Models\StockProduitFini::where('type_produit', 'riz_blanc')->get();
        $points_vente = \App\Models\PointVente::all();
        $agents = \App\Models\Agent::all();

        return view('livewire.transferts-points-vente.index', compact(
            'transferts',
            'stocks_riz',
            'points_vente',
            'agents'
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
        $transfert = TransfertPointsVente::findOrFail($id);
        $this->form = $transfert->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $transfert = TransfertPointsVente::findOrFail($id);
        $this->form = $transfert->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        TransfertPointsVente::findOrFail($id)->delete();
        session()->flash('message', 'Transfert supprimé avec succès.');
        $this->resetPage();
    }

    public function save()
    {
        $this->validate([
            'form.code_transfert' => 'required|unique:transferts_points_vente,code_transfert,' . ($this->form['id'] ?? 'null'),
            'form.stock_riz_id' => 'required|exists:stocks_produits_finis,id',
            'form.point_vente_id' => 'required|exists:points_vente,id',
            'form.agent_id' => 'required|exists:agents,id',
            'form.quantite_transferee_kg' => 'required|numeric|min:0.1',
            'form.date_transfert' => 'required|date',
        ]);

        TransfertPointsVente::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Transfert mis à jour.' : 'Transfert créé.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'code_transfert' => '',
            'stock_riz_id' => '',
            'point_vente_id' => '',
            'agent_id' => '',
            'quantite_transferee_kg' => '',
            'date_transfert' => now()->format('Y-m-d'),
        ];
    }
}
