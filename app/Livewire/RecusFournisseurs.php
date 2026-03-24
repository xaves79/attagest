<?php

namespace App\Livewire;

use App\Models\RecuFournisseur;
use App\Models\Fournisseur;
use App\Models\VarieteRice;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class RecusFournisseurs extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Propriétés du reçu
    public $id;
    public $numero_recu;
    public $fournisseur_id;
    public $date_recu;
    public $montant_total = 0;
    public $paye = false;
    public $date_limite_paiement;
    public $jours_credit = 60;
    public $mode_paiement = 'espece';
    public $acompte = 0;
    public $solde_du = 0;
    public $reference_entreprise;
    public $variete_rice_id;
    public $entreprise_id = 1;

    // Lignes
    public $lignes = [];
    public $editing_ligne = null;

    public $showForm = false;
    public $showDetailsModal = false;
    public $currentRecu;

    public $showDeleteModal = false;

    protected $rules = [
        'numero_recu'             => 'required|string|max:20|unique:recus_fournisseurs,numero_recu',
        'fournisseur_id'          => 'required|exists:fournisseurs,id',
        'date_recu'               => 'required|date',
        'montant_total'           => 'required|numeric|min:0',
        'paye'                    => 'boolean',
        'date_limite_paiement'    => 'nullable|date',
        'jours_credit'            => 'nullable|integer|min:0',
        'mode_paiement'           => 'nullable|in:espece,cheque,mobile_money,credit,virement',
        'acompte'                 => 'nullable|numeric|min:0',
        'solde_du'                => 'nullable|numeric|min:0',
        'reference_entreprise'    => 'nullable|string',
        'variete_rice_id'         => 'nullable|exists:varietes_rice,id',
        'entreprise_id'           => 'nullable|exists:entreprises,id',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = RecuFournisseur::with(['fournisseur', 'variete'])
            ->where('entreprise_id', $this->entreprise_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('numero_recu', 'like', "%{$this->search}%")
                  ->orWhereHas('fournisseur', fn($q) => $q->where('nom', 'like', "%{$this->search}%"));
            });
        }

        $recus = $query->latest()->paginate($this->perPage);

        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $varietes     = VarieteRice::orderBy('nom')->get();

        return view('livewire.recus-fournisseurs.index', compact('recus', 'fournisseurs', 'varietes'));
    }

    public function resetForm()
    {
        $this->reset([
            'id',
            'numero_recu',
            'fournisseur_id',
            'date_recu',
            'montant_total',
            'paye',
            'date_limite_paiement',
            'jours_credit',
            'mode_paiement',
            'acompte',
            'solde_du',
            'reference_entreprise',
            'variete_rice_id',
            'entreprise_id',
            'lignes',
            'editing_ligne',
        ]);

        $this->paye = false;
        $this->jours_credit = 60;
        $this->mode_paiement = 'espece';
        $this->acompte = 0;
        $this->solde_du = 0;
        $this->entreprise_id = 1;

        $this->date_recu = now()->format('Y-m-d');
    }

    public function generateNumeroRecu()
    {
        $prefix = 'REC-' . now()->format('Y');
        $last   = RecuFournisseur::where('numero_recu', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $last ? (int) substr($last->numero_recu, -4) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $this->resetForm();
        $this->numero_recu = $this->generateNumeroRecu();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $recu = RecuFournisseur::with(['lignes'])->findOrFail($id);

        $this->id                   = $recu->id;
        $this->numero_recu          = $recu->numero_recu;
        $this->fournisseur_id       = $recu->fournisseur_id;
        $this->date_recu            = $recu->date_recu->format('Y-m-d');
        $this->montant_total        = $recu->montant_total;
        $this->paye                 = $recu->paye;
        $this->date_limite_paiement = $recu->date_limite_paiement?->format('Y-m-d');
        $this->jours_credit         = $recu->jours_credit;
        $this->mode_paiement        = $recu->mode_paiement;
        $this->acompte              = $recu->acompte;
        $this->solde_du             = $recu->solde_du;
        $this->reference_entreprise = $recu->reference_entreprise;
        $this->variete_rice_id      = $recu->variete_rice_id;
        $this->entreprise_id        = $recu->entreprise_id;

        $this->lignes = $recu->lignes->map(fn($l) => [
            'id'                => $l->id,
            'variete_rice_id'   => $l->variete_rice_id,
            'quantite_kg'       => $l->quantite_kg,
            'prix_unitaire'     => $l->prix_unitaire,
            'sous_total'        => $l->sous_total,
        ])->toArray();

        $this->showForm = true;
    }

    public function addLigne()
    {
        $this->lignes[] = [
            'id'                => null,
            'variete_rice_id'   => null,
            'quantite_kg'       => 0,
            'prix_unitaire'     => 0,
            'sous_total'        => 0,
        ];
    }

    public function removeLigne($index)
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
    }

    public function save()
	{
		// Génère le numéro uniquement si c’est une création
		if (!$this->id) {
			$this->numero_recu = $this->generateNumeroRecu();
		}

		// Calcule le montant total à partir des lignes
		$montant_total = 0;
		foreach ($this->lignes as $ligne) {
			$montant_total += ($ligne['quantite_kg'] ?? 0) * ($ligne['prix_unitaire'] ?? 0);
		}

		// Déduit le solde dû à partir de l’acompte
		$acompte = max(0, $this->acompte);
		$solde_du = max(0, $montant_total - $acompte);

		// Détermine si c’est payé
		$paye = $acompte >= $montant_total;

		$data = [
			'numero_recu'             => $this->numero_recu,
			'fournisseur_id'          => $this->fournisseur_id,
			'date_recu'               => $this->date_recu,
			'montant_total'           => $montant_total,
			'paye'                    => $paye,
			'date_limite_paiement'    => $this->date_limite_paiement,
			'jours_credit'            => $this->jours_credit,
			'mode_paiement'           => $this->mode_paiement,
			'acompte'                 => $acompte,
			'solde_du'                => $solde_du,
			'reference_entreprise'    => $this->reference_entreprise,
			'variete_rice_id'         => $this->variete_rice_id,
			'entreprise_id'           => $this->entreprise_id,
		];

		if ($this->id) {
			$recu = RecuFournisseur::findOrFail($this->id);
			$recu->update($data);
		} else {
			$recu = RecuFournisseur::create($data);
		}

		$recu->lignes()->delete();
		foreach ($this->lignes as $ligne) {
			if ($ligne['variete_rice_id']) {
				$recu->lignes()->create([
					'variete_rice_id'   => $ligne['variete_rice_id'],
					'quantite_kg'       => $ligne['quantite_kg'] ?? 0,
					'prix_unitaire'     => $ligne['prix_unitaire'] ?? 0,
					'sous_total'        => ($ligne['quantite_kg'] ?? 0) * ($ligne['prix_unitaire'] ?? 0),
				]);
			}
		}

		$this->resetForm();
		$this->showForm = false;
		session()->flash('message', 'Reçu fournisseur enregistré avec succès.');
	}

    public function show($id)
    {
        $this->currentRecu = RecuFournisseur::with(['fournisseur', 'lignes.variete'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function confirmDelete($id)
    {
        $this->id = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $recu = RecuFournisseur::findOrFail($this->id);
        $recu->delete();

        $this->showDeleteModal = false;
        session()->flash('message', 'Reçu fournisseur supprimé.');
    }
}
