<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class Rapports extends Component
{
    public string $periode = 'tous';
    public int    $annee   = 2026;
    public int    $mois    = 3;
    public int    $semaine = 1;

    public function mount(): void
    {
        $this->annee   = now()->year;
        $this->mois    = now()->month;
        $this->semaine = (int)now()->week;
    }

    private function getDates(): array
    {
        switch ($this->periode) {
            case 'journalier':
                return [now()->startOfDay(), now()->endOfDay(), 'Aujourd\'hui — ' . now()->format('d/m/Y')];
            case 'hebdomadaire':
                $debut = now()->setISODate($this->annee, $this->semaine)->startOfWeek();
                $fin   = $debut->copy()->endOfWeek();
                return [$debut, $fin, 'Semaine ' . $this->semaine . ' — ' . $debut->format('d/m') . ' → ' . $fin->format('d/m/Y')];
            case 'mensuel':
                $debut = now()->create($this->annee, $this->mois, 1)->startOfMonth();
                $fin   = $debut->copy()->endOfMonth();
                return [$debut, $fin, $debut->locale('fr')->isoFormat('MMMM YYYY')];
            case 'annuel':
                $debut = now()->create($this->annee, 1, 1)->startOfYear();
                $fin   = $debut->copy()->endOfYear();
                return [$debut, $fin, 'Année ' . $this->annee];
            default:
                return [null, null, 'Depuis le début (' . now()->year . ')'];
        }
    }

    private function getData(): array
    {
        [$debut, $fin, $label] = $this->getDates();

        $achats = DB::table('lots_paddy as lp')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->select('lp.date_achat', 'f.nom as fournisseur',
                     DB::raw('lp.quantite_achat_kg::numeric as qte'),
                     DB::raw('lp.montant_achat_total_fcfa::numeric as montant'))
            ->when($debut, fn($q) => $q->whereBetween('lp.date_achat', [$debut->format('Y-m-d'), $fin->format('Y-m-d')]))
            ->orderBy('lp.date_achat')
            ->get();

        $ventes = DB::table('ventes as v')
            ->leftJoin('clients as c', 'c.id', '=', 'v.client_id')
            ->select('v.created_at as date_vente', 'c.nom as client', 'c.raison_sociale',
                     DB::raw('v.quantite_vendue_kg::numeric as qte'),
                     DB::raw('v.montant_vente_total_fcfa::numeric as montant'))
            ->when($debut, fn($q) => $q->whereBetween('v.created_at', [$debut, $fin]))
            ->orderBy('v.created_at')
            ->get();

        $traitements = DB::table('traitements_client as t')
            ->leftJoin('clients as c', 'c.id', '=', 't.client_id')
            ->select('t.date_reception', 'c.nom as client', 'c.raison_sociale',
                     DB::raw('t.quantite_paddy_kg::numeric as qte_paddy'),
                     DB::raw('t.quantite_riz_blanc_kg::numeric as qte_blanc'),
                     DB::raw('t.montant_traitement_fcfa::numeric as montant'),
                     't.statut')
            ->when($debut, fn($q) => $q->whereBetween('t.date_reception', [$debut->format('Y-m-d'), $fin->format('Y-m-d')]))
            ->orderBy('t.date_reception')
            ->get();

        $paiements = DB::table('paiements_factures')
            ->when($debut, fn($q) => $q->whereBetween('date_paiement', [$debut->format('Y-m-d'), $fin->format('Y-m-d')]))
            ->sum('montant_paye');

        $global = [
            'paddy_achete_kg'   => $achats->sum('qte'),
            'paddy_achete_fcfa' => $achats->sum('montant'),
            'riz_vendu_kg'      => $ventes->sum('qte'),
            'riz_vendu_fcfa'    => $ventes->sum('montant'),
            'paddy_traite_kg'   => $traitements->sum('qte_paddy'),
            'riz_blanc_kg'      => $traitements->sum('qte_blanc'),
            'paiements_fcfa'    => $paiements,
            'achats_count'      => $achats->count(),
            'ventes_count'      => $ventes->count(),
            'label'             => $label,
        ];

        return compact('achats', 'ventes', 'traitements', 'global', 'label');
    }

    public function exportPdf()
    {
        $data       = $this->getData();
        $entreprise = DB::table('entreprises')->first();

        $pdf = Pdf::loadView('pdf.rapports', array_merge($data, [
            'entreprise' => $entreprise,
            'global' => [
                'periode_label'     => $data['label'],
                'paddy_achete_kg'   => $data['global']['paddy_achete_kg'],
                'paddy_achete_fcfa' => $data['global']['paddy_achete_fcfa'],
                'riz_vendu_kg'      => $data['global']['riz_vendu_kg'],
                'riz_vendu_fcfa'    => $data['global']['riz_vendu_fcfa'],
                'paiements_fcfa'    => $data['global']['paiements_fcfa'],
            ],
        ]))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans', 'isHtml5ParserEnabled' => true, 'dpi' => 150]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "rapport-attagest-" . now()->format('Y-m-d') . ".pdf"
        );
    }

    public function exportCsv()
    {
        $data     = $this->getData();
        $filename = "rapport-attagest-" . now()->format('Y-m-d') . ".csv";

        $callback = function () use ($data) {
            $f = fopen('php://output', 'w');
            fputs($f, "\xEF\xBB\xBF");
            fputcsv($f, ['RAPPORT ATTAGEST — ' . $data['label']]);
            fputcsv($f, []);
            fputcsv($f, ['ACHATS PADDY']);
            fputcsv($f, ['Date', 'Fournisseur', 'Quantité (kg)', 'Montant (FCFA)']);
            foreach ($data['achats'] as $a) {
                fputcsv($f, [\Carbon\Carbon::parse($a->date_achat)->format('d/m/Y'), $a->fournisseur, (int)$a->qte, (int)$a->montant]);
            }
            fputcsv($f, []);
            fputcsv($f, ['VENTES RIZ']);
            fputcsv($f, ['Date', 'Client', 'Quantité (kg)', 'Montant (FCFA)']);
            foreach ($data['ventes'] as $v) {
                fputcsv($f, [\Carbon\Carbon::parse($v->date_vente)->format('d/m/Y'), $v->raison_sociale ?: $v->client, (int)$v->qte, (int)$v->montant]);
            }
            fputcsv($f, []);
            fputcsv($f, ['TRAITEMENTS CLIENTS']);
            fputcsv($f, ['Date', 'Client', 'Paddy (kg)', 'Riz blanc (kg)', 'Montant (FCFA)']);
            foreach ($data['traitements'] as $t) {
                fputcsv($f, [\Carbon\Carbon::parse($t->date_reception)->format('d/m/Y'), $t->raison_sociale ?: $t->client, (int)$t->qte_paddy, (int)$t->qte_blanc, (int)$t->montant]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function render()
    {
        $data = $this->getData();
        return view('livewire.rapports', $data)->layout('layouts.app');
    }
}