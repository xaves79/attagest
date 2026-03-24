<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class StocksPaddy extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $query = DB::table('stocks_paddy as s')
            ->leftJoin('lots_paddy as lp', 'lp.id', '=', 's.lot_paddy_id')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->leftJoin('agents as a', 'a.id', '=', 's.agent_id')
            ->select(
                's.id', 's.code_stock', 's.quantite_stock_kg', 's.quantite_restante_kg',
                's.emplacement', 's.created_at',
                'lp.code_lot', 'lp.statut as lot_statut',
                'f.nom as fournisseur_nom',
                'v.nom as variete_nom',
                'a.nom as agent_nom', 'a.prenom as agent_prenom'
            )
            ->when($this->search, fn($q) =>
                $q->where('s.code_stock', 'ilike', "%{$this->search}%")
                  ->orWhere('lp.code_lot', 'ilike', "%{$this->search}%")
                  ->orWhere('s.emplacement', 'ilike', "%{$this->search}%")
                  ->orWhere('f.nom', 'ilike', "%{$this->search}%")
            )
            ->orderByDesc('s.created_at');

        $stocks = $query->paginate(15);

        // KPIs
        $kpis = [
            'total_lots'       => DB::table('stocks_paddy')->count(),
            'total_kg'         => DB::table('stocks_paddy')->sum('quantite_stock_kg'),
            'restant_kg'       => DB::table('stocks_paddy')->sum('quantite_restante_kg'),
            'lots_disponibles' => DB::table('lots_paddy')->where('quantite_restante_kg', '>', 0)->whereNotIn('statut', ['complet', 'epuise'])->count(),
        ];

        return view('livewire.stocks-paddy.index', compact('stocks', 'kpis'))
            ->layout('layouts.app');
    }
}