<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class GestionPrix extends Component
{
    use WithPagination;

    // Filtres
    public string $searchType = '';
    public string $searchUnite = '';

    // Formulaire
    public $prix_id = null;
    public $type_produit = '';
    public $unite = 'sac';
    public $poids_sac_kg = null;
    public $prix_unitaire_fcfa = '';
    public $actif = true;
    public $date_application = '';

    // États de l'UI
    public bool $showForm = false;
    public string $formMode = 'create'; // create / edit

    // Règles de validation
    protected function rules()
    {
        return [
            'type_produit' => 'required|in:riz_blanc,son,brisures',
            'unite' => 'required|in:sac,kg',
            'poids_sac_kg' => 'nullable|numeric|min:0|required_if:unite,sac',
            'prix_unitaire_fcfa' => 'required|integer|min:0',
            'actif' => 'boolean',
            'date_application' => 'required|date',
        ];
    }

    // Messages personnalisés
    protected function messages()
    {
        return [
            'type_produit.required' => 'Le type de produit est obligatoire.',
            'type_produit.in' => 'Type invalide.',
            'unite.required' => 'L\'unité est obligatoire.',
            'unite.in' => 'Unité invalide.',
            'poids_sac_kg.required_if' => 'Le poids du sac est obligatoire pour l\'unité "sac".',
            'poids_sac_kg.numeric' => 'Le poids doit être un nombre.',
            'poids_sac_kg.min' => 'Le poids doit être positif.',
            'prix_unitaire_fcfa.required' => 'Le prix unitaire est obligatoire.',
            'prix_unitaire_fcfa.integer' => 'Le prix doit être un nombre entier.',
            'prix_unitaire_fcfa.min' => 'Le prix doit être positif.',
            'date_application.required' => 'La date d\'application est obligatoire.',
            'date_application.date' => 'Format de date invalide.',
        ];
    }

    public function mount()
    {
        $this->date_application = now()->format('Y-m-d');
    }

    public function render()
    {
        $prix = DB::table('parametres_prix')
            ->when($this->searchType, function ($query) {
                $query->where('type_produit', $this->searchType);
            })
            ->when($this->searchUnite, function ($query) {
                $query->where('unite', $this->searchUnite);
            })
            ->orderBy('type_produit')
            ->orderBy('date_application', 'desc')
            ->orderBy('poids_sac_kg')
            ->paginate(10);

        return view('livewire.gestion-prix', [
            'prix' => $prix,
        ]);
    }

    // Afficher le formulaire pour créer
    public function showCreateForm()
    {
        $this->resetForm();
        $this->formMode = 'create';
        $this->showForm = true;
    }

    // Afficher le formulaire pour éditer
    public function edit($id)
    {
        $this->resetForm();
        $this->formMode = 'edit';
        $this->prix_id = $id;

        $record = DB::table('parametres_prix')->where('id', $id)->first();
        if ($record) {
            $this->type_produit = $record->type_produit;
            $this->unite = $record->unite;
            $this->poids_sac_kg = $record->poids_sac_kg;
            $this->prix_unitaire_fcfa = (string) $record->prix_unitaire_fcfa;
            $this->actif = (bool) $record->actif;
            $this->date_application = $record->date_application;
        }
        $this->showForm = true;
    }

    // Enregistrer (création ou mise à jour)
    public function save()
    {
        $this->validate();

        $data = [
            'type_produit' => $this->type_produit,
            'unite' => $this->unite,
            'poids_sac_kg' => $this->unite === 'sac' ? $this->poids_sac_kg : null,
            'prix_unitaire_fcfa' => (int) $this->prix_unitaire_fcfa,
            'actif' => $this->actif,
            'date_application' => $this->date_application,
            'updated_at' => now(),
        ];

        if ($this->formMode === 'create') {
            $data['created_at'] = now();
            DB::table('parametres_prix')->insert($data);
            session()->flash('message', 'Prix ajouté avec succès.');
        } else {
            DB::table('parametres_prix')->where('id', $this->prix_id)->update($data);
            session()->flash('message', 'Prix modifié avec succès.');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('prix-updated'); // pour rafraîchir la liste si nécessaire
    }

    // Supprimer
    public function delete($id)
    {
        DB::table('parametres_prix')->where('id', $id)->delete();
        session()->flash('message', 'Prix supprimé.');
    }

    // Réinitialiser le formulaire
    private function resetForm()
    {
        $this->prix_id = null;
        $this->type_produit = '';
        $this->unite = 'sac';
        $this->poids_sac_kg = null;
        $this->prix_unitaire_fcfa = '';
        $this->actif = true;
        $this->date_application = now()->format('Y-m-d');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Annuler le formulaire
    public function cancelForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }
}