<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\PieceComptable;

#[Layout('components.layouts.app')]
class PiecesComptables extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id'          => null,
        'code'        => '',
        'libelle'     => '',
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
        $pieces = PieceComptable::where(function ($q) {
                $q->where('code', 'like', "%{$this->search}%")
                  ->orWhere('libelle', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.pieces-comptables.index', compact('pieces'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $piece = PieceComptable::findOrFail($id);
        $this->form = $piece->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $piece = PieceComptable::findOrFail($id);
        $this->form = $piece->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        PieceComptable::findOrFail($id)->delete();
        session()->flash('message', 'Pièce comptable supprimée avec succès.');
        $this->resetPage();
    }

    public function save()
    {
        $this->validate([
            'form.code'        => 'required|unique:pieces_comptables,code,' . ($this->form['id'] ?? 'null'),
            'form.libelle'     => 'required|string|max:200',
            'form.description' => 'nullable|string',
        ]);

        PieceComptable::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Pièce comptable mise à jour.' : 'Pièce comptable créée.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id'          => null,
            'code'        => '',
            'libelle'     => '',
            'description' => '',
        ];
    }
}
