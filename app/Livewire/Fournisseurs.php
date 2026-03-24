<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Fournisseur;
use App\Models\Localite;

class Fournisseurs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
	
	protected $rulesCache = [];

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'                => null,
        'type_personne'     => 'PHYSIQUE',
        'nom'               => '',
        'prenom'            => '',
        'raison_sociale'    => '',
        'sigle'             => '',
        'code_fournisseur'  => '',
        'whatsapp'          => '',
        'telephone'         => '',
        'localite_id'       => '',
        'type_fournisseur'  => 'Producteur',
        'email'             => '',
    ];

    protected function rules()
	{
		$id = $this->form['id'] ?? null;
		
		return [
			'form.type_personne'     => 'required|in:PHYSIQUE,MORALE',
			'form.nom'               => ['nullable', 'required_if:form.type_personne,PHYSIQUE', 'string', 'max:100'],
			'form.prenom'            => ['nullable', 'required_if:form.type_personne,PHYSIQUE', 'string', 'max:50'],
			'form.raison_sociale'    => ['nullable', 'required_if:form.type_personne,MORALE', 'string', 'max:150'],
			'form.sigle'             => ['nullable', 'string', 'max:10', "unique:fournisseurs,sigle,{$id}"],
			'form.code_fournisseur'  => ['required', 'string', 'max:20', "unique:fournisseurs,code_fournisseur,{$id}"],
			'form.whatsapp'          => 'nullable|string|max:20',
			'form.telephone'         => 'nullable|string|max:20',
			'form.localite_id'       => 'nullable|exists:localites,id',
			'form.type_fournisseur'  => 'nullable|string|max:30',
			'form.email'             => 'nullable|email|max:100',
		];
	}

    protected $validationAttributes = [
        'form.type_personne'     => 'type de personne',
        'form.nom'               => 'nom',
        'form.prenom'            => 'prénom',
        'form.raison_sociale'    => 'raison sociale',
        'form.sigle'             => 'sigle',
        'form.code_fournisseur'  => 'code fournisseur',
        'form.whatsapp'          => 'WhatsApp',
        'form.telephone'         => 'téléphone',
        'form.localite_id'       => 'localité',
        'form.type_fournisseur'  => 'type de fournisseur',
        'form.email'             => 'email',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $fournisseurs = Fournisseur::with('localite')
            ->where(function ($q) {
                $q->where('nom', 'like', "%{$this->search}%")
                  ->orWhere('prenom', 'like', "%{$this->search}%")
                  ->orWhere('code_fournisseur', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('nom')
            ->paginate(10);

        $localites = Localite::orderBy('nom')->get();

        return view('livewire.fournisseurs.index', compact('fournisseurs', 'localites'));
    }

    public function resetForm()
    {
        $this->form = [
            'id'                => null,
            'type_personne'     => 'PHYSIQUE',
            'nom'               => '',
            'prenom'            => '',
            'raison_sociale'    => '',
            'sigle'             => '',
            'code_fournisseur'  => '',
            'whatsapp'          => '',
            'telephone'         => '',
            'localite_id'       => '',
            'type_fournisseur'  => 'Producteur',
            'email'             => '',
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function create()
	{
		$this->resetForm();
		$code = $this->generateCodeFournisseur();
		('Nouveau code généré : ' . $code); // Voir ce qui est généré
		$this->form['code_fournisseur'] = $code;
		$this->showModal = true;
		$this->viewMode = false;
	}

    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $this->form = $fournisseur->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
	{
		$fournisseur = Fournisseur::findOrFail($id);
		$this->form = $fournisseur->toArray();
		\Log::info('Edit form loaded:', $this->form); // Debug
		$this->showModal = true;
		$this->viewMode = false;
	}

    public function delete($id)
    {
        Fournisseur::findOrFail($id)->delete();
        session()->flash('message', 'Fournisseur supprimé avec succès.');
    }

    public function save()
	{
		try {
			// 1. Log avant validation
			\Log::info('=== SAVE START ===', ['form' => $this->form]);
			
			// 2. Validation explicite avec message d'erreur
			$validated = $this->validate();
			\Log::info('VALIDATION OK', ['validated' => $validated]);
			
			// 3. Préparer les données
			$data = [
				'type_personne'     => $this->form['type_personne'],
				'nom'               => $this->form['type_personne'] === 'PHYSIQUE' ? $this->form['nom'] : null,
				'prenom'            => $this->form['type_personne'] === 'PHYSIQUE' ? $this->form['prenom'] : null,
				'raison_sociale'    => $this->form['type_personne'] === 'MORALE' ? $this->form['raison_sociale'] : null,
				'sigle'             => ($this->form['type_personne'] === 'MORALE' && !empty($this->form['sigle'])) ? $this->form['sigle'] : null,
				'code_fournisseur'  => $this->form['code_fournisseur'],
				'telephone'         => $this->form['telephone'] ?: null,
				'whatsapp'          => $this->form['whatsapp'] ?: null,
				'email'             => $this->form['email'] ?: null,
				'localite_id'       => $this->form['localite_id'] ?: null,
				'type_fournisseur'  => $this->form['type_fournisseur'] ?: null,
			];
			
			\Log::info('DATA PREPAREE', $data);

			if ($this->form['id']) {
				$fournisseur = Fournisseur::findOrFail($this->form['id']);
				$result = $fournisseur->update($data);
				\Log::info('UPDATE RESULTAT', ['success' => $result, 'fournisseur' => $fournisseur->fresh()->toArray()]);
				session()->flash('message', '✅ Fournisseur mis à jour avec succès.');
			} else {
				$fournisseur = Fournisseur::create($data);
				\Log::info('CREATE RESULTAT', $fournisseur->toArray());
				session()->flash('message', '✅ Fournisseur créé avec succès.');
			}

		} catch (\Illuminate\Validation\ValidationException $e) {
			// 4. Capture les erreurs de validation
			\Log::error('VALIDATION ERROR', [
				'errors' => $e->errors(),
				'messages' => $e->getMessage()
			]);
			return; // Arrête sans crash
		} catch (Exception $e) {
			\Log::error('SAVE ERROR', ['error' => $e->getMessage()]);
			session()->flash('error', 'Erreur: ' . $e->getMessage());
			return;
		}

		$this->resetForm();
		$this->showModal = false;
	}
	
	public function updated($propertyName)
	{
		// Efface le cache des règles à CHAQUE modification
		$this->rulesCache = [];
	}

    protected function generateCodeFournisseur()
	{
		$prefix = 'FOU-' . now()->format('Y') . '-';
		$last = Fournisseur::where('code_fournisseur', 'like', $prefix . '%')
			->orderBy('code_fournisseur', 'desc')
			->first();
		
		if (!$last) {
			return $prefix . '0001';
		}
		
		// Extraire le numéro (les 4 derniers chiffres)
		preg_match('/-(\d{4})$/', $last->code_fournisseur, $matches);
		$num = (int) ($matches[1] ?? 0) + 1;
		
		return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
	}
	
    public function updatedFormTypePersonne($value)
    {
        if ($value === 'PHYSIQUE') {
            $this->form['raison_sociale'] = '';
            $this->form['sigle'] = '';
            $this->form['nom'] = ''; // Reset nom pour PHYSIQUE
        } else {
            $this->form['nom'] = '';
            $this->form['prenom'] = '';
            $this->form['raison_sociale'] = ''; // Reset raison_sociale pour MORALE
        }
    }
}