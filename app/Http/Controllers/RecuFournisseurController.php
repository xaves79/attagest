<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RecuFournisseurController extends Controller
{
    public function imprimer(int $id)
    {
        $recu = DB::table('recus_fournisseurs as r')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'r.fournisseur_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'r.variete_rice_id')
            ->leftJoin('lots_paddy as lp', 'lp.id', '=', 'r.achat_paddy_id')
            ->leftJoin('localites as l', 'l.id', '=', 'lp.localite_id')
            ->leftJoin('agents as a', 'a.id', '=', 'lp.agent_id')
            ->select(
                'r.*',
                'f.nom as fournisseur_nom', 'f.prenom as fournisseur_prenom',
                'f.telephone as fournisseur_tel', 'f.email as fournisseur_email', 'f.whatsapp as fournisseur_whatsapp',
                'f.type_personne',
                'v.nom as variete_nom',
                'lp.code_lot', 'lp.quantite_achat_kg', 'lp.prix_achat_unitaire_fcfa',
                'lp.date_achat', 'lp.statut as lot_statut',
                'l.nom as localite_nom',
                'a.nom as agent_nom', 'a.prenom as agent_prenom'
            )
            ->where('r.id', $id)
            ->first();

        if (!$recu) abort(404);

        $paiements = DB::table('paiements_fournisseurs')
            ->where('recu_fournisseur_id', $id)
            ->orderBy('date_paiement')
            ->get();

        $entreprise = DB::table('entreprises')->first();

        $pdf = Pdf::loadView('pdf.recu-fournisseur', compact('recu', 'paiements', 'entreprise'))
            ->setPaper([0, 0, 419.53, 595.28], 'portrait') // A5
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isRemoteEnabled'      => false,
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 150,
            ]);

        $filename = 'Recu-' . $recu->numero_recu . '.pdf';
        return $pdf->stream($filename);
    }
}