<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Reservoir;
use App\Models\PointVente;

class Reservoirs extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'                   => null,
        'nom_reservoir'        => '',
        'type_produit'         => 'riz_blanc',
        'capacite_max_kg'      => '',
        'point_vente_id'       => '',
        'quantite_actuelle_kg' => 0,
    ];

    protected function rules()
    {
        return [
            'form.nom_reservoir'   => 'required|string|max:50|unique:reservoirs,nom_reservoir,' . ($this->form['id'] ?? 'NULL'),
            'form.type_produit'    => 'required|in:riz_blanc,son,brisures,rejet',
            'form.capacite_max_kg' => 'required|numeric|min:0.1',
            'form.point_vente_id'  => 'nullable|exists:points_vente,id',
            'form.quantite_actuelle_kg' => 'nullable|numeric|min:0',
        ];
    }

    protected $validationAttributes = [
        'form.nom_reservoir'   => 'nom du réservoir',
        'form.type_produit'    => 'type de produit',
        'form.capacite_max_kg' => 'capacité maximale',
        'form.point_vente_id'  => 'point de vente',
        'form.quantite_actuelle_kg' => 'quantité actuelle',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $reservoirs = Reservoir::with('pointVente')
            ->where('nom_reservoir', 'like', "%{$this->search}%")
            ->orWhereHas('pointVente', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"))
            ->orderBy('nom_reservoir')
            ->paginate(10);

        $pointsVente = PointVente::orderBy('nom')->get();

        return view('livewire.reservoirs.index', [
            'reservoirs'   => $reservoirs,
            'pointsVente'  => $pointsVente,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $reservoir = Reservoir::findOrFail($id);
        $this->form = $reservoir->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $reservoir = Reservoir::findOrFail($id);
        $this->form = $reservoir->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        $reservoir = Reservoir::findOrFail($id);
        $reservoir->delete();
        session()->flash('message', 'Réservoir supprimé avec succès.');
    }

    public function save()
    {
        // Nettoyage des virgules
        $numericFields = ['capacite_max_kg', 'quantite_actuelle_kg'];
        foreach ($numericFields as $field) {
            if (isset($this->form[$field]) && is_string($this->form[$field])) {
                $this->form[$field] = str_replace(',', '.', $this->form[$field]);
                $this->form[$field] = (float) $this->form[$field];
            }
        }

        // Si quantité actuelle est vide, on met 0
        if ($this->form['quantite_actuelle_kg'] === '' || $this->form['quantite_actuelle_kg'] === null) {
            $this->form['quantite_actuelle_kg'] = 0;
        }

        $this->validate();

        Reservoir::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Réservoir mis à jour.' : 'Réservoir créé.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id'                   => null,
            'nom_reservoir'        => '',
            'type_produit'         => 'riz_blanc',
            'capacite_max_kg'      => '',
            'point_vente_id'       => '',
            'quantite_actuelle_kg' => 0,
        ];
    }
}