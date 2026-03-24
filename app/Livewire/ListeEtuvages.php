<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListeEtuvages extends Component
{
    use WithPagination;

    public string $recherche    = '';
    public string $filtrePeriode = 'mois';

    public function updatingRecherche(): void { $this->resetPage(); }

    public function render()
    {
        $query = DB::table('etuvages as e')
            ->leftJoin('lots_paddy as lp', 'lp.id', '=', 'e.lot_paddy_id')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->leftJoin('agents as a', 'a.id', '=', 'e.agent_id')
            ->leftJoin('lots_riz_etuve as lre', 'lre.provenance_etuvage_id', '=', 'e.id')
            ->select(
                'e.id', 'e.code_etuvage', 'e.quantite_paddy_entree_kg',
                'e.date_debut_etuvage', 'e.statut',
                'lp.code_lot', 'v.nom as variete_nom', 'f.nom as fournisseur_nom',
                'a.nom as agent_nom', 'a.prenom as agent_prenom',
                'lre.code_lot as code_lot_etuve', 'lre.masse_apres_kg', 'lre.rendement_pourcentage'
            )
            ->when($this->recherche, fn($q) =>
                $q->where('e.code_etuvage', 'ilike', "%{$this->recherche}%")
                  ->orWhere('lp.code_lot', 'ilike', "%{$this->recherche}%")
            )
            ->when($this->filtrePeriode === 'semaine', fn($q) =>
                $q->where('e.date_debut_etuvage', '>=', now()->subDays(7))
            )
            ->when($this->filtrePeriode === 'mois', fn($q) =>
                $q->whereRaw("EXTRACT(MONTH FROM e.date_debut_etuvage) = ?", [now()->month])
                  ->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year])
            )
            ->when($this->filtrePeriode === 'annee', fn($q) =>
                $q->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year])
            )
            ->orderByDesc('e.date_debut_etuvage');

        $etuvages = $query->paginate(15);

        // KPIs
        $kpiBase = DB::table('etuvages as e')
            ->when($this->filtrePeriode === 'semaine', fn($q) => $q->where('e.date_debut_etuvage', '>=', now()->subDays(7)))
            ->when($this->filtrePeriode === 'mois', fn($q) => $q->whereRaw("EXTRACT(MONTH FROM e.date_debut_etuvage) = ?", [now()->month])->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year]))
            ->when($this->filtrePeriode === 'annee', fn($q) => $q->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year]));

        $kpis = [
            'nb'             => (clone $kpiBase)->count(),
            'total_entree'   => (clone $kpiBase)->sum('e.quantite_paddy_entree_kg'),
            'total_etuve'    => DB::table('lots_riz_etuve as lre')
                ->join('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
                ->when($this->filtrePeriode === 'mois', fn($q) => $q->whereRaw("EXTRACT(MONTH FROM e.date_debut_etuvage) = ?", [now()->month])->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year]))
                ->sum('lre.masse_apres_kg'),
            'rendement_moy'  => DB::table('lots_riz_etuve as lre')
                ->join('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
                ->when($this->filtrePeriode === 'mois', fn($q) => $q->whereRaw("EXTRACT(MONTH FROM e.date_debut_etuvage) = ?", [now()->month])->whereRaw("EXTRACT(YEAR FROM e.date_debut_etuvage) = ?", [now()->year]))
                ->avg('lre.rendement_pourcentage'),
        ];

        return view('livewire.transformation.liste-etuvages', compact('etuvages', 'kpis'))
            ->layout('layouts.app');
    }
}