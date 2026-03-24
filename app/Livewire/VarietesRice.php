<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\VarieteRice;

#[Layout('components.layouts.app')]
class VarietesRice extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id' => null,
        'nom' => '',
        'code_variete' => '',
        'type_riz' => 'Paddy',
        'rendement_estime' => '',
        'duree_cycle' => '',
        'origine' => '',
        'description' => '',
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
        $varietes = VarieteRice::where(function ($query) {
                $query->where('nom', 'like', "%{$this->search}%")
                    ->orWhere('code_variete', 'like', "%{$this->search}%")
                    ->orWhere('type_riz', 'like', "%{$this->search}%")
                    ->orWhere('origine', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.varietes-rice.index', compact('varietes'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $variete = VarieteRice::findOrFail($id);
        $this->form = $variete->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $variete = VarieteRice::findOrFail($id);
        $this->form = $variete->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        VarieteRice::findOrFail($id)->delete();
        session()->flash('message', 'Variété supprimée avec succès.');
        $this->resetPage();
    }

    public function save()
    {
        $this->validate([
            'form.nom' => 'required|unique:varietes_rice,nom,' . ($this->form['id'] ?? 'null'),
            'form.code_variete' => 'required|unique:varietes_rice,code_variete,' . ($this->form['id'] ?? 'null'),
            'form.type_riz' => 'nullable|in:Paddy,Parboiled,Blanc',
            'form.rendement_estime' => 'nullable|numeric|min:0',
            'form.duree_cycle' => 'nullable|integer|min:1',
            'form.origine' => 'nullable|string|max:50',
            'form.description' => 'nullable|string',
        ]);

        VarieteRice::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Variété mise à jour.' : 'Variété créée.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'nom' => '',
            'code_variete' => '',
            'type_riz' => 'Paddy',
            'rendement_estime' => '',
            'duree_cycle' => '',
            'origine' => '',
            'description' => '',
        ];
    }
}
