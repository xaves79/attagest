<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AchatPaddy;
use App\Models\Fournisseur;
use App\Models\RecuFournisseur;
use Illuminate\Support\Facades\DB;

class DashboardAchats extends Component
{
    public function render()
    {
        // KPI : montant total des achats Paddy
        $montantTotalAchats = AchatPaddy::sum('montant_achat_total_fcfa');

        // Nombre de réceptions fournisseurs
        $nombreRecus = RecuFournisseur::count();

        // Nombre de fournisseurs actifs (ayant au moins un achat ou une réception)
        $fournisseursActifs = RecuFournisseur::distinct('fournisseur_id')
            ->count('fournisseur_id');

        // Derniers achats Paddy
        $derniersAchats = AchatPaddy::with('fournisseur')
            ->latest()
            ->take(5)
            ->get();

        // Dernières réceptions fournisseurs
        $derniersRecus = RecuFournisseur::with('fournisseur')
            ->latest()
            ->take(5)
            ->get();

        // Données pour le graphique achats Paddy mensuels
        $achatsMensuels = AchatPaddy::selectRaw('extract(month from date_achat) as mois, sum(montant_achat_total_fcfa) as montant')
            ->whereYear('date_achat', now()->year)
            ->groupBy(DB::raw('extract(month from date_achat)'))
            ->orderBy('mois')
            ->get();

        $labels = [];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $label = now()->setMonth($m)->format('M');
            $row = $achatsMensuels->firstWhere('mois', $m);
            $labels[] = $label;
            $data[] = $row ? (float) $row->montant : 0;
        }

        return view('livewire.dashboard-achats', [
            'montantTotalAchats' => $montantTotalAchats,
            'nombreRecus' => $nombreRecus,
            'fournisseursActifs' => $fournisseursActifs,
            'derniersAchats' => $derniersAchats,
            'derniersRecus' => $derniersRecus,
            'achatsLabels' => $labels,
            'achatsData' => $data,
        ])
        ->layout('layouts.app');
    }
}
