<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Clients extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id'            => null,
        'type_personne' => 'PHYSIQUE',  // Ajout
        'nom'           => '',
        'prenom'        => '',
        'raison_sociale'=> '',
        'sigle'         => '',
        'telephone'     => '',
        'email'         => '',
        'adresse'       => '',
        'ville'         => '',
        'code_client'   => '',
        'type_client'   => 'PARTICULIER', // GROSSISTE, PARTICULIER, RESTAURANT, HOTEL, MARCHE, DETAILLANT
        'localite_id'   => '',
        'whatsapp'      => '',
        'point_vente_id'=> '',
        'type_achat'    => 'Riz Blanc',
    ];

    public $showModal = false;
    public $viewMode = false;

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.clients.index', [
            'clients'    => $this->query()->paginate(10),
            'localites'  => \App\Models\Localite::orderBy('nom')->get(),
            'pointsVente'=> \App\Models\PointVente::orderBy('nom')->get(),
        ]);
    }

    public function query()
    {
        $query = Client::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('raison_sociale', 'like', "%{$this->search}%")
                  ->orWhere('telephone', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('code_client', 'like', "%{$this->search}%");
            });
        }

        return $query;
    }

    public function resetForm()
    {
        $this->form = [
            'id'            => null,
            'type_personne' => 'PHYSIQUE',
            'nom'           => '',
            'prenom'        => '',
            'raison_sociale'=> '',
            'sigle'         => '',
            'telephone'     => '',
            'email'         => '',
            'adresse'       => '',
            'ville'         => '',
            'code_client'   => '',
            'type_client'   => 'PARTICULIER',
            'localite_id'   => '',
            'whatsapp'      => '',
            'point_vente_id'=> '',
            'type_achat'    => 'Riz Blanc',
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function generateCodeClient()
    {
        $last = Client::orderBy('id', 'desc')->first();
        if ($last && preg_match('/CLI-(\d{4})-(\d+)/', $last->code_client, $matches)) {
            $year = $matches[1];
            $num  = (int) $matches[2];
            $newNum = ($year == now()->year) ? $num + 1 : 1;
        } else {
            $newNum = 1;
        }
        return sprintf('CLI-%d-%04d', now()->year, $newNum);
    }

    public function create()
    {
        $this->resetForm();
        $this->form['code_client'] = $this->generateCodeClient();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $this->form = $client->toArray();
        // Déduire le type_personne : si raison_sociale remplie -> MORALE, sinon PHYSIQUE
        $this->form['type_personne'] = !empty($client->raison_sociale) ? 'MORALE' : 'PHYSIQUE';
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        $this->form = $client->toArray();
        $this->form['type_personne'] = !empty($client->raison_sociale) ? 'MORALE' : 'PHYSIQUE';
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function updatedFormTypePersonne($value)
    {
        if ($value === 'PHYSIQUE') {
            $this->form['raison_sociale'] = '';
            $this->form['sigle'] = '';
        } else {
            $this->form['nom'] = '';
            $this->form['prenom'] = '';
        }
    }

    public function save()
	{
		// Nettoyer les champs selon le type
		if ($this->form['type_personne'] === 'PHYSIQUE') {
			$this->form['raison_sociale'] = null;
			$this->form['sigle'] = null;
		} else {
			$this->form['nom'] = null;
			$this->form['prenom'] = null;
		}

		// Convertir les chaînes vides en null pour les clés étrangères
		foreach (['localite_id', 'point_vente_id'] as $field) {
			if (isset($this->form[$field]) && $this->form[$field] === '') {
				$this->form[$field] = null;
			}
		}

		// Validation
		$rules = [
			'form.code_client' => 'required|string|max:20|unique:clients,code_client,' . ($this->form['id'] ?? 'NULL'),
			'form.type_client' => 'required|in:GROSSISTE,PARTICULIER,RESTAURANT,HOTEL,MARCHE,DETAILLANT',
			'form.type_achat'  => 'nullable|string|max:30',
			'form.telephone'   => 'nullable|string|max:20',
			'form.whatsapp'    => 'nullable|string|max:20',
			'form.email'       => 'nullable|email|max:100',
			'form.adresse'     => 'nullable|string',
			'form.localite_id' => 'nullable|exists:localites,id',
			'form.point_vente_id' => 'nullable|exists:points_vente,id',
		];

		if ($this->form['type_personne'] === 'PHYSIQUE') {
			$rules['form.nom'] = 'required|string|max:100';
			$rules['form.prenom'] = 'nullable|string|max:100';
		} else {
			$rules['form.raison_sociale'] = 'required|string|max:150';
			$rules['form.sigle'] = 'nullable|string|max:10|unique:clients,sigle,' . ($this->form['id'] ?? 'NULL');
		}

		$this->validate($rules);

		// Préparer les données à enregistrer
		$data = [
			'code_client'   => $this->form['code_client'],
			'type_client'   => $this->form['type_client'],
			'type_achat'    => $this->form['type_achat'],
			'telephone'     => $this->form['telephone'],
			'whatsapp'      => $this->form['whatsapp'],
			'email'         => $this->form['email'],
			'adresse'       => $this->form['adresse'],
			'localite_id'   => $this->form['localite_id'],
			'point_vente_id'=> $this->form['point_vente_id'],
		];

		if ($this->form['type_personne'] === 'PHYSIQUE') {
			$data['nom'] = $this->form['nom'];
			$data['prenom'] = $this->form['prenom'];
		} else {
			$data['raison_sociale'] = $this->form['raison_sociale'];
			$data['sigle'] = $this->form['sigle'];
			// Contournement de la contrainte NOT NULL sur 'nom'
			$data['nom'] = substr($this->form['raison_sociale'], 0, 100); // valeur forcée
		}

		if ($this->form['id']) {
			$client = Client::findOrFail($this->form['id']);
			$client->update($data);
			session()->flash('message', 'Client mis à jour.');
		} else {
			Client::create($data);
			session()->flash('message', 'Client créé.');
		}

		$this->resetForm();
		$this->dispatch('client-saved');
	}

    public function delete($id)
    {
        Client::findOrFail($id)->delete();
        session()->flash('message', 'Client supprimé.');
        $this->dispatch('client-deleted');
    }
}