<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\CommandeVente;
use App\Models\Vente;
use App\Models\Client;
use App\Models\PointVente;

class DashboardVentes extends Component
{
    public string $periode    = 'mois';   // mois | trimestre | annee
    public string $annee      = '';
    public string $mois       = '';
    public ?int   $pointVenteId = null;

    public function mount(): void
    {
        $this->annee = now()->format('Y');
        $this->mois  = now()->format('m');
    }

    // ----------------------------------------------------------------
    // Helpers de période
    // ----------------------------------------------------------------
    private function dateDebut(): string
    {
        return match($this->periode) {
            'mois'      => "{$this->annee}-{$this->mois}-01",
            'trimestre' => $this->debutTrimestre(),
            'annee'     => "{$this->annee}-01-01",
            default     => "{$this->annee}-{$this->mois}-01",
        };
    }

    private function dateFin(): string
    {
        return match($this->periode) {
            'mois'      => now()->parse($this->dateDebut())->endOfMonth()->format('Y-m-d'),
            'trimestre' => $this->finTrimestre(),
            'annee'     => "{$this->annee}-12-31",
            default     => now()->parse($this->dateDebut())->endOfMonth()->format('Y-m-d'),
        };
    }

    private function debutTrimestre(): string
    {
        $m = (int) $this->mois;
        $t = (int) ceil($m / 3);
        $debut = ($t - 1) * 3 + 1;
        return "{$this->annee}-" . str_pad($debut, 2, '0', STR_PAD_LEFT) . "-01";
    }

    private function finTrimestre(): string
    {
        $m = (int) $this->mois;
        $t = (int) ceil($m / 3);
        $fin = $t * 3;
        return now()->parse("{$this->annee}-" . str_pad($fin, 2, '0', STR_PAD_LEFT) . "-01")
                    ->endOfMonth()->format('Y-m-d');
    }

    private function baseQuery()
    {
        return CommandeVente::whereBetween('date_commande', [$this->dateDebut(), $this->dateFin()])
            ->when($this->pointVenteId, fn($q) => $q->where('point_vente_id', $this->pointVenteId))
            ->where('statut', '!=', 'annulee');
    }

    // ----------------------------------------------------------------
    // KPIs principaux
    // ----------------------------------------------------------------
    private function kpis(): array
    {
        $data = $this->baseQuery()->selectRaw("
            COUNT(*)                                                  AS nb_commandes,
            SUM(montant_total_fcfa)                                   AS ca_total,
            SUM(montant_acompte_fcfa)                                 AS ca_encaisse,
            SUM(montant_solde_fcfa)                                   AS ca_en_attente,
            COUNT(*) FILTER (WHERE statut = 'livree')                 AS nb_livrees,
            COUNT(*) FILTER (WHERE statut = 'annulee')                AS nb_annulees,
            COUNT(*) FILTER (WHERE type_vente = 'credit'
                             AND montant_solde_fcfa > 0)              AS nb_credit_ouvert,
            AVG(montant_total_fcfa)                                   AS panier_moyen
        ")->first();

        return [
            'nb_commandes'    => (int)   ($data->nb_commandes ?? 0),
            'ca_total'        => (int)   ($data->ca_total ?? 0),
            'ca_encaisse'     => (int)   ($data->ca_encaisse ?? 0),
            'ca_en_attente'   => (int)   ($data->ca_en_attente ?? 0),
            'nb_livrees'      => (int)   ($data->nb_livrees ?? 0),
            'nb_credit_ouvert'=> (int)   ($data->nb_credit_ouvert ?? 0),
            'panier_moyen'    => (int)   ($data->panier_moyen ?? 0),
            'taux_livraison'  => $data->nb_commandes > 0
                ? round(($data->nb_livrees / $data->nb_commandes) * 100)
                : 0,
        ];
    }

    // ----------------------------------------------------------------
    // CA par jour (courbe)
    // ----------------------------------------------------------------
    private function caParJour(): array
    {
        if ($this->periode === 'annee') {
            // Par mois pour l'année
            return $this->baseQuery()
                ->selectRaw("TO_CHAR(date_commande, 'YYYY-MM') AS periode, SUM(montant_total_fcfa) AS ca")
                ->groupBy('periode')
                ->orderBy('periode')
                ->get()
                ->map(fn($r) => [
                    'label' => now()->parse($r->periode . '-01')->format('M y'),
                    'ca'    => (int) $r->ca,
                ])
                ->toArray();
        }

        return $this->baseQuery()
            ->selectRaw("date_commande::date AS jour, SUM(montant_total_fcfa) AS ca")
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->map(fn($r) => [
                'label' => now()->parse($r->jour)->format('d/m'),
                'ca'    => (int) $r->ca,
            ])
            ->toArray();
    }

    // ----------------------------------------------------------------
    // Répartition par type de vente
    // ----------------------------------------------------------------
    private function repartitionTypes(): array
    {
        return $this->baseQuery()
            ->selectRaw("type_vente, COUNT(*) AS nb, SUM(montant_total_fcfa) AS ca")
            ->groupBy('type_vente')
            ->orderByDesc('ca')
            ->get()
            ->map(fn($r) => [
                'type' => $r->type_vente,
                'nb'   => (int) $r->nb,
                'ca'   => (int) $r->ca,
            ])
            ->toArray();
    }

    // ----------------------------------------------------------------
    // Top clients
    // ----------------------------------------------------------------
    private function topClients(): array
    {
        return $this->baseQuery()
            ->selectRaw("client_id, SUM(montant_total_fcfa) AS ca, COUNT(*) AS nb")
            ->with('client')
            ->groupBy('client_id')
            ->orderByDesc('ca')
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'nom' => $r->client->raison_sociale
                    ?? ($r->client->nom . ' ' . $r->client->prenom),
                'ca'  => (int) $r->ca,
                'nb'  => (int) $r->nb,
            ])
            ->toArray();
    }

    // ----------------------------------------------------------------
    // Commandes crédit en retard
    // ----------------------------------------------------------------
    private function creditEnRetard(): \Illuminate\Database\Eloquent\Collection
    {
        return CommandeVente::with(['client', 'pointVente'])
            ->where('type_vente', 'credit')
            ->where('statut', '!=', 'annulee')
            ->where('montant_solde_fcfa', '>', 0)
            ->where('date_echeance', '<', now()->format('Y-m-d'))
            ->when($this->pointVenteId, fn($q) => $q->where('point_vente_id', $this->pointVenteId))
            ->orderBy('date_echeance')
            ->limit(10)
            ->get();
    }

    // ----------------------------------------------------------------
    // Performance par point de vente
    // ----------------------------------------------------------------
    private function perfPointsVente(): array
    {
        return $this->baseQuery()
            ->selectRaw("point_vente_id, SUM(montant_total_fcfa) AS ca, COUNT(*) AS nb")
            ->with('pointVente')
            ->groupBy('point_vente_id')
            ->orderByDesc('ca')
            ->get()
            ->map(fn($r) => [
                'nom' => $r->pointVente->nom ?? '—',
                'ca'  => (int) $r->ca,
                'nb'  => (int) $r->nb,
            ])
            ->toArray();
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------
    public function render()
    {
        $kpis          = $this->kpis();
        $caParJour     = $this->caParJour();
        $repartition   = $this->repartitionTypes();
        $topClients    = $this->topClients();
        $creditRetard  = $this->creditEnRetard();
        $perfPV        = $this->perfPointsVente();
        $pointsVente   = PointVente::orderBy('nom')->get();
        $caTotal       = $kpis['ca_total'];

        return view('livewire.dashboards.dashboard-ventes', compact(
            'kpis', 'caParJour', 'repartition', 'topClients',
            'creditRetard', 'perfPV', 'pointsVente', 'caTotal'
        ))->layout('layouts.app');
    }
}