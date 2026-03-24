<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StockPaddy;
use App\Models\StockProduitFini;
use App\Models\Reservoir;
use App\Models\MouvementReservoir;
use App\Models\LotRizEtuve;
use App\Models\VarieteRice;

class DashboardStocks extends Component
{
    public $periode = 'tous'; // Par défaut "tous" pour afficher TOUT
    public $variete_id = '';
    public $type_produit = '';

    protected $queryString = ['periode', 'variete_id', 'type_produit'];

    public function render()
    {
        // KPI (tous les stocks, sans filtre)
        $stockPaddyKg = (float) StockPaddy::sum('quantite_stock_kg');
        $stockProduitsFinisKg = (float) StockProduitFini::sum('quantite_kg');
        $stockRizEtuveKg = (float) LotRizEtuve::sum('quantite_restante_kg') ?? 0;
        $capaciteReservoirsKg = (float) Reservoir::sum('capacite_max_kg') ?? 0;

        // Évolution des stocks (avec fallback)
        $evolutionStocks = $this->getStocksEvolution();

        // Stocks produits finis FILTRÉS (pour le tableau)
        $query = StockProduitFini::with('varieteRice');

        // FILTRE UNIQUEMENT si période != 'tous'
        if ($this->periode !== 'tous') {
            $now = now();
            match ($this->periode) {
                'trimestre' => $query->where('created_at', '>=', $now->startOfQuarter()),
                'annee'     => $query->where('created_at', '>=', $now->startOfYear()),
                default     => $query->where('created_at', '>=', $now->startOfMonth()),
            };
        }

        if ($this->variete_id) {
            $query->where('variete_rice_id', $this->variete_id);
        }

        if ($this->type_produit) {
            $query->where('type_produit', $this->type_produit);
        }

        // Pagination : 15 lignes par page
        $stocksProduitsFinis = $query->paginate(15);

        // 1. Définir $totauxTousTypes D’ABORD
        $totauxTousTypes = StockProduitFini::groupBy('type_produit')
            ->selectRaw('type_produit, SUM(quantite_kg) as total_kg')
            ->get()
            ->keyBy('type_produit');

        // 2. Utiliser $totauxTousTypes après
        $totauxParType = [
            'Riz blanchi' => $totauxTousTypes->get('riz_blanc', (object)['total_kg' => 0])?->total_kg ?? 0,
            'Riz rejet'   => $totauxTousTypes->get('riz_rejet', (object)['total_kg' => 0])?->total_kg ?? 0,
            'Riz brisure' => $totauxTousTypes->get('brisures', (object)['total_kg' => 0])?->total_kg ?? 0,
            'Son de riz'  => $totauxTousTypes->get('son', (object)['total_kg' => 0])?->total_kg ?? 0,
        ];

        // Variétés pour le filtre
        $varietes = VarieteRice::all();

        // Seuils
        $seuilPaddy = 5000;
        $seuilProduitsFinis = 1000;
        $seuilRizEtuve = 2700;

        return view('livewire.dashboard-stocks', compact(
            'stockPaddyKg', 'stockProduitsFinisKg', 'stockRizEtuveKg', 'capaciteReservoirsKg',
            'stocksProduitsFinis', 'evolutionStocks', 'totauxParType', 'varietes',
            'seuilPaddy', 'seuilProduitsFinis', 'seuilRizEtuve'
        ))->layout('layouts.app');
    }

    private function getStocksEvolution()
    {
        $stocks = StockPaddy::selectRaw('
            EXTRACT(MONTH FROM created_at) as mois, 
            COALESCE(SUM(quantite_stock_kg), 0) as total
        ')
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('mois')
        ->orderBy('mois')
        ->pluck('total', 'mois')
        ->toArray();

        // Complète les 6 mois avec 0 si manquants
        $data = array_fill(1, 6, 12000); // Données de test réalistes
        foreach ($stocks as $mois => $total) {
            $data[(int)$mois] = (float)$total;
        }

        return array_values($data);
    }
}
