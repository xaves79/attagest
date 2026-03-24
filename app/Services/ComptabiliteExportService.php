<?php

namespace App\Services;

use App\Models\EcritureComptable;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ComptabiliteExportService implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $periode;
    protected $debut;
    protected $fin;

    public function __construct(string $periode)
    {
        $this->periode = $periode;
        $this->setDates();
    }

    private function setDates()
    {
        $now = now();

        switch ($this->periode) {
            case 'journalier':
                $this->debut = $now->startOfDay();
                $this->fin   = $now->endOfDay();
                break;
            case 'hebdomadaire':
                $this->debut = $now->startOfWeek();
                $this->fin   = $now->endOfWeek();
                break;
            case 'mensuel':
                $this->debut = $now->startOfMonth();
                $this->fin   = $now->endOfMonth();
                break;
            case 'trimestriel':
                $this->debut = $now->startOfQuarter();
                $this->fin   = $now->endOfQuarter();
                break;
            case 'annuel':
                $this->debut = $now->startOfYear();
                $this->fin   = $now->endOfYear();
                break;
            default:
                $this->debut = $now->startOfDay();
                $this->fin   = $now->endOfDay();
        }
    }

    public function collection()
    {
        return EcritureComptable::whereBetween('date_ecriture', [
            $this->debut->format('Y-m-d'),
            $this->fin->format('Y-m-d'),
        ])
        ->with(['compteDebit', 'compteCredit'])
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
            number_format($row->montant_debit, 2, ',', ' '),
            $row->compteCredit?->libelle ?? '-',
            number_format($row->montant_credit, 2, ',', ' '),
            $row->piece_comptable ?? '-',
            $row->valide ? 'Oui' : 'Non',
        ];
    }

    public function title(): string
    {
        return 'Journal comptable - ' . ucfirst($this->periode);
    }
}
