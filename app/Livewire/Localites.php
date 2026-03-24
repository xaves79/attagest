<?php

namespace App\Livewire;

use App\Models\Localite;
use Livewire\Component;
use Livewire\WithPagination;

class Localites extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'     => null,
        'nom'    => '',
        'region' => '',
    ];

    protected function rules()
    {
        return [
            'form.nom'    => 'required|string|max:100|unique:localites,nom,' . ($this->form['id'] ?? 'NULL') . ',id,region,' . ($this->form['region'] ?? ''),
            'form.region' => 'required|string|max:100',
        ];
    }

    protected $validationAttributes = [
        'form.nom'    => 'nom',
        'form.region' => 'région',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $localites = Localite::query()
            ->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('region', 'like', "%{$this->search}%");
            })
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.localites', compact('localites'));
    }

    public function resetForm()
    {
        $this->form = [
            'id'     => null,
            'nom'    => '',
            'region' => '',
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $localite = Localite::findOrFail($id);
        $this->form = $localite->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
    {
        $localite = Localite::findOrFail($id);
        $this->form = $localite->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function delete($id)
    {
        $localite = Localite::findOrFail($id);
        $localite->delete();
        session()->flash('message', 'Localité supprimée avec succès.');
    }

    public function save()
    {
        $this->validate();

        if ($this->form['id']) {
            $localite = Localite::findOrFail($this->form['id']);
            $localite->update($this->form);
            session()->flash('message', 'Localité mise à jour.');
        } else {
            Localite::create($this->form);
            session()->flash('message', 'Localité créée.');
        }

        $this->resetForm();
        $this->showModal = false;
    }
}