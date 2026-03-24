<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\PointVente;
use App\Models\Localite;

class DashboardEntreprise extends Component
{
    public $periode = 'mois'; // mois / trimestre / an

    protected $queryString = ['periode'];

    public function render()
    {
        // Comptes globaux
        $totalAgents       = Agent::count();
        $totalClients      = Client::count();
        $totalFournisseurs = Fournisseur::count();
        $totalPointsVente  = PointVente::count();
        $totalLocalites    = Localite::count();

        // Nouveaux par période
        $now = now();
        $debutPeriode = match ($this->periode) {
            'trimestre' => $now->startOfQuarter(),
            'an'        => $now->startOfYear(),
            default     => $now->startOfMonth(),
        };

        $nouveauxClients = Client::where('created_at', '>=', $debutPeriode)->count();
        $nouveauxFournisseurs = Fournisseur::where('created_at', '>=', $debutPeriode)->count();

        // Répartition par localité (ex : clients)
        $clientsParLocalite = Client::with('localite')
            ->whereNotNull('localite_id')
            ->groupBy('localite_id')
            ->selectRaw('localite_id, COUNT(*) as total')
            ->get()
            ->keyBy('localite_id');

        $localites = Localite::all();

        return view('livewire.dashboard-entreprise', compact(
            'totalAgents',
            'totalClients',
            'totalFournisseurs',
            'totalPointsVente',
            'totalLocalites',
            'nouveauxClients',
            'nouveauxFournisseurs',
            'clientsParLocalite',
            'localites'
        ))->layout('layouts.app');
    }
}
