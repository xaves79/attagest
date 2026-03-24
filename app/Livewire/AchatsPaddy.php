<?php

namespace App\Livewire;

use App\Models\AchatPaddy;
use App\Models\Agent;
use App\Models\Fournisseur;
use App\Models\VarieteRice;
use App\Models\Localite;
use App\Models\Entreprise;
use App\Models\StockPaddy;     // ← Table stocks_paddy (singulier)
use App\Models\RecuFournisseur;
use Livewire\Component;
use Livewire\WithPagination;

class AchatsPaddy extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id' => null, 'agent_id' => '', 'fournisseur_id' => '', 'variete_id' => '',
        'localite_id' => '', 'entreprise_id' => '', 'date_achat' => '',
        'quantite_achat_kg' => '', 'prix_achat_unitaire_fcfa' => '',
        'montant_achat_total_fcfa' => '', 'statut' => 'stock_paddy', 'code_lot' => ''
    ];

    public $showModal = false;
    public $viewMode = false;
    public $fournisseurs = [], $varietes = [], $agents = [], $localites = [], $entreprises = [];

    public function mount()
    {
        $this->fournisseurs = Fournisseur::orderBy('nom')->get();
        $this->varietes = VarieteRice::orderBy('nom')->get();
        $this->agents = Agent::where('actif', true)->orderBy('nom')->get();
        $this->localites = Localite::orderBy('nom')->get();
        $this->entreprises = Entreprise::orderBy('nom')->get();
        $this->resetForm();
    }

    public function render()
    {
        $query = AchatPaddy::with(['fournisseur', 'agent']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('code_lot', 'like', "%{$this->search}%")
                  ->orWhereHas('fournisseur', fn($q) => $q->where('nom', 'like', "%{$this->search}%"));
            });
        }

        $achats = $query->orderBy('created_at', 'desc')->paginate(10);

        // ✅ CORRIGÉ : Vérifications sécurisées
        $achats->getCollection()->transform(function ($achat) {
            $achat->has_stock = \App\Models\StockPaddy::where('lot_paddy_id', $achat->id)->exists();
            $achat->has_recu = \App\Models\RecuFournisseur::where('achat_paddy_id', $achat->id)->exists();
            return $achat;
        });

        return view('livewire.achats-paddy.index', [
            'achats' => $achats,
            'fournisseurs' => $this->fournisseurs,
            'varietes' => $this->varietes,
            'agents' => $this->agents,
            'localites' => $this->localites,
            'entreprises' => $this->entreprises,
        ])->layout('layouts.app');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function generateCodeLot()
    {
        $last = AchatPaddy::latest('id')->first();
        $currentYYMM = now()->format('ym');
        if ($last && preg_match('/L-(\d{4})-(\d+)/', $last->code_lot, $m)) {
            $newNum = ($m[1] === $currentYYMM) ? ((int)$m[2] + 1) : 1;
        } else {
            $newNum = 1;
        }
        return sprintf('L-%s-%04d', $currentYYMM, $newNum);
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null, 'agent_id' => '', 'fournisseur_id' => '', 'variete_id' => '',
            'localite_id' => '', 'entreprise_id' => '', 'date_achat' => now()->format('Y-m-d'),
            'quantite_achat_kg' => '', 'prix_achat_unitaire_fcfa' => '',
            'montant_achat_total_fcfa' => '', 'statut' => 'stock_paddy', 'code_lot' => ''
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function create() { $this->resetForm(); $this->form['code_lot'] = $this->generateCodeLot(); $this->showModal = true; }

    public function show($id)
    {
        $achat = AchatPaddy::with(['fournisseur', 'variete', 'agent'])->findOrFail($id);
        $this->form = $achat->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
    {
        $achat = AchatPaddy::with(['fournisseur', 'variete', 'agent'])->findOrFail($id);
        $this->form = $achat->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    // ✅ BOUTON CRÉER RÉCU
    public function createRecuFromAchat($achatId)
    {
        $achat = AchatPaddy::findOrFail($achatId);
        session(['achat_paddy_for_recu' => [
            'id' => $achat->id, 'code_lot' => $achat->code_lot,
            'fournisseur_id' => $achat->fournisseur_id, 'fournisseur_nom' => $achat->fournisseur?->nom,
            'variete_id' => $achat->variete_id, 'quantite_kg' => $achat->quantite_achat_kg,
            'prix_unitaire' => $achat->prix_achat_unitaire_fcfa, 'montant_total' => $achat->montant_achat_total_fcfa
        ]]);
        return redirect()->route('recus-fournisseurs.index')->with('openFromAchat', $achat->id);
    }

    public function sendToStock($achatId)
    {
        $achat = AchatPaddy::findOrFail($achatId);
        $codeStock = 'STK-' . $achat->code_lot;

        \App\Models\StockPaddy::updateOrCreate(  // ✅ Table stocks_paddy (singulier)
            ['lot_paddy_id' => $achat->id],
            [
                'code_stock' => $codeStock,
                'agent_id' => $achat->agent_id,
                'quantite_stock_kg' => $achat->quantite_achat_kg,
                'quantite_restante_kg' => $achat->quantite_achat_kg,
                'emplacement' => 'Entrepôt Central',
            ]
        );

        $achat->update(['statut' => 'stock_paddy']);
        session()->flash('message', '✅ Stock créé : ' . $codeStock);
        $this->dispatch('$refresh');
    }

    public function updatedFormQuantiteAchatKg() { $this->recalculateMontant(); }
    public function updatedFormPrixAchatUnitaireFcfa() { $this->recalculateMontant(); }

    protected function recalculateMontant()
    {
        $q = (float)($this->form['quantite_achat_kg'] ?? 0);
        $p = (float)($this->form['prix_achat_unitaire_fcfa'] ?? 0);
        $this->form['montant_achat_total_fcfa'] = $q * $p;
    }

    public function save()
    {
        if ($this->viewMode) return;

        $this->validate([
            'form.fournisseur_id' => 'required|exists:fournisseurs,id',
            'form.variete_id' => 'required|exists:varietes_rice,id',
            'form.date_achat' => 'required|date',
            'form.quantite_achat_kg' => 'required|numeric|min:0.1',
            'form.prix_achat_unitaire_fcfa' => 'required|numeric|min:0',
            'form.code_lot' => 'required|unique:lots_paddy,code_lot,' . ($this->form['id'] ?? 'NULL'),
        ]);

        AchatPaddy::updateOrCreate(['id' => $this->form['id']], $this->form);
        session()->flash('message', $this->form['id'] ? '✅ Achat mis à jour' : '✅ Achat créé');
        $this->resetForm();
        $this->dispatch('$refresh');
    }

    public function delete($id)
    {
        $achat = AchatPaddy::findOrFail($id);
        if (\App\Models\StockPaddy::where('lot_paddy_id', $id)->exists()) {
            session()->flash('error', '❌ Stock existant');
            return;
        }
        $achat->delete();
        session()->flash('message', '✅ Achat supprimé');
        $this->dispatch('$refresh');
    }
}
