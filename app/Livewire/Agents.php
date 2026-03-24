<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Agent;
use App\Models\Entreprise;
use App\Models\Poste;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
class Agents extends Component
{
    use WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'            => null,
        'nom'           => '',
        'prenom'        => '',
        'matricule'     => '',
        'whatsapp'      => '',
        'telephone'     => '',
        'email'         => '',
        'photo'         => '', // chemin stocké en base
        'entreprise_id' => '',
        'date_embauche' => '',
        'actif'         => true,
        'poste_id'      => '',
        'nom_complet'   => '',
    ];

    public $photo; // pour l'upload temporaire

    protected function rules()
    {
        $rules = [
            'form.nom'           => 'required|string|max:50',
            'form.prenom'        => 'required|string|max:50',
            'form.whatsapp'      => 'nullable|string|max:20',
            'form.telephone'     => 'nullable|string|max:20',
            'form.email'         => 'nullable|email|max:100',
            'form.entreprise_id' => 'nullable|exists:entreprises,id',
            'form.date_embauche' => 'nullable|date',
            'form.actif'         => 'nullable|boolean',
            'form.poste_id'      => 'nullable|exists:postes,id',
            'photo'              => 'nullable|image|max:1024', // max 1MB
        ];

        // Règle unique pour le matricule : ignore l'agent en cours en modification
        if ($this->form['id']) {
            $rules['form.matricule'] = 'required|string|max:10|unique:agents,matricule,' . $this->form['id'];
        } else {
            $rules['form.matricule'] = 'required|string|max:10|unique:agents,matricule';
        }

        return $rules;
    }

    protected $validationAttributes = [
        'form.nom'           => 'nom',
        'form.prenom'        => 'prénom',
        'form.matricule'     => 'matricule',
        'form.whatsapp'      => 'WhatsApp',
        'form.telephone'     => 'téléphone',
        'form.email'         => 'email',
        'form.entreprise_id' => 'entreprise',
        'form.date_embauche' => 'date d\'embauche',
        'form.poste_id'      => 'poste',
        'photo'              => 'photo',
    ];

    public function render()
    {
        $agents = Agent::with(['entreprise', 'poste'])
			->where('actif', true) // <-- ajoutez ce filtre
			->where(function ($q) {
				$q->where('nom', 'like', "%{$this->search}%")
				  ->orWhere('prenom', 'like', "%{$this->search}%")
				  ->orWhere('matricule', 'like', "%{$this->search}%");
			})
			->latest()
			->paginate(10);

        $entreprises = Entreprise::all();
        $postes      = Poste::all();

        return view('livewire.agents.index', compact('agents', 'entreprises', 'postes'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $agent = Agent::findOrFail($id);
        $this->fillForm($agent);
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
	{
        $agent = Agent::findOrFail($id);
        $this->fillForm($agent);
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function delete($id)
	{
		$agent = Agent::findOrFail($id);
		
		// Désactiver l'agent (passe actif à false) au lieu de supprimer
		$agent->update(['actif' => false]);

		// Optionnel : supprimer la photo associée si vous le souhaitez
		if ($agent->photo && Storage::disk('public')->exists($agent->photo)) {
			Storage::disk('public')->delete($agent->photo);
		}

		session()->flash('message', 'Agent désactivé avec succès.');
		$this->dispatch('notify', message: 'Agent désactivé.');
	}

    public function save()
	{
		// Convertir les chaînes vides en null pour les champs de clés étrangères
		$foreignKeys = ['entreprise_id', 'poste_id'];
		foreach ($foreignKeys as $key) {
			if (isset($this->form[$key]) && $this->form[$key] === '') {
				$this->form[$key] = null;
			}
		}
		$this->validate();
		// Gestion de la photo uploadée
		if ($this->photo) {
			// Supprimer l'ancienne photo si elle existe
			if (!empty($this->form['id']) && !empty($this->form['photo'])) {
				Storage::disk('public')->delete($this->form['photo']);
			}
			// Générer un nom basé sur le matricule
			$extension = $this->photo->getClientOriginalExtension();
			$nom = $this->form['matricule'] . '.' . $extension;
			$path = $this->photo->storeAs('agents', $nom, 'public');
			$this->form['photo'] = $path;
		} else {
			// Si aucune nouvelle photo, on garde l'ancienne (sauf en création)
			if (empty($this->form['id'])) {
				$this->form['photo'] = null;
			}
		}

		if ($this->form['id']) {
			$agent = Agent::findOrFail($this->form['id']);
			$agent->update($this->form);
			session()->flash('message', 'Agent mis à jour avec succès.');
		} else {
			Agent::create($this->form);
			session()->flash('message', 'Agent créé avec succès.');
		}

		$this->showModal = false;
		$this->resetForm();
	}

    private function resetForm()
    {
        $this->form = [
            'id'            => null,
            'nom'           => '',
            'prenom'        => '',
            'matricule'     => '',
            'whatsapp'      => '',
            'telephone'     => '',
            'email'         => '',
            'photo'         => '',
            'entreprise_id' => '',
            'date_embauche' => '',
            'actif'         => true,
            'poste_id'      => '',
            'nom_complet'   => '',
        ];
        $this->photo = null;
    }

    private function fillForm(Agent $agent)
    {
        $this->form = $agent->only([
            'id',
            'nom',
            'prenom',
            'matricule',
            'whatsapp',
            'telephone',
            'email',
            'photo',
            'entreprise_id',
            'date_embauche',
            'actif',
            'poste_id',
            'nom_complet',
        ]);
        $this->photo = null; // on réinitialise l'upload temporaire
    }
}