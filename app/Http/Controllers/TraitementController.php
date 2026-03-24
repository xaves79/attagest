<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TraitementController extends Controller
{
    public function imprimer(int $id)
    {
        $t = DB::selectOne("
            SELECT t.*,
                   c.nom as client_nom, c.raison_sociale, c.telephone as client_tel,
                   a.nom as agent_nom, a.prenom as agent_prenom,
                   v.nom as variete_nom,
                   l.nom as localite_nom,
                   t.quantite_paddy_kg::text      as qte_paddy,
                   t.quantite_riz_blanc_kg::text  as qte_blanc,
                   t.quantite_son_kg::text        as qte_son,
                   t.taux_rendement::text         as taux,
                   t.prix_traitement_par_kg::text as prix_kg,
                   t.montant_traitement_fcfa::text as montant
            FROM traitements_client t
            LEFT JOIN clients c ON c.id = t.client_id
            LEFT JOIN agents a ON a.id = t.agent_id
            LEFT JOIN varietes_rice v ON v.id = t.variete_id
            LEFT JOIN localites l ON l.id = t.localite_id
            WHERE t.id = ?
        ", [$id]);

        if (!$t) abort(404);

        $paiements = DB::table('paiements_traitements')
            ->where('traitement_id', $id)
            ->where('statut', 'paye')
            ->orderBy('date_paiement')
            ->get();

        $totalPaye = $paiements->sum('montant_paye');
        $solde     = max(0, (float)$t->montant - $totalPaye);

        $entreprise = DB::table('entreprises')->first();

        $pdf = Pdf::loadView('pdf.traitement-client', compact('t', 'paiements', 'totalPaye', 'solde', 'entreprise'))
            ->setPaper([0, 0, 419.53, 595.28], 'portrait') // A5
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isRemoteEnabled'      => false,
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 150,
            ]);

        return $pdf->stream("Traitement-{$t->code_traitement}.pdf");
    }
}