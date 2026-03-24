<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PointVente;
use App\Models\Agent;
use App\Models\Localite;

#[Layout('components.layouts.app')]
class PointsVente extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id' => null,
        'nom' => '',
        'code_point' => '',
        'agent_id' => '',
        'localite_id' => '',
        'adresse' => '',
        'telephone' => '',
        'whatsapp' => '',
        'email' => '',
        'actif' => true,
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Méthode appelée via wire:change sur le select localité
    public function generateCodeForLocalite($localiteId)
	{
		if ($localiteId) {
			$localite = \App\Models\Localite::find($localiteId);
			if ($localite) {
				// Générer un code basé sur la localité et un timestamp
				$prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $localite->nom), 0, 3));
				$timestamp = now()->format('ymdHis');
				$this->form['code_point'] = "PTV-{$prefix}-{$timestamp}";
			} else {
				$this->form['code_point'] = '';
			}
		} else {
			$this->form['code_point'] = '';
		}
	}

    public function render()
    {
        $points = PointVente::with(['agent', 'localite'])
            ->where(function ($query) {
                $query->where('nom', 'like', "%{$this->search}%")
                    ->orWhere('code_point', 'like', "%{$this->search}%")
                    ->orWhereHas('agent', fn ($q) => $q->whereRaw("concat(prenom, ' ', nom) ilike ?", ["%{$this->search}%"]))
                    ->orWhereHas('localite', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"));
            })
            ->latest()
            ->paginate(10);

        $agents = Agent::all();
        $localites = Localite::all();

        return view('livewire.points-vente.index', compact('points', 'agents', 'localites'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $point = PointVente::findOrFail($id);
        $this->form = $point->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $point = PointVente::findOrFail($id);
        $this->form = $point->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        PointVente::findOrFail($id)->delete();
        session()->flash('message', 'Point de vente supprimé avec succès.');
        $this->resetPage();
    }

    public function save()
	{
		// Si la localité est choisie mais que le code est vide (cas improbable), on le génère
		if ($this->form['localite_id'] && empty($this->form['code_point'])) {
			$this->generateCodeForLocalite($this->form['localite_id']);
		}

		// Convertir les champs vides en null pour les clés étrangères
		$this->form['agent_id'] = $this->form['agent_id'] ?: null;
		$this->form['localite_id'] = $this->form['localite_id'] ?: null;

		$this->validate([
			'form.nom' => 'required|string|max:100',
			'form.code_point' => 'required|unique:points_vente,code_point,' . ($this->form['id'] ?? 'null'),
			'form.agent_id' => 'nullable|exists:agents,id',
			'form.localite_id' => 'nullable|exists:localites,id',
			'form.adresse' => 'nullable|string',
			'form.telephone' => 'nullable|string|max:20',
			'form.whatsapp' => 'nullable|string|max:20',
			'form.email' => 'nullable|email|max:100',
			'form.actif' => 'required|boolean',
		]);

		PointVente::updateOrCreate(
			['id' => $this->form['id']],
			$this->form
		);

		session()->flash('message', $this->form['id'] ? 'Point de vente mis à jour.' : 'Point de vente créé.');
		$this->showModal = false;
		$this->resetPage();
	}

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'nom' => '',
            'code_point' => '',
            'agent_id' => '',
            'localite_id' => '',
            'adresse' => '',
            'telephone' => '',
            'whatsapp' => '',
            'email' => '',
            'actif' => true,
        ];
    }
}