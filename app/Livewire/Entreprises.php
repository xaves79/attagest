<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Storage;

class Entreprises extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $showModal = false;
    public $showDetailsId = null; // pour le détail sous la ligne

    public $form = [
        'id'              => null,
        'nom'             => '',
        'sigle'           => '',
        'code_entreprise' => '',
        'whatsapp'        => '',
        'telephone'       => '',
        'email'           => '',
        'logo'            => '',
        'adresse'         => '',
        'gerant_nom'      => '',
    ];

    public $logo; // pour l'upload

    protected function rules()
    {
        return [
            'form.nom'             => 'required|string|max:100',
            'form.sigle'           => 'required|string|max:10|unique:entreprises,sigle,' . ($this->form['id'] ?? 'NULL'),
            'form.code_entreprise' => 'required|string|max:10|unique:entreprises,code_entreprise,' . ($this->form['id'] ?? 'NULL'),
            'form.whatsapp'        => 'nullable|string|max:20',
            'form.telephone'       => 'nullable|string|max:20',
            'form.email'           => 'nullable|email|max:100',
            'form.adresse'         => 'nullable|string',
            'form.gerant_nom'      => 'nullable|string|max:100',
            'logo'                 => 'nullable|image|max:1024', // 1 Mo max
        ];
    }

    protected $validationAttributes = [
        'form.nom'             => 'nom',
        'form.sigle'           => 'sigle',
        'form.code_entreprise' => 'code entreprise',
        'form.whatsapp'        => 'WhatsApp',
        'form.telephone'       => 'téléphone',
        'form.email'           => 'email',
        'form.adresse'         => 'adresse',
        'form.gerant_nom'      => 'nom du gérant',
        'logo'                 => 'logo',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $entreprises = Entreprise::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nom', 'like', "%{$this->search}%")
                      ->orWhere('sigle', 'like', "%{$this->search}%")
                      ->orWhere('code_entreprise', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.entreprises.index', compact('entreprises'));
    }

    public function resetForm()
    {
        $this->form = [
            'id'              => null,
            'nom'             => '',
            'sigle'           => '',
            'code_entreprise' => '',
            'whatsapp'        => '',
            'telephone'       => '',
            'email'           => '',
            'logo'            => '',
            'adresse'         => '',
            'gerant_nom'      => '',
        ];
        $this->logo = null;
        $this->showModal = false;
    }

    /**
     * Génère un code entreprise unique (ex: ENT-2024-0001)
     */
    protected function generateCode()
	{
		$prefix = 'ENT' . now()->format('y'); // année sur 2 chiffres (ex: 26)
		$last = Entreprise::where('code_entreprise', 'like', $prefix . '%')
			->orderBy('code_entreprise', 'desc')
			->first();
		if ($last) {
			$num = (int) substr($last->code_entreprise, -4) + 1;
		} else {
			$num = 1;
		}
		return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
	}

    public function create()
    {
        $this->resetForm();
        $this->form['code_entreprise'] = $this->generateCode();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $this->form = $entreprise->toArray();
        $this->showModal = true;
    }

    public function showDetails($id)
    {
        $this->showDetailsId = $this->showDetailsId === $id ? null : $id;
    }

    public function save()
    {
        $this->validate();

        // Si on est en création, on s'assure que le code est bien généré
        if (empty($this->form['id']) && empty($this->form['code_entreprise'])) {
            $this->form['code_entreprise'] = $this->generateCode();
        }

        // Gestion de l'upload du logo
        if ($this->logo) {
            // Supprimer l'ancien logo si existant
            if (!empty($this->form['id']) && !empty($this->form['logo'])) {
                Storage::disk('public')->delete($this->form['logo']);
            }
            $path = $this->logo->store('entreprises', 'public');
            $this->form['logo'] = $path;
        } else {
            // Si pas de nouveau logo et pas d'ID (création), on met null
            if (empty($this->form['id'])) {
                $this->form['logo'] = null;
            }
        }

        // Préparer les données à sauvegarder (on retire l'id qui est géré par Eloquent)
        $data = [
            'nom'             => $this->form['nom'],
            'sigle'           => $this->form['sigle'],
            'code_entreprise' => $this->form['code_entreprise'],
            'whatsapp'        => $this->form['whatsapp'],
            'telephone'       => $this->form['telephone'],
            'email'           => $this->form['email'],
            'logo'            => $this->form['logo'],
            'adresse'         => $this->form['adresse'],
            'gerant_nom'      => $this->form['gerant_nom'],
        ];

        if ($this->form['id']) {
            // Mise à jour
            $entreprise = Entreprise::findOrFail($this->form['id']);
            $entreprise->update($data);
            session()->flash('message', 'Entreprise mise à jour.');
        } else {
            // Création
            Entreprise::create($data);
            session()->flash('message', 'Entreprise créée.');
        }

        $this->resetForm();
        $this->dispatch('entreprise-saved');
    }

    public function delete($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        // Supprimer le logo associé
        if ($entreprise->logo && Storage::disk('public')->exists($entreprise->logo)) {
            Storage::disk('public')->delete($entreprise->logo);
        }
        $entreprise->delete();
        session()->flash('message', 'Entreprise supprimée avec succès.');
        $this->dispatch('entreprise-deleted');
    }
}