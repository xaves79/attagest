<?php

namespace App\Livewire;

use App\Models\Poste;
use Livewire\Component;
use Livewire\WithPagination;

class Postes extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id'          => null,
        'libelle'     => '',
        'description' => '',
        'actif'       => true,
    ];

    public $showModal = false;

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = Poste::query();

        if ($this->search) {
            $query->where('libelle', 'like', "%{$this->search}%");
        }

        return view('livewire.postes', [
            'postes' => $query->paginate(10),
        ]);
    }

    public function resetForm()
    {
        $this->form = [
            'id'          => null,
            'libelle'     => '',
            'description' => '',
            'actif'       => true,
        ];
        $this->showModal = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $poste = Poste::findOrFail($id);
        $this->form = $poste->toArray();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'form.libelle'    => 'required|string|max:50|unique:postes,libelle,' . ($this->form['id'] ?? 'NULL'),
            'form.description' => 'nullable|string',
            'form.actif'      => 'required|boolean',
        ]);

		Poste::updateOrCreate(
			['id' => $this->form['id']],
			$this->form
		);

		session()->flash('message', $this->form['id'] ? 'Poste mis à jour.' : 'Poste créé.');
		$this->resetForm();
		$this->dispatch('poste-saved');
	}

	public function delete($id)
	{
		Poste::findOrFail($id)->delete();
		session()->flash('message', 'Poste supprimé.');
		$this->dispatch('poste-deleted');
	}
}
