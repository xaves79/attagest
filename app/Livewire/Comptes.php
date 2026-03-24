<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Compte;

#[Layout('components.layouts.app')]
class Comptes extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id' => null,
        'code_compte' => '',
        'libelle' => '',
        'type_compte' => 'actif',
        'solde_debit' => '',
        'solde_credit' => '',
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
        $comptes = Compte::where(function ($query) {
                $query->where('code_compte', 'like', "%{$this->search}%")
                    ->orWhere('libelle', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.comptes.index', compact('comptes'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $compte = Compte::findOrFail($id);
        $this->form = $compte->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $compte = Compte::findOrFail($id);
        $this->form = $compte->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        Compte::findOrFail($id)->delete();
        session()->flash('message', 'Compte supprimé avec succès.');
        $this->resetPage();
    }

    public function save()
	{
		$validated = $this->validate([
			'form.code_compte' => 'required|string|max:255',
			'form.libelle' => 'required|string|max:255',
			'form.type_compte' => 'required|in:actif,passif,charges,produits',
			'form.solde_debit' => 'nullable|numeric|min:0',
			'form.solde_credit' => 'nullable|numeric|min:0',
		]);

		// Convert empty strings to null for numeric fields
		$data = $this->form;
		$data['solde_debit'] = $data['solde_debit'] !== '' ? $data['solde_debit'] : null;
		$data['solde_credit'] = $data['solde_credit'] !== '' ? $data['solde_credit'] : null;

		Compte::updateOrCreate(
			['id' => $data['id'] ?? null],
			$data
		);

		session()->flash('message', isset($data['id']) ? 'Compte mis à jour.' : 'Compte créé.');
		$this->showModal = false;
		$this->resetPage();
	}

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'code_compte' => '',
            'libelle' => '',
            'type_compte' => 'actif',
            'solde_debit' => '',
            'solde_credit' => '',
        ];
    }
}
