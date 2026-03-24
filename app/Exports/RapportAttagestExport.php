<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RapportAttagestExport implements FromCollection, WithHeadings, WithStyles
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        $rows = [];

        // Résumé
        $rows[] = ['RAPPORT ATTAGEST'];
        $rows[] = ['Période: ' . $this->periode['label']];
        $rows[] = [];
        $rows[] = ['RÉSUMÉ'];
        $rows[] = [
            'Achats: ' . $this->periode['achats']->sum('quantite_achat_kg') . ' kg / ' .
            number_format($this->periode['achats']->sum('montant_achat_total_fcfa'), 0, ',', ' ') . ' FCFA'
        ];
        $rows[] = [
            'Ventes: ' . $this->periode['ventes']->sum('quantite_vendue_kg') . ' kg / ' .
            number_format($this->periode['ventes']->sum('montant_vente_total_fcfa'), 0, ',', ' ') . ' FCFA'
        ];
        $rows[] = [];

        // Achats
        $rows[] = ['ACHATS PADDY'];
        $rows[] = ['Date', 'Fournisseur', 'Kg', 'Montant FCFA'];
        foreach ($this->periode['achats'] as $achat) {
            $rows[] = [
                $achat->date_achat?->format('d/m/Y'),
                $achat->fournisseur->nom ?? 'N/A',
                $achat->quantite_achat_kg,
                number_format($achat->montant_achat_total_fcfa, 0, ',', ' ')
            ];
        }
        $rows[] = [];

        // Ventes
        $rows[] = ['VENTES RIZ'];
        $rows[] = ['Date', 'Client', 'Kg', 'Montant FCFA'];
        foreach ($this->periode['ventes'] as $vente) {
            $rows[] = [
                $vente->date_vente?->format('d/m/Y'),
                $vente->client->nom ?? 'N/A',
                $vente->quantite_vendue_kg,
                number_format($vente->montant_vente_total_fcfa, 0, ',', ' ')
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Mettre en gras les lignes de titre
        $sheet->getStyle('A1:A500')->getFont()->setBold(true);
        return [];
    }
}
