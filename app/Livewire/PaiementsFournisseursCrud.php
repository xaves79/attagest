<?php

namespace App\Livewire;

use App\Models\PaiementFournisseur;
use App\Models\RecuFournisseur;
use Livewire\Component;
use Livewire\WithPagination;

class PaiementsFournisseursCrud extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    // Recherche et filtres
    public $search = '';
    public $perPage = 10;
    public $recu_id = null;

    // Formulaire création
    public $date_paiement;
    public $montant;
    public $mode_paiement = 'espece';
    public $reference;
    public $notes;
    public $showForm = false;

    protected $rules = [
        'date_paiement' => 'required|date',
        'montant' => 'required|numeric|min:0.01',
        'mode_paiement' => 'required|in:espece,cheque,mobile_money,virement',
        'reference' => 'nullable|string|max:100|unique:paiements_fournisseurs,reference',
    ];

    public function mount()
    {
        $this->date_paiement = now()->format('Y-m-d');
        $this->recu_id = request()->query('recu_id');
    }

    public function render()
    {
        $query = PaiementFournisseur::with(['recu.fournisseur'])
            ->orderBy('date_paiement', 'desc');

        if ($this->recu_id) {
            $query->where('recu_fournisseur_id', $this->recu_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('recu.fournisseur', fn($q) => $q->where('nom', 'like', "%{$this->search}%"))
                  ->orWhere('reference', 'like', "%{$this->search}%")
                  ->orWhere('mode_paiement', 'like', "%{$this->search}%");
            });
        }

        $paiements = $query->paginate($this->perPage);
        $recu = $this->recu_id ? RecuFournisseur::with('fournisseur')->find($this->recu_id) : null;

        return view('livewire.paiements-fournisseurs-crud', compact('paiements', 'recu'));
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->recu_id === null) {
            session()->flash('error', '❌ Aucun reçu sélectionné !');
            return;
        }

        PaiementFournisseur::create([
            'recu_fournisseur_id' => $this->recu_id,
            'date_paiement' => $this->date_paiement,
            'montant' => $this->montant,
            'mode_paiement' => $this->mode_paiement,
            'reference' => $this->reference,
            'notes' => $this->notes,
        ]);

        session()->flash('message', '✅ Paiement enregistré avec succès !');
        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
    }

    public function delete($id)
    {
        $paiement = PaiementFournisseur::findOrFail($id);
        $paiement->delete();
        
        session()->flash('message', '🗑️ Paiement supprimé avec succès');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->date_paiement = now()->format('Y-m-d');
        $this->montant = '';
        $this->mode_paiement = 'espece';
        $this->reference = '';
        $this->notes = '';
        $this->resetErrorBag();
    }
}
