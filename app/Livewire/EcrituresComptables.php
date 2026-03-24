<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\EcritureComptable;
use App\Models\Compte;
use App\Models\PieceComptable;
use App\Services\EcritureComptableService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Comptabilite\JournalierExport;
use App\Exports\Comptabilite\MensuelExport;
use App\Exports\Comptabilite\AnnuelExport;

#[Layout('components.layouts.app')]
class EcrituresComptables extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $date_debut = '';
    public $date_fin = '';
    public $valide_filter = '';
    public $filter_compte_debit = '';
    public $filter_compte_credit = '';
    public $filter_piece = '';

    public $form = [
        'id'              => null,
        'code_ecriture'   => '',
        'date_ecriture'   => '',
        'libelle'         => '',
        'compte_debit'    => '',
        'montant_debit'   => '',
        'compte_credit'   => '',
        'montant_credit'  => '',
        'piece_comptable' => '',
        'valide'          => false,
    ];

    public $comptes;
    public $pieces;

    public function mount()
    {
        $this->comptes = Compte::orderBy('code_compte')->get();
        $this->pieces  = PieceComptable::orderBy('libelle')->get();
    }

    // Réinitialisation de la page lors du changement des filtres
    public function updatedSearch() { $this->resetPage(); }
    public function updatedDateDebut() { $this->resetPage(); }
    public function updatedDateFin() { $this->resetPage(); }
    public function updatedValideFilter() { $this->resetPage(); }
    public function updatedFilterCompteDebit() { $this->resetPage(); }
    public function updatedFilterCompteCredit() { $this->resetPage(); }
    public function updatedFilterPiece() { $this->resetPage(); }

    /**
     * Calcule les statistiques en fonction des filtres actuels
     */
    public function getStatsProperty()
    {
        $query = EcritureComptable::query();
        $this->applyFilters($query);

        return [
            'total'    => (clone $query)->count(),
            'validees' => (clone $query)->where('valide', true)->count(),
            'debit'    => (clone $query)->sum('montant_debit'),
            'credit'   => (clone $query)->sum('montant_credit'),
        ];
    }

    /**
     * Rendu principal avec la liste paginée
     */
    public function render()
    {
        $ecritures = EcritureComptable::with(['compteDebit', 'compteCredit', 'pieceComptable']);
        $this->applyFilters($ecritures);

        $ecritures = $ecritures->orderBy('date_ecriture', 'desc')
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);

        return view('livewire.ecritures-comptables.index', [
            'ecritures' => $ecritures,
            'stats'     => $this->stats,
            'comptes'   => $this->comptes,
            'pieces'    => $this->pieces,
        ]);
    }

    /**
     * Applique tous les filtres à la requête donnée
     */
    private function applyFilters($query)
    {
        if ($this->date_debut) {
            $query->whereDate('date_ecriture', '>=', $this->date_debut);
        }

        if ($this->date_fin) {
            $query->whereDate('date_ecriture', '<=', $this->date_fin);
        }

        if ($this->valide_filter !== '') {
            $query->where('valide', $this->valide_filter);
        }

        if ($this->filter_compte_debit) {
            $query->where('compte_debit', $this->filter_compte_debit);
        }

        if ($this->filter_compte_credit) {
            $query->where('compte_credit', $this->filter_compte_credit);
        }

        if ($this->filter_piece) {
            $query->where('piece_comptable', $this->filter_piece);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code_ecriture', 'like', "%{$this->search}%")
                  ->orWhere('libelle', 'like', "%{$this->search}%")
                  ->orWhere('piece_comptable', 'like', "%{$this->search}%");
            });
        }
    }

    /**
     * Ouvre le modal de création
     */
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    /**
     * Ouvre le modal d'édition
     */
    public function edit($id)
    {
        $ecriture = EcritureComptable::findOrFail($id);
        $this->form = $ecriture->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    /**
     * Ouvre le modal en visualisation seule
     */
    public function show($id)
    {
        $ecriture = EcritureComptable::findOrFail($id);
        $this->form = $ecriture->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    /**
     * Supprime une écriture
     */
    public function delete($id)
    {
        EcritureComptable::findOrFail($id)->delete();
        session()->flash('message', 'Écriture comptable supprimée avec succès.');
        $this->resetPage();
    }

    /**
     * Sauvegarde (création ou mise à jour)
     */
    public function save()
    {
        $this->validate([
            'form.date_ecriture'   => 'required|date',
            'form.libelle'         => 'required|string|max:200',
            'form.compte_debit'    => 'required|exists:comptes,code_compte',
            'form.montant_debit'   => 'required|numeric|min:0.01',
            'form.compte_credit'   => 'required|exists:comptes,code_compte',
            'form.montant_credit'  => 'required|numeric|min:0.01',
            'form.piece_comptable' => 'nullable|string|max:50',
            'form.valide'          => 'required|boolean',
        ]);

        $data = [
            'code_ecriture'   => $this->form['id'] ? $this->form['code_ecriture'] : (new EcritureComptableService())->generateCodeEcriture(),
            'date_ecriture'   => $this->form['date_ecriture'],
            'libelle'         => $this->form['libelle'],
            'compte_debit'    => $this->form['compte_debit'],
            'montant_debit'   => $this->form['montant_debit'],
            'compte_credit'   => $this->form['compte_credit'],
            'montant_credit'  => $this->form['montant_credit'],
            'piece_comptable' => $this->form['piece_comptable'],
            'valide'          => $this->form['valide'],
        ];

        EcritureComptable::updateOrCreate(
            ['id' => $this->form['id']],
            $data
        );

        session()->flash('message', $this->form['id'] ? 'Écriture comptable mise à jour.' : 'Écriture comptable créée.');
        $this->showModal = false;
        $this->resetPage();
    }

    /**
     * Réinitialise le formulaire
     */
    public function resetForm()
    {
        $this->form = [
            'id'              => null,
            'code_ecriture'   => (new EcritureComptableService())->generateCodeEcriture(),
            'date_ecriture'   => now()->format('Y-m-d'),
            'libelle'         => '',
            'compte_debit'    => '',
            'montant_debit'   => '',
            'compte_credit'   => '',
            'montant_credit'  => '',
            'piece_comptable' => '',
            'valide'          => false,
        ];
    }

    // ======================  EXPORTS  ======================

    public function exportJournalierPdf()
    {
        $ecritures = $this->getEcrituresByPeriode('journalier');
        $pdf = Pdf::loadView('exports.comptabilite.pdf', [
            'ecritures' => $ecritures,
            'periode'   => 'journalier',
            'title'     => 'Journal journalier',
        ]);
        return response()->streamDownload(fn () => print($pdf->output()), "journal_journalier.pdf");
    }

    public function exportJournalierExcel()
    {
        return Excel::download(new JournalierExport(), "journal_journalier.xlsx");
    }

    public function exportMensuelPdf()
    {
        $ecritures = $this->getEcrituresByPeriode('mensuel');
        $pdf = Pdf::loadView('exports.comptabilite.pdf', [
            'ecritures' => $ecritures,
            'periode'   => 'mensuel',
            'title'     => 'Journal mensuel',
        ]);
        return response()->streamDownload(fn () => print($pdf->output()), "journal_mensuel.pdf");
    }

    public function exportMensuelExcel()
    {
        return Excel::download(new MensuelExport(), "journal_mensuel.xlsx");
    }

    public function exportAnnuelPdf()
    {
        $ecritures = $this->getEcrituresByPeriode('annuel');
        $pdf = Pdf::loadView('exports.comptabilite.pdf', [
            'ecritures' => $ecritures,
            'periode'   => 'annuel',
            'title'     => 'Journal annuel',
        ]);
        return response()->streamDownload(fn () => print($pdf->output()), "journal_annuel.pdf");
    }

    public function exportAnnuelExcel()
    {
        return Excel::download(new AnnuelExport(), "journal_annuel.xlsx");
    }

    /**
     * Récupère les écritures d'une période (basée sur la date du jour)
     */
    private function getEcrituresByPeriode(string $periode)
    {
        $now = now();
        switch ($periode) {
            case 'journalier':
                $debut = $now->startOfDay()->format('Y-m-d');
                $fin   = $now->endOfDay()->format('Y-m-d');
                break;
            case 'mensuel':
                $debut = $now->startOfMonth()->format('Y-m-d');
                $fin   = $now->endOfMonth()->format('Y-m-d');
                break;
            case 'annuel':
                $debut = $now->startOfYear()->format('Y-m-d');
                $fin   = $now->endOfYear()->format('Y-m-d');
                break;
            default:
                $debut = $now->startOfDay()->format('Y-m-d');
                $fin   = $now->endOfDay()->format('Y-m-d');
        }

        return EcritureComptable::whereBetween('date_ecriture', [$debut, $fin])
            ->with(['compteDebit', 'compteCredit', 'pieceComptable'])
            ->orderBy('date_ecriture')
            ->get();
    }
}