<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardProduction extends Component
{
    public string $periode = 'mois';

    public function render()
    {
        $now = now();

        // Filtre période
        $dateDebut = match($this->periode) {
            'semaine' => $now->copy()->subDays(7),
            'mois'    => $now->copy()->startOfMonth(),
            'annee'   => $now->copy()->startOfYear(),
            default   => null,
        };

        // ----------------------------------------------------------------
        // KPIs globaux (toutes périodes)
        // ----------------------------------------------------------------
        $kpisGlobaux = [
            'etuvages_en_cours'      => DB::table('etuvages')->where('statut', 'en_cours')->count(),
            'decorticages_en_cours'  => DB::table('decorticages')->where('statut', 'en_cours')->count(),
            'lots_riz_etuve_dispo'   => DB::table('lots_riz_etuve')->where('quantite_restante_kg', '>', 0)->count(),
            'stock_riz_blanc_kg'     => DB::table('stocks_produits_finis')->where('type_produit', 'riz_blanc')->sum('quantite_kg'),
            'stock_son_kg'           => DB::table('stocks_produits_finis')->where('type_produit', 'son')->sum('quantite_kg'),
            'stock_brisures_kg'      => DB::table('stocks_produits_finis')->where('type_produit', 'brisures')->sum('quantite_kg'),
            'stock_rejet_kg'         => DB::table('stocks_produits_finis')->where('type_produit', 'rejet')->sum('quantite_kg'),
        ];

        // ----------------------------------------------------------------
        // KPIs période sélectionnée
        // ----------------------------------------------------------------
        $qEtuvage    = DB::table('etuvages')->where('statut', 'termine');
        $qDecort     = DB::table('decorticages')->where('statut', 'termine');

        if ($dateDebut) {
            $qEtuvage->where('date_debut_etuvage', '>=', $dateDebut);
            $qDecort->where('date_debut_decorticage', '>=', $dateDebut);
        }

        $kpisPeriode = [
            'nb_etuvages'         => (clone $qEtuvage)->count(),
            'paddy_etuve_kg'      => (clone $qEtuvage)->sum('quantite_paddy_entree_kg'),
            'riz_etuve_kg'        => DB::table('lots_riz_etuve as lre')
                ->join('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
                ->where('e.statut', 'termine')
                ->when($dateDebut, fn($q) => $q->where('e.date_debut_etuvage', '>=', $dateDebut))
                ->sum('lre.masse_apres_kg'),
            'rendement_etuvage'   => (clone $qEtuvage)->avg(DB::raw(
                'CASE WHEN quantite_paddy_entree_kg > 0 THEN 
                    (SELECT masse_apres_kg FROM lots_riz_etuve WHERE provenance_etuvage_id = etuvages.id LIMIT 1) 
                    / quantite_paddy_entree_kg * 100 
                ELSE NULL END'
            )),
            'nb_decorticages'     => (clone $qDecort)->count(),
            'riz_etuve_traite_kg' => (clone $qDecort)->sum('quantite_paddy_entree_kg'),
            'riz_blanc_produit_kg'=> (clone $qDecort)->sum('quantite_riz_blanc_kg'),
            'son_produit_kg'      => (clone $qDecort)->sum('quantite_son_kg'),
            'brisures_kg'         => (clone $qDecort)->sum('quantite_brise_kg'),
            'rendement_decorticage'=> (clone $qDecort)->avg('taux_rendement'),
        ];

        // ----------------------------------------------------------------
        // Étuvages récents (10 derniers)
        // ----------------------------------------------------------------
        $etuvagesRecents = DB::table('etuvages as e')
            ->leftJoin('lots_paddy as lp', 'lp.id', '=', 'e.lot_paddy_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->leftJoin('lots_riz_etuve as lre', 'lre.provenance_etuvage_id', '=', 'e.id')
            ->select('e.code_etuvage', 'e.statut', 'e.quantite_paddy_entree_kg',
                     'e.date_debut_etuvage', 'v.nom as variete_nom',
                     'lre.masse_apres_kg', 'lre.rendement_pourcentage')
            ->orderByDesc('e.date_debut_etuvage')
            ->limit(8)->get();

        // ----------------------------------------------------------------
        // Décorticages récents (10 derniers)
        // ----------------------------------------------------------------
        $decorticagesRecents = DB::table('decorticages as d')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'd.variete_rice_id')
            ->select('d.code_decorticage', 'd.statut', 'd.quantite_paddy_entree_kg',
                     'd.quantite_riz_blanc_kg', 'd.quantite_son_kg', 'd.quantite_brise_kg',
                     'd.taux_rendement', 'd.date_debut_decorticage', 'v.nom as variete_nom')
            ->orderByDesc('d.date_debut_decorticage')
            ->limit(8)->get();

        // ----------------------------------------------------------------
        // Seuils stocks critiques
        // ----------------------------------------------------------------
        $seuils = DB::table('parametres_app')
            ->whereIn('cle', ['seuil_stock_paddy_kg', 'seuil_stock_riz_blanc_kg', 'seuil_stock_son_kg', 'seuil_stock_brisures_kg'])
            ->pluck('valeur', 'cle');

        $alertes = [];
        if ($kpisGlobaux['stock_riz_blanc_kg'] < (float)($seuils['seuil_stock_riz_blanc_kg'] ?? 500)) {
            $alertes[] = ['type' => 'riz_blanc', 'label' => 'Riz blanc', 'stock' => $kpisGlobaux['stock_riz_blanc_kg'], 'seuil' => $seuils['seuil_stock_riz_blanc_kg'] ?? 500];
        }
        if ($kpisGlobaux['stock_son_kg'] < (float)($seuils['seuil_stock_son_kg'] ?? 200)) {
            $alertes[] = ['type' => 'son', 'label' => 'Son de riz', 'stock' => $kpisGlobaux['stock_son_kg'], 'seuil' => $seuils['seuil_stock_son_kg'] ?? 200];
        }
        if ($kpisGlobaux['stock_brisures_kg'] < (float)($seuils['seuil_stock_brisures_kg'] ?? 200)) {
            $alertes[] = ['type' => 'brisures', 'label' => 'Brisures', 'stock' => $kpisGlobaux['stock_brisures_kg'], 'seuil' => $seuils['seuil_stock_brisures_kg'] ?? 200];
        }

        return view('livewire.dashboards.dashboard-production', compact(
            'kpisGlobaux', 'kpisPeriode', 'etuvagesRecents', 'decorticagesRecents', 'alertes'
        ))->layout('layouts.app');
    }
}