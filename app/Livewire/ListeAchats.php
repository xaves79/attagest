<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\AchatPaddy;

class ListeAchats extends Component
{
    use WithPagination;

    public string $recherche   = '';
    public string $filtreStatut = '';
    public string $filtreVariete = '';
    public string $filtrePeriode = 'mois';

    public function updatingRecherche(): void { $this->resetPage(); }
    public function updatingFiltreStatut(): void { $this->resetPage(); }

    public function render()
    {
        $query = AchatPaddy::with(['fournisseur', 'variete', 'agent', 'stockPaddy'])
            ->when($this->recherche, fn($q) =>
                $q->where('code_lot', 'like', "%{$this->recherche}%")
                  ->orWhereHas('fournisseur', fn($q2) =>
                      $q2->where('nom', 'ilike', "%{$this->recherche}%")
                  )
            )
            ->when($this->filtreStatut, fn($q) =>
                $q->where('statut', $this->filtreStatut)
            )
            ->when($this->filtreVariete, fn($q) =>
                $q->where('variete_id', $this->filtreVariete)
            )
            ->when($this->filtrePeriode === 'semaine', fn($q) =>
                $q->where('date_achat', '>=', now()->subDays(7))
            )
            ->when($this->filtrePeriode === 'mois', fn($q) =>
                $q->whereMonth('date_achat', now()->month)->whereYear('date_achat', now()->year)
            )
            ->when($this->filtrePeriode === 'annee', fn($q) =>
                $q->whereYear('date_achat', now()->year)
            )
            ->orderByDesc('date_achat')
            ->orderByDesc('id');

        $achats = $query->paginate(15);

        // KPIs période
        $kpiQuery = AchatPaddy::query()
            ->when($this->filtrePeriode === 'semaine', fn($q) => $q->where('date_achat', '>=', now()->subDays(7)))
            ->when($this->filtrePeriode === 'mois', fn($q) => $q->whereMonth('date_achat', now()->month)->whereYear('date_achat', now()->year))
            ->when($this->filtrePeriode === 'annee', fn($q) => $q->whereYear('date_achat', now()->year));

        $kpis = [
            'nb_lots'        => (clone $kpiQuery)->count(),
            'total_kg'       => (clone $kpiQuery)->sum('quantite_achat_kg'),
            'total_montant'  => (clone $kpiQuery)->sum('montant_achat_total_fcfa'),
            'disponible_kg'  => (clone $kpiQuery)->where('statut', 'disponible')->sum('quantite_restante_kg'),
        ];

        $varietes = DB::table('varietes_rice')->orderBy('nom')->get();

        return view('livewire.achats.liste-achats', compact('achats', 'kpis', 'varietes'))
            ->layout('layouts.app');
    }
}