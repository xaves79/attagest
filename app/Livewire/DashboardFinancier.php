<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vente;
use App\Models\AchatPaddy;
use Illuminate\Support\Facades\DB;

class DashboardFinancier extends Component
{
    public function render()
    {
        // Chiffre d’affaires total
        $ca = Vente::sum('montant_vente_total_fcfa');

        // Coût total des achats Paddy
        $coutAchats = AchatPaddy::sum('montant_achat_total_fcfa');

        // Marge brute (CA − coûts d’achats)
        $marge = $ca - $coutAchats;

        // Données mensuelles (CA et coûts)
        $ventesMensuelles = Vente::selectRaw('extract(month from date_vente) as mois, sum(montant_vente_total_fcfa) as ca')
            ->whereYear('date_vente', now()->year)
            ->groupBy(DB::raw('extract(month from date_vente)'))
            ->orderBy('mois')
            ->get();

        $achatsMensuels = AchatPaddy::selectRaw('extract(month from date_achat) as mois, sum(montant_achat_total_fcfa) as cout')
            ->whereYear('date_achat', now()->year)
            ->groupBy(DB::raw('extract(month from date_achat)'))
            ->orderBy('mois')
            ->get();

        $labels = [];
        $caData = [];
        $coutData = [];

        for ($m = 1; $m <= 12; $m++) {
            $label = now()->setMonth($m)->format('M');
            $labels[] = $label;

            $venteRow = $ventesMensuelles->firstWhere('mois', $m);
            $caData[] = $venteRow ? (float) $venteRow->ca : 0;

            $achatRow = $achatsMensuels->firstWhere('mois', $m);
            $coutData[] = $achatRow ? (float) $achatRow->cout : 0;
        }

        return view('livewire.dashboard-financier', [
            'ca' => $ca,
            'coutAchats' => $coutAchats,
            'marge' => $marge,
            'prodLabels' => $labels,
            'caData' => $caData,
            'coutData' => $coutData,
        ])
        ->layout('layouts.app');
    }
}
