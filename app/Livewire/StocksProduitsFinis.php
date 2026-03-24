<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class StocksProduitsFinis extends Component
{
    use WithPagination;

    public string $recherche      = '';
    public string $filtreType     = '';
    public string $filtreStatut   = 'disponible';

    public function updatingRecherche(): void { $this->resetPage(); }
    public function updatingFiltreType(): void { $this->resetPage(); }

    public function render()
    {
        // KPIs par type
        $kpis = DB::table('stocks_produits_finis')
            ->selectRaw('type_produit, COUNT(*) as nb_lots, SUM(quantite_kg) as total_kg')
            ->where('statut', 'disponible')
            ->groupBy('type_produit')
            ->get()
            ->keyBy('type_produit');

        // Seuils pour alertes
        $seuils = DB::table('parametres_app')
            ->whereIn('cle', ['seuil_stock_riz_blanc_kg', 'seuil_stock_son_kg', 'seuil_stock_brisures_kg'])
            ->pluck('valeur', 'cle');

        // Liste des stocks
        $stocks = DB::table('stocks_produits_finis as spf')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'spf.variete_rice_id')
            ->leftJoin('decorticages as d', 'd.id', '=', 'spf.decorticage_id')
            ->leftJoin('agents as a', 'a.id', '=', 'spf.agent_id')
            ->select(
                'spf.id', 'spf.code_stock', 'spf.type_produit', 'spf.quantite_kg',
                'spf.statut', 'spf.created_at',
                'v.nom as variete_nom',
                'd.code_decorticage',
                'a.nom as agent_nom', 'a.prenom as agent_prenom'
            )
            ->when($this->recherche, fn($q) =>
                $q->where('spf.code_stock', 'ilike', "%{$this->recherche}%")
                  ->orWhere('v.nom', 'ilike', "%{$this->recherche}%")
                  ->orWhere('d.code_decorticage', 'ilike', "%{$this->recherche}%")
            )
            ->when($this->filtreType, fn($q) => $q->where('spf.type_produit', $this->filtreType))
            ->when($this->filtreStatut, fn($q) => $q->where('spf.statut', $this->filtreStatut))
            ->orderByDesc('spf.created_at')
            ->paginate(20);

        return view('livewire.stocks-produits-finis.index', compact('kpis', 'stocks', 'seuils'))
            ->layout('layouts.app');
    }
}