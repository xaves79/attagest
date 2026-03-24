<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BilansGlobaux extends Component
{
    public string $date_debut = '';
    public string $date_fin   = '';
    public string $periode    = 'mois';

    public function mount(): void
    {
        $this->date_debut = now()->startOfMonth()->format('Y-m-d');
        $this->date_fin   = now()->format('Y-m-d');
    }

    public function updatedPeriode(): void
    {
        match($this->periode) {
            'semaine' => [$this->date_debut, $this->date_fin] = [now()->subDays(7)->format('Y-m-d'), now()->format('Y-m-d')],
            'mois'    => [$this->date_debut, $this->date_fin] = [now()->startOfMonth()->format('Y-m-d'), now()->format('Y-m-d')],
            'annee'   => [$this->date_debut, $this->date_fin] = [now()->startOfYear()->format('Y-m-d'), now()->format('Y-m-d')],
            default   => null,
        };
    }

    private function getData(): array
    {
        $debut = Carbon::parse($this->date_debut)->startOfDay();
        $fin   = Carbon::parse($this->date_fin)->endOfDay();

        // ── ACHATS ──────────────────────────────────────────────────
        $achats = DB::table('lots_paddy')
            ->whereBetween('date_achat', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(quantite_achat_kg) as total_kg, SUM(montant_achat_total_fcfa) as total_fcfa')
            ->first();

        $achatsFournisseurs = DB::table('lots_paddy as lp')
            ->join('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->whereBetween('lp.date_achat', [$debut, $fin])
            ->selectRaw('f.nom, COUNT(*) as nb_achats, SUM(lp.quantite_achat_kg) as total_kg, SUM(lp.montant_achat_total_fcfa) as total_fcfa')
            ->groupBy('f.id', 'f.nom')
            ->orderByDesc('total_fcfa')
            ->get();

        // ── PRODUCTION ──────────────────────────────────────────────
        $etuvages = DB::table('etuvages')
            ->where('statut', 'termine')
            ->whereBetween('date_debut_etuvage', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(quantite_paddy_entree_kg) as total_entree')
            ->first();

        $rizEtuve = DB::table('lots_riz_etuve as lre')
            ->join('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
            ->where('e.statut', 'termine')
            ->whereBetween('e.date_debut_etuvage', [$debut, $fin])
            ->selectRaw('SUM(lre.masse_apres_kg) as total_kg, AVG(lre.rendement_pourcentage) as rdt_moy')
            ->first();

        $decorticages = DB::table('decorticages')
            ->where('statut', 'termine')
            ->whereBetween('date_debut_decorticage', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(quantite_paddy_entree_kg) as total_entree,
                         SUM(quantite_riz_blanc_kg) as total_blanc, SUM(quantite_son_kg) as total_son,
                         SUM(quantite_brise_kg) as total_brisures, AVG(taux_rendement) as rdt_moy')
            ->first();

        $prodParVariete = DB::table('decorticages as d')
            ->join('varietes_rice as v', 'v.id', '=', 'd.variete_rice_id')
            ->where('d.statut', 'termine')
            ->whereBetween('d.date_debut_decorticage', [$debut, $fin])
            ->selectRaw('v.nom as variete, SUM(d.quantite_riz_blanc_kg) as riz_blanc_kg, SUM(d.quantite_son_kg) as son_kg, AVG(d.taux_rendement) as rdt_moy')
            ->groupBy('v.id', 'v.nom')
            ->orderByDesc('riz_blanc_kg')
            ->get();

        // ── VENTES ──────────────────────────────────────────────────
        $ventes = DB::table('ventes')
            ->whereBetween('created_at', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(quantite_vendue_kg) as total_kg, SUM(montant_vente_total_fcfa) as total_fcfa')
            ->first();

        $commandes = DB::table('commandes_vente')
            ->whereBetween('date_commande', [$debut, $fin])
            ->selectRaw('COUNT(*) as nb, SUM(montant_total_fcfa) as total_fcfa,
                         SUM(CASE WHEN type_vente = \'comptant\' THEN 1 ELSE 0 END) as nb_comptant,
                         SUM(CASE WHEN type_vente = \'credit\' THEN 1 ELSE 0 END) as nb_credit')
            ->first();

        $ventesClients = DB::table('commandes_vente as cv')
            ->join('clients as c', 'c.id', '=', 'cv.client_id')
            ->whereBetween('cv.date_commande', [$debut, $fin])
            ->selectRaw('c.nom, c.raison_sociale, COUNT(*) as nb_commandes, SUM(cv.montant_total_fcfa) as total_fcfa')
            ->groupBy('c.id', 'c.nom', 'c.raison_sociale')
            ->orderByDesc('total_fcfa')
            ->limit(10)
            ->get();

        // ── PAIEMENTS ───────────────────────────────────────────────
        $paiementsClients = DB::table('paiements_factures')
            ->whereBetween('date_paiement', [$debut, $fin])
            ->selectRaw('SUM(montant_paye) as total_encaisse, COUNT(*) as nb')
            ->first();

        $paiementsFournisseurs = DB::table('paiements_fournisseurs')
            ->whereBetween('date_paiement', [$debut, $fin])
            ->selectRaw('SUM(montant) as total_paye, COUNT(*) as nb')
            ->first();

        $soldesClients = DB::table('factures_clients')
            ->where('statut', '!=', 'payee')
            ->selectRaw('SUM(solde_restant) as total_solde, COUNT(*) as nb_factures')
            ->first();

        $soldesFournisseurs = DB::table('recus_fournisseurs')
            ->where('paye', false)
            ->selectRaw('SUM(solde_du) as total_solde, COUNT(*) as nb_recus')
            ->first();

        return compact(
            'achats', 'achatsFournisseurs',
            'etuvages', 'rizEtuve', 'decorticages', 'prodParVariete',
            'ventes', 'commandes', 'ventesClients',
            'paiementsClients', 'paiementsFournisseurs',
            'soldesClients', 'soldesFournisseurs'
        );
    }

    public function telechargerPdf()
    {
        $data = $this->getData();
        $entreprise = DB::table('entreprises')->first();

        $pdf = Pdf::loadView('pdf.bilan-global', array_merge($data, [
            'date_debut' => $this->date_debut,
            'date_fin'   => $this->date_fin,
            'entreprise' => $entreprise,
        ]))->setPaper('a4', 'portrait')
           ->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'dpi' => 150]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "bilan_attagest_{$this->date_debut}_{$this->date_fin}.pdf"
        );
    }

    public function render()
    {
        $data = $this->getData();
        return view('livewire.bilans-globaux', $data)->layout('layouts.app');
    }
}