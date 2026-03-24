<?php

namespace App\Livewire;

use App\Models\LigneRecuFournisseur;
use App\Models\RecuFournisseur;
use App\Models\VarieteRice;
use Livewire\Component;
use Livewire\WithPagination;

class LignesRecuFournisseur extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'                    => null,
        'recu_fournisseur_id'   => '',
        'variete_rice_id'       => '',
        'quantite_kg'           => '',
        'prix_unitaire'         => '',
        'sous_total'            => '',
    ];

    public $recus = [];
    public $varietes = [];

    public function mount()
    {
        $this->recus = RecuFournisseur::orderBy('numero_recu')->get();
        $this->varietes = VarieteRice::orderBy('nom')->get();
        $this->resetForm();
    }

    public function render()
	{
		// Définir un timeout plus long pour le débogage (à retirer après)
		set_time_limit(120);

		// Utiliser simplePaginate pour éviter le COUNT(*) coûteux sur de grandes tables
		$query = LigneRecuFournisseur::with(['recu', 'variete']);

		if ($this->search) {
			$query->where(function ($q) {
				$q->whereHas('recu', fn($q) => $q->where('numero_recu', 'ilike', "%{$this->search}%"))
				  ->orWhereHas('variete', fn($q) => $q->where('nom', 'ilike', "%{$this->search}%"));
			});
		}

		// simplePaginate évite le count(*), plus rapide pour les grandes tables
		$lignes = $query->paginate(10);

		return view('livewire.lignes-recu-fournisseur.index', [
			'lignes'   => $lignes,
			'recus'    => $this->recus,
			'varietes' => $this->varietes,
		]);
	}

    public function resetForm()
    {
        $this->form = [
            'id'                    => null,
            'recu_fournisseur_id'   => '',
            'variete_rice_id'       => '',
            'quantite_kg'           => '',
            'prix_unitaire'         => '',
            'sous_total'            => '',
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
        $ligne = LigneRecuFournisseur::findOrFail($id);
        $this->form = $ligne->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
    {
        $ligne = LigneRecuFournisseur::findOrFail($id);
        $this->form = $ligne->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function updatedFormQuantiteKg()
    {
        $this->recalculSousTotal();
    }

    public function updatedFormPrixUnitaire()
    {
        $this->recalculSousTotal();
    }

    protected function recalculSousTotal()
    {
        $q = (float) ($this->form['quantite_kg'] ?? 0);
        $p = (float) ($this->form['prix_unitaire'] ?? 0);
        $this->form['sous_total'] = $q * $p;
    }

    public function save()
    {
        if ($this->viewMode) return;

        $this->recalculSousTotal();

        $this->validate([
            'form.recu_fournisseur_id' => 'required|exists:recus_fournisseurs,id',
            'form.variete_rice_id'     => 'required|exists:varietes_rice,id',
            'form.quantite_kg'         => 'required|numeric|min:0',
            'form.prix_unitaire'       => 'required|numeric|min:0',
            'form.sous_total'          => 'required|numeric|min:0',
        ]);

        LigneRecuFournisseur::updateOrCreate(
            ['id' => $this->form['id']],
            [
                'recu_fournisseur_id' => $this->form['recu_fournisseur_id'],
                'variete_rice_id'     => $this->form['variete_rice_id'],
                'quantite_kg'         => $this->form['quantite_kg'],
                'prix_unitaire'       => $this->form['prix_unitaire'],
                'sous_total'          => $this->form['sous_total'],
            ]
        );

        $this->resetForm();
        session()->flash('message', 'Ligne enregistrée avec succès.');
    }

    public function delete($id)
    {
        LigneRecuFournisseur::findOrFail($id)->delete();
        session()->flash('message', 'Ligne supprimée avec succès.');
    }
}