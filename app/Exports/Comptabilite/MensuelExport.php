<?php

namespace App\Exports\Comptabilite;

use App\Models\EcritureComptable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MensuelExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $debut;
    protected $fin;

    public function __construct()
    {
        $now = now();
        $this->debut = $now->startOfMonth()->format('Y-m-d');
        $this->fin   = $now->endOfMonth()->format('Y-m-d');
    }

    public function collection()
    {
        return EcritureComptable::whereBetween('date_ecriture', [$this->debut, $this->fin])
            ->with(['compteDebit', 'compteCredit', 'pieceComptable'])
            ->orderBy('date_ecriture')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Code écriture',
            'Date écriture',
            'Libellé',
            'Compte débit',
            'Montant débit (FCFA)',
            'Compte crédit',
            'Montant crédit (FCFA)',
            'Pièce comptable',
            'Validé',
        ];
    }

    public function map($row): array
    {
        return [
            $row->code_ecriture,
            $row->date_ecriture?->format('d/m/Y'),
            $row->libelle,
            $row->compteDebit?->libelle ?? '-',
            number_format($row->montant_debit, 0, ',', ' '),
            $row->compteCredit?->libelle ?? '-',
            number_format($row->montant_credit, 0, ',', ' '),
            $row->pieceComptable?->libelle ?? '-',
            $row->valide ? 'Oui' : 'Non',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}