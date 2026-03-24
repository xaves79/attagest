<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AlertesStock extends Component
{
    public bool $visible = true;

    public function dismiss(): void
    {
        $this->visible = false;
    }

    public function render()
    {
        if (!$this->visible) {
            return view('livewire.alertes-stock', ['alertes' => []]);
        }

        $seuils = DB::table('parametres_app')
            ->whereIn('cle', [
                'seuil_stock_paddy_kg',
                'seuil_stock_riz_blanc_kg',
                'seuil_stock_son_kg',
                'seuil_stock_brisures_kg',
            ])
            ->pluck('valeur', 'cle');

        $stocks = DB::table('stocks_produits_finis')
            ->selectRaw('type_produit, SUM(quantite_kg) as total_kg')
            ->groupBy('type_produit')
            ->pluck('total_kg', 'type_produit');

        $stockPaddy = DB::table('lots_paddy')
            ->whereNotIn('statut', ['complet', 'epuise'])
            ->sum('quantite_restante_kg');

        $alertes = [];

        if ($stockPaddy < (float)($seuils['seuil_stock_paddy_kg'] ?? 1000)) {
            $alertes[] = [
                'label'  => 'Stock paddy',
                'stock'  => $stockPaddy,
                'seuil'  => $seuils['seuil_stock_paddy_kg'] ?? 1000,
                'icon'   => '🌾',
                'route'  => 'achats.index',
            ];
        }
        if (($stocks['riz_blanc'] ?? 0) < (float)($seuils['seuil_stock_riz_blanc_kg'] ?? 500)) {
            $alertes[] = [
                'label'  => 'Riz blanc',
                'stock'  => $stocks['riz_blanc'] ?? 0,
                'seuil'  => $seuils['seuil_stock_riz_blanc_kg'] ?? 500,
                'icon'   => '🍚',
                'route'  => 'decorticages.index',
            ];
        }
        if (($stocks['son'] ?? 0) < (float)($seuils['seuil_stock_son_kg'] ?? 200)) {
            $alertes[] = [
                'label'  => 'Son de riz',
                'stock'  => $stocks['son'] ?? 0,
                'seuil'  => $seuils['seuil_stock_son_kg'] ?? 200,
                'icon'   => '🟤',
                'route'  => 'decorticages.index',
            ];
        }
        if (($stocks['brisures'] ?? 0) < (float)($seuils['seuil_stock_brisures_kg'] ?? 200)) {
            $alertes[] = [
                'label'  => 'Brisures',
                'stock'  => $stocks['brisures'] ?? 0,
                'seuil'  => $seuils['seuil_stock_brisures_kg'] ?? 200,
                'icon'   => '💛',
                'route'  => 'decorticages.index',
            ];
        }

        return view('livewire.alertes-stock', compact('alertes'));
    }
}