<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StockPaddy;
use App\Models\StockProduitFini;
use App\Models\AchatPaddy;
use App\Models\Vente;
use App\Models\FactureClient;
use App\Models\PaiementFacture;
use App\Models\TraitementClient;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
		set_time_limit(60);
        // Stock Paddy
        $stockPaddyKg = StockPaddy::sum('quantite_stock_kg');

        // Stock produits finis
        $stockProduitsFinisKg = StockProduitFini::sum('quantite_kg');
        $nbProduitsFinis = \App\Models\Article::count();

        // Ventes du mois
        $ventesMois = Vente::whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year)
            ->sum('montant_vente_total_fcfa');
        $nbVentesMois = Vente::whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year)
            ->count();

        // Achats du mois
        $achatsMois = AchatPaddy::whereMonth('date_achat', now()->month)
            ->whereYear('date_achat', now()->year)
            ->sum('montant_achat_total_fcfa');
        $nbAchatsMois = AchatPaddy::whereMonth('date_achat', now()->month)
            ->whereYear('date_achat', now()->year)
            ->count();

        // Chiffre d'affaires total de l'année
        $caAnnuel = Vente::whereYear('date_vente', now()->year)
            ->sum('montant_vente_total_fcfa');

        // Total des paiements reçus ce mois
        $paiementsMois = PaiementFacture::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->sum('montant_paye');

        // Top 5 clients (par montant d'achat)
        $topClients = FactureClient::select('client_id', DB::raw('SUM(montant_total) as total'))
            ->with('client')
            ->groupBy('client_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Top 5 fournisseurs
        $topFournisseurs = AchatPaddy::select('fournisseur_id', DB::raw('SUM(montant_achat_total_fcfa) as total'))
            ->with('fournisseur')
            ->groupBy('fournisseur_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Évolution mensuelle des ventes (pour graphique)
        $ventesParMois = Vente::select(
                DB::raw('EXTRACT(MONTH FROM date_vente) as mois'),
                DB::raw('SUM(montant_vente_total_fcfa) as total')
            )
            ->whereYear('date_vente', now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM date_vente)'))
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();

        // Remplir les mois manquants avec 0
        $moisNoms = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $ventesGraph = [];
        for ($i = 1; $i <= 12; $i++) {
            $ventesGraph[] = $ventesParMois[$i] ?? 0;
        }

        // Derniers achats Paddy (5 derniers)
        $derniersAchats = AchatPaddy::with('fournisseur')
            ->latest()
            ->take(5)
            ->get();

        // Dernières ventes (5 dernières)
        $dernieresVentes = Vente::with('client')
            ->latest()
            ->take(5)
            ->get();

        // Dernière mise à jour
        $lastStockUpdate = now()->format('d/m/Y H:i');

        return view('livewire.dashboard', compact(
            'stockPaddyKg',
            'stockProduitsFinisKg',
            'nbProduitsFinis',
            'ventesMois',
            'nbVentesMois',
            'achatsMois',
            'nbAchatsMois',
            'caAnnuel',
            'paiementsMois',
            'topClients',
            'topFournisseurs',
            'ventesGraph',
            'moisNoms',
            'derniersAchats',
            'dernieresVentes',
            'lastStockUpdate'
        ));
    }
}