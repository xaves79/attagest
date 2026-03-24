<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class BilansGlobauxExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $periode;
    protected $sectionRows = [];
    protected $globalData;

    public function __construct($periode)
    {
        $this->periode = $periode;
        $this->globalData = $periode['global'] ?? [];
    }

    public function collection()
    {
        $rows = [];
        $currentRow = 1;

        // EN-TÊTE AVEC LOGO SIMULÉ
        $this->sectionRows['header'] = $currentRow;
        $rows[] = ['ATTAGEST'];
        $currentRow++;
        $rows[] = ['BILAN GLOBAL'];
        $currentRow++;
        $rows[] = ['Période: ' . ($this->periode['label'] ?? 'Non définie')];
        $currentRow += 2;

        // INDICATEURS CLÉS - MISE EN PAGE HORIZONTALE
        $this->sectionRows['kpis'] = $currentRow;
        $rows[] = ['INDICATEURS CLÉS'];
        $currentRow++;
        
        // Calculer le taux de transformation de manière sécurisée
        $paddyTraite = $this->globalData['paddy_traite'] ?? 0;
        $rizBlanc = $this->globalData['riz_blanc'] ?? 0;
        $tauxTransformation = ($paddyTraite > 0) ? ($rizBlanc / $paddyTraite) * 100 : 0;
        
        // Création d'une ligne avec 2 indicateurs par rangée pour un look moderne
        $kpiData = [
            [
                'Paddy traité',
                number_format($paddyTraite, 2, ',', ' ') . ' kg',
                'Riz blanc',
                number_format($rizBlanc, 2, ',', ' ') . ' kg'
            ],
            [
                'Ventes totales',
                number_format($this->globalData['ventes_riz'] ?? 0, 2, ',', ' ') . ' kg',
                'Chiffre d\'affaires',
                number_format($this->globalData['ventes_montant'] ?? 0, 0, ',', ' ') . ' FCFA'
            ],
            [
                'Paiements reçus',
                number_format($this->globalData['paiements'] ?? 0, 0, ',', ' ') . ' FCFA',
                'Taux de transformation',
                number_format($tauxTransformation, 1, ',', ' ') . ' %'
            ]
        ];
        
        foreach ($kpiData as $kpi) {
            $rows[] = $kpi;
            $currentRow++;
        }
        $currentRow += 2;

        // TRAITEMENT PAR VARIÉTÉ
        $this->sectionRows['traitement'] = $currentRow;
        $rows[] = ['PRODUCTION PAR VARIÉTÉ'];
        $currentRow++;
        $rows[] = ['Variété', 'Paddy (kg)', 'Riz blanc (kg)', 'Rendement (%)'];
        $currentRow++;
        
        $traitements = $this->periode['parVarieteTraitements'] ?? [];
        foreach ($traitements as $v) {
            $paddy = $v->total_paddy ?? 0;
            $riz = $v->total_riz_blanc ?? 0;
            $rendement = ($paddy > 0) ? ($riz / $paddy) * 100 : 0;
            
            $rows[] = [
                $v->variete ?? 'N/A',
                number_format($paddy, 2, ',', ' '),
                number_format($riz, 2, ',', ' '),
                number_format($rendement, 1, ',', ' ') . ' %'
            ];
            $currentRow++;
        }
        $currentRow += 2;

        // PERFORMANCE COMMERCIALE
        $this->sectionRows['commercial'] = $currentRow;
        $rows[] = ['PERFORMANCE COMMERCIALE'];
        $currentRow += 2;

        // VENTES PAR VARIÉTÉ
        $rows[] = ['VENTES PAR VARIÉTÉ'];
        $currentRow++;
        $rows[] = ['Variété', 'Quantité vendue', 'Montant', 'Prix moyen/kg'];
        $currentRow++;
        
        $ventesVariete = $this->periode['parVarieteVentes'] ?? [];
        foreach ($ventesVariete as $v) {
            $quantite = $v->total_vente ?? 0;
            $montant = $v->total_montant ?? 0;
            $prixMoyen = ($quantite > 0) ? ($montant / $quantite) : 0;
            
            $rows[] = [
                $v->variete ?? 'N/A',
                number_format($quantite, 2, ',', ' ') . ' kg',
                number_format($montant, 0, ',', ' ') . ' FCFA',
                number_format($prixMoyen, 0, ',', ' ') . ' FCFA'
            ];
            $currentRow++;
        }
        $currentRow += 2;

        // VENTES PAR CLIENT
        $rows[] = ['CLIENTS PRINCIPAUX'];
        $currentRow++;
        $rows[] = ['Client', 'Transactions', 'Volume total', 'Chiffre d\'affaires'];
        $currentRow++;
        
        // Trier par montant décroissant pour mettre en avant les meilleurs clients
        $clients = collect($this->periode['parClient'] ?? [])
            ->sortByDesc(function($item) {
                return $item->total_montant ?? 0;
            })
            ->take(10); // Limiter aux 10 premiers clients
        
        foreach ($clients as $c) {
            $rows[] = [
                $c->client ?? 'N/A',
                $c->nb_ventes ?? 0,
                number_format($c->total_vente ?? 0, 2, ',', ' ') . ' kg',
                number_format($c->total_montant ?? 0, 0, ',', ' ') . ' FCFA'
            ];
            $currentRow++;
        }
        $currentRow += 2;

        // APPROVISIONNEMENT
        $this->sectionRows['approvisionnement'] = $currentRow;
        $rows[] = ['APPROVISIONNEMENT'];
        $currentRow++;
        $rows[] = ['Fournisseur', 'Commandes', 'Quantité achetée', 'Montant total'];
        $currentRow++;
        
        // Trier par montant décroissant
        $fournisseurs = collect($this->periode['parFournisseur'] ?? [])
            ->sortByDesc(function($item) {
                return $item->total_montant ?? 0;
            });
        
        foreach ($fournisseurs as $f) {
            $rows[] = [
                $f->fournisseur ?? 'N/A',
                $f->nb_achats ?? 0,
                number_format($f->total_achat ?? 0, 2, ',', ' ') . ' kg',
                number_format($f->total_montant ?? 0, 0, ',', ' ') . ' FCFA'
            ];
            $currentRow++;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Bilan ' . date('m-Y');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 22,
            'C' => 22,
            'D' => 22,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style global
        $sheet->getStyle('A:D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:D')->getFont()->setName('Calibri');
        $sheet->getStyle('A:D')->getFont()->setSize(11);

        // EN-TÊTE
        if (isset($this->sectionRows['header'])) {
            $row = $this->sectionRows['header'];
            
            // Nom de l'entreprise
            $sheet->mergeCells("A{$row}:D{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 20,
                    'color' => ['rgb' => '1A5276']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(28);

            // Titre "BILAN GLOBAL"
            $sheet->mergeCells("A" . ($row + 1) . ":D" . ($row + 1));
            $sheet->getStyle("A" . ($row + 1))->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => '2C3E50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);

            // Période
            $sheet->mergeCells("A" . ($row + 2) . ":D" . ($row + 2));
            $sheet->getStyle("A" . ($row + 2))->applyFromArray([
                'font' => [
                    'size' => 12,
                    'color' => ['rgb' => '7F8C8D']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);
        }

        // INDICATEURS CLÉS - Design minimaliste
        if (isset($this->sectionRows['kpis'])) {
            $row = $this->sectionRows['kpis'];
            
            // Titre de section
            $sheet->mergeCells("A{$row}:D{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '2C3E50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(24);

            // Style pour les indicateurs (pairs/impairs)
            for ($i = 1; $i <= 3; $i++) {
                $currentRow = $row + $i;
                $color = $i % 2 == 0 ? 'F8F9F9' : 'FFFFFF';
                
                $sheet->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $color]
                    ]
                ]);

                // Style pour les libellés (colonnes A et C)
                $sheet->getStyle("A{$currentRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '34495E']
                    ]
                ]);
                
                $sheet->getStyle("C{$currentRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '34495E']
                    ]
                ]);

                // Style pour les valeurs (colonnes B et D)
                $sheet->getStyle("B{$currentRow}")->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '1A5276']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
                
                $sheet->getStyle("D{$currentRow}")->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '1A5276']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
            }
        }

        // STYLE DES TABLEAUX
        $tables = [
            'traitement' => 'PRODUCTION PAR VARIÉTÉ',
            'commercial' => 'PERFORMANCE COMMERCIALE'
        ];

        foreach ($tables as $key => $title) {
            if (isset($this->sectionRows[$key])) {
                $startRow = $this->sectionRows[$key];
                
                // Titre de section
                $sheet->mergeCells("A{$startRow}:D{$startRow}");
                $sheet->getStyle("A{$startRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '2C3E50']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);
                $sheet->getRowDimension($startRow)->setRowHeight(26);

                // En-têtes de tableau (ligne suivante)
                $headerRow = $startRow + 1;
                $sheet->getStyle("A{$headerRow}:D{$headerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '5D6D7E']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ]
                ]);

                // Ajuster l'alignement des colonnes numériques
                $sheet->getStyle('B:D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }
        }

        // STYLE POUR "APPROVISIONNEMENT"
        if (isset($this->sectionRows['approvisionnement'])) {
            $row = $this->sectionRows['approvisionnement'];
            
            // Titre de section
            $sheet->mergeCells("A{$row}:D{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '2C3E50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);
            $sheet->getRowDimension($row)->setRowHeight(26);

            // En-têtes
            $headerRow = $row + 1;
            $sheet->getStyle("A{$headerRow}:D{$headerRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '5D6D7E']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ]);
        }

        // Ajouter un espacement minimal entre les lignes de données
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Ajouter un filtre sur la première ligne
                $event->sheet->setAutoFilter('A4:D4');
                
                // Geler la première ligne (en-tête)
                $event->sheet->freezePane('A2');
            },
        ];
    }
}