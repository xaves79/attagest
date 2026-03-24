<?php

namespace App\Http\Controllers;

use App\Models\FactureClient;
use App\Models\CommandeVente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    /**
     * Afficher / télécharger la facture en PDF (format A5).
     */
    public function imprimer(int $id)
    {
        $facture = FactureClient::with([
            'client.localite',
            'commande.lignes.sac',
            'commande.agent',
            'commande.pointVente',
        ])->findOrFail($id);

        // Si la relation commande ne charge rien, chercher via commandes_vente
        if (!$facture->commande) {
            $commande = CommandeVente::with([
                'lignes.sac',
                'agent',
                'pointVente',
            ])->where('facture_id', $id)->first();
            $facture->setRelation('commande', $commande);
        }

        $entreprise = \App\Models\Entreprise::first();

        $pdf = Pdf::loadView('pdf.facture', compact('facture', 'entreprise'))
            ->setPaper([0, 0, 419.53, 595.28], 'portrait') // A5 en points PDF exacts
            ->setOptions([
                'defaultFont'        => 'DejaVu Sans',
                'isRemoteEnabled'    => false,
                'isHtml5ParserEnabled' => true,
                'dpi'                => 150,
            ]);

        $filename = 'Facture-' . $facture->numero_facture . '.pdf';

        return $pdf->stream($filename);
    }
}