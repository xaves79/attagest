<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListeDecorticages extends Component
{
    use WithPagination;

    public string $recherche     = '';
    public string $filtrePeriode = 'mois';

    public function updatingRecherche(): void { $this->resetPage(); }

    public function render()
    {
        $query = DB::table('decorticages as d')
            ->leftJoin('lots_riz_etuve as lre', 'lre.id', '=', 'd.lot_riz_etuve_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'd.variete_rice_id')
            ->leftJoin('agents as a', 'a.id', '=', 'd.agent_id')
            ->select(
                'd.id', 'd.code_decorticage', 'd.quantite_paddy_entree_kg',
                'd.quantite_riz_blanc_kg', 'd.quantite_son_kg', 'd.quantite_brise_kg',
                'd.taux_rendement', 'd.statut', 'd.date_debut_decorticage',
                'lre.code_lot as code_lot_etuve', 'v.nom as variete_nom',
                'a.nom as agent_nom', 'a.prenom as agent_prenom'
            )
            ->when($this->recherche, fn($q) =>
                $q->where('d.code_decorticage', 'ilike', "%{$this->recherche}%")
                  ->orWhere('lre.code_lot', 'ilike', "%{$this->recherche}%")
            )
            ->when($this->filtrePeriode === 'semaine', fn($q) =>
                $q->where('d.date_debut_decorticage', '>=', now()->subDays(7))
            )
            ->when($this->filtrePeriode === 'mois', fn($q) =>
                $q->whereRaw("EXTRACT(MONTH FROM d.date_debut_decorticage) = ?", [now()->month])
                  ->whereRaw("EXTRACT(YEAR FROM d.date_debut_decorticage) = ?", [now()->year])
            )
            ->when($this->filtrePeriode === 'annee', fn($q) =>
                $q->whereRaw("EXTRACT(YEAR FROM d.date_debut_decorticage) = ?", [now()->year])
            )
            ->orderByDesc('d.date_debut_decorticage');

        $decorticages = $query->paginate(15);

        $kpiBase = DB::table('decorticages as d')
            ->when($this->filtrePeriode === 'mois', fn($q) =>
                $q->whereRaw("EXTRACT(MONTH FROM d.date_debut_decorticage) = ?", [now()->month])
                  ->whereRaw("EXTRACT(YEAR FROM d.date_debut_decorticage) = ?", [now()->year])
            )
            ->when($this->filtrePeriode === 'annee', fn($q) =>
                $q->whereRaw("EXTRACT(YEAR FROM d.date_debut_decorticage) = ?", [now()->year])
            )
            ->where('d.statut', 'termine');

        $kpis = [
            'nb'            => (clone $kpiBase)->count(),
            'total_entree'  => (clone $kpiBase)->sum('d.quantite_paddy_entree_kg'),
            'total_blanc'   => (clone $kpiBase)->sum('d.quantite_riz_blanc_kg'),
            'rendement_moy' => (clone $kpiBase)->avg('d.taux_rendement'),
        ];

        return view('livewire.transformation.liste-decorticages', compact('decorticages', 'kpis'))
            ->layout('layouts.app');
    }
}