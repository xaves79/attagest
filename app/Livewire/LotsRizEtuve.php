<?php

namespace App\Livewire;

use App\Models\Etuvage;
use App\Models\LotRizEtuve;
use App\Models\VarieteRice;
use Livewire\Component;
use Livewire\WithPagination;

class LotsRizEtuve extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id'                    => null,
        'code_lot'              => '',
        'quantite_entree_kg'    => '',
        'quantite_restante_kg'  => '',
        'masse_apres_kg'        => '',
        'provenance_etuvage_id' => '',
        'variete_rice_id'       => '',
        'date_production'       => '',
    ];

    public $showModal = false;
    public $viewMode = false;
    public $etuvages = [];
    public $varietes = [];

    public function mount()
    {
        $this->etuvages = Etuvage::where('statut', 'termine')->orderBy('code_etuvage')->get();
        $this->varietes = VarieteRice::orderBy('nom')->get();
        $this->resetForm();
    }

    public function render()
    {
        $query = LotRizEtuve::with(['etuvage', 'variete']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code_lot', 'like', "%{$this->search}%");
            })->orWhereHas('etuvage', fn($q) => $q->where('code_etuvage', 'like', "%{$this->search}%"));
        }

        $lots = $query->paginate(10);

        return view('livewire.lots-riz-etuve.index', [
            'lots' => $lots,
        ])->layout('layouts.app');
    }

    public function resetForm()
    {
        $this->form = [
            'id'                    => null,
            'code_lot'              => 'LRZ-' . now()->format('Ymd-His'),
            'quantite_entree_kg'    => '',
            'quantite_restante_kg'  => '',
            'masse_apres_kg'        => '',
            'provenance_etuvage_id' => '',
            'variete_rice_id'       => '',
            'date_production'       => now()->format('Y-m-d H:i'),
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function show($id)
    {
        $lot = LotRizEtuve::with(['etuvage', 'variete'])->findOrFail($id);
        $this->form = $lot->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
    {
        $lot = LotRizEtuve::with(['etuvage', 'variete'])->findOrFail($id);
        $this->form = $lot->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }
	
	public function updatedFormProvenanceEtuvageId($value)
	{
		if ($value) {
			$etuvage = Etuvage::with('stockPaddy.achat')->find($value);

			$this->form['quantite_entree_kg'] = $etuvage?->masse_sortie_kg ?? 0;
			$this->form['variete_rice_id']   = $etuvage?->stockPaddy?->achat?->variete_rice_id ?? null;
		} else {
			$this->form['quantite_entree_kg'] = '';
			$this->form['variete_rice_id']   = '';
		}
	}

    public function save()
    {
        $rules = [
            'form.code_lot'              => 'required|string|max:25|unique:lots_riz_etuve,code_lot,' . ($this->form['id'] ?? 'NULL'),
            'form.quantite_entree_kg'    => 'required|numeric|min:0',
            'form.quantite_restante_kg'  => 'required|numeric|min:0',
            'form.masse_apres_kg'        => 'nullable|numeric|min:0',
            'form.provenance_etuvage_id' => 'required|integer|exists:etuvages,id',
            'form.variete_rice_id'       => 'nullable|integer|exists:varietes_rice,id',
            'form.date_production'       => 'nullable|date',
        ];

        $this->validate($rules);

        LotRizEtuve::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        $this->resetForm();
        session()->flash('message', '✅ Lot riz étuvé enregistré !');
        $this->dispatch('$refresh');
    }

    public function delete($id)
    {
        LotRizEtuve::findOrFail($id)->delete();

        session()->flash('message', '✅ Lot riz étuvé supprimé !');
        $this->dispatch('$refresh');
    }
}
