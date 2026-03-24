<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\LigneFacture;

#[Layout('components.layouts.app')]
class LignesFacture extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $form = [
        'id' => null,
        'facture_id' => '',
        'article_id' => '',
        'quantite' => 1,
        'prix_unitaire' => '',
        'montant' => '',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $lignes = LigneFacture::with([
                'facture',
                'article',
            ])
            ->where(function ($query) {
                $query->whereHas('facture', fn ($q) => $q->where('numero_facture', 'like', "%{$this->search}%"))
                    ->orWhereHas('article', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"));
            })
            ->latest()
            ->paginate(10);

        $factures = \App\Models\FactureClient::all();
        $articles = \App\Models\Article::all();

        return view('livewire.lignes-facture.index', compact(
            'lignes',
            'factures',
            'articles'
        ));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $ligne = LigneFacture::findOrFail($id);
        $this->form = $ligne->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $ligne = LigneFacture::findOrFail($id);
        $this->form = $ligne->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        LigneFacture::findOrFail($id)->delete();
        session()->flash('message', 'Ligne de facture supprimée avec succès.');
        $this->resetPage();
    }

    public function updatedFormQuantite()
    {
        if ($this->form['quantite'] && $this->form['prix_unitaire']) {
            $this->form['montant'] = $this->form['quantite'] * $this->form['prix_unitaire'];
        }
    }

    public function updatedFormPrixUnitaire()
    {
        if ($this->form['quantite'] && $this->form['prix_unitaire']) {
            $this->form['montant'] = $this->form['quantite'] * $this->form['prix_unitaire'];
        }
    }

    public function save()
    {
        $this->validate([
            'form.facture_id' => 'required|exists:factures_clients,id',
            'form.article_id' => 'required|exists:articles,id',
            'form.quantite' => 'required|integer|min:1',
            'form.prix_unitaire' => 'required|integer|min:1',
            'form.montant' => 'required|integer|min:1',
        ]);

        LigneFacture::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        session()->flash('message', $this->form['id'] ? 'Ligne de facture mise à jour.' : 'Ligne de facture créée.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'facture_id' => '',
            'article_id' => '',
            'quantite' => 1,
            'prix_unitaire' => '',
            'montant' => '',
        ];
    }
}
