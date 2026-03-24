<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Comptabilite extends Component
{
    use WithPagination;

    public string $onglet        = 'journal';
    public string $search        = '';
    public string $filtreCompte  = '';
    public string $filtreDateDeb = '';
    public string $filtreDateFin = '';

    // Formulaire écriture
    public bool   $showModal        = false;
    public ?int   $formId           = null;
    public string $formDate         = '';
    public string $formLibelle      = '';
    public string $formCompteDebit  = '';
    public string $formCompteCredit = '';
    public string $formMontant      = '';
    public string $formPiece        = '';

    public string $successMessage = '';
    public string $errorMessage   = '';

    public function mount(): void
    {
        $this->formDate      = now()->format('Y-m-d');
        $this->filtreDateDeb = now()->startOfMonth()->format('Y-m-d');
        $this->filtreDateFin = now()->format('Y-m-d');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    private function n(string $v): float
    {
        $v = preg_replace('/[\x20\xc2\xa0\xe2\x80\xaf]+/u', '', $v);
        return (float)str_replace(',', '.', $v);
    }

    // ── Journal ──────────────────────────────────────────────────────
    public function nouvelleEcriture(): void
    {
        $this->formId           = null;
        $this->formDate         = now()->format('Y-m-d');
        $this->formLibelle      = '';
        $this->formCompteDebit  = '';
        $this->formCompteCredit = '';
        $this->formMontant      = '';
        $this->formPiece        = '';
        $this->showModal        = true;
        $this->errorMessage     = '';
    }

    public function validerEcriture(): void
    {
        $this->errorMessage = '';
        $montant = $this->n($this->formMontant);

        if (!$this->formDate)         { $this->errorMessage = 'Date requise.'; return; }
        if (!$this->formLibelle)      { $this->errorMessage = 'Libellé requis.'; return; }
        if (!$this->formCompteDebit)  { $this->errorMessage = 'Compte débit requis.'; return; }
        if (!$this->formCompteCredit) { $this->errorMessage = 'Compte crédit requis.'; return; }
        if ($montant <= 0)            { $this->errorMessage = 'Montant invalide.'; return; }
        if ($this->formCompteDebit === $this->formCompteCredit) {
            $this->errorMessage = 'Les comptes débit et crédit doivent être différents.'; return;
        }

        $nextId = (DB::table('ecritures_comptables')->max('id') ?? 0) + 1;
        $code   = 'ECR-' . now()->format('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        try {
            DB::transaction(function () use ($montant, $code) {
                if ($this->formId) {
                    // Annuler l'ancienne écriture sur les soldes
                    $old = DB::table('ecritures_comptables')->where('id', $this->formId)->first();
                    if ($old) {
                        DB::table('comptes')->where('code_compte', $old->compte_debit)
                            ->decrement('solde_debit', $old->montant_debit);
                        DB::table('comptes')->where('code_compte', $old->compte_credit)
                            ->decrement('solde_credit', $old->montant_credit);
                    }
                    DB::table('ecritures_comptables')->where('id', $this->formId)->update([
                        'date_ecriture'  => $this->formDate,
                        'libelle'        => $this->formLibelle,
                        'compte_debit'   => $this->formCompteDebit,
                        'montant_debit'  => $montant,
                        'compte_credit'  => $this->formCompteCredit,
                        'montant_credit' => $montant,
                        'piece_comptable'=> $this->formPiece ?: null,
                    ]);
                } else {
                    DB::table('ecritures_comptables')->insert([
                        'code_ecriture'  => $code,
                        'date_ecriture'  => $this->formDate,
                        'libelle'        => $this->formLibelle,
                        'compte_debit'   => $this->formCompteDebit,
                        'montant_debit'  => $montant,
                        'compte_credit'  => $this->formCompteCredit,
                        'montant_credit' => $montant,
                        'piece_comptable'=> $this->formPiece ?: null,
                        'valide'         => true,
                        'created_at'     => now(),
                    ]);
                }

                // Mettre à jour les soldes
                DB::table('comptes')->where('code_compte', $this->formCompteDebit)
                    ->increment('solde_debit', $montant);
                DB::table('comptes')->where('code_compte', $this->formCompteCredit)
                    ->increment('solde_credit', $montant);
            });

            $this->successMessage = 'Écriture enregistrée : ' . $code;
            $this->showModal = false;
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function editerEcriture(int $id): void
    {
        $e = DB::table('ecritures_comptables')->where('id', $id)->first();
        if (!$e) return;
        $this->formId           = $e->id;
        $this->formDate         = $e->date_ecriture;
        $this->formLibelle      = $e->libelle;
        $this->formCompteDebit  = $e->compte_debit;
        $this->formCompteCredit = $e->compte_credit;
        $this->formMontant      = (string)(int)$e->montant_debit;
        $this->formPiece        = $e->piece_comptable ?? '';
        $this->showModal        = true;
        $this->errorMessage     = '';
    }

    public function supprimerEcriture(int $id): void
    {
        $e = DB::table('ecritures_comptables')->where('id', $id)->first();
        if (!$e) return;
        DB::table('comptes')->where('code_compte', $e->compte_debit)->decrement('solde_debit', $e->montant_debit);
        DB::table('comptes')->where('code_compte', $e->compte_credit)->decrement('solde_credit', $e->montant_credit);
        DB::table('ecritures_comptables')->where('id', $id)->delete();
        $this->successMessage = 'Écriture supprimée.';
    }

    public function render()
    {
        $comptes = DB::table('comptes')->orderBy('code_compte')->get();
        $pieces  = DB::table('pieces_comptables')->orderBy('code')->get();

        // Journal
        $ecritures = DB::table('ecritures_comptables as e')
            ->leftJoin('comptes as cd', 'cd.code_compte', '=', 'e.compte_debit')
            ->leftJoin('comptes as cc', 'cc.code_compte', '=', 'e.compte_credit')
            ->select(
                'e.id', 'e.code_ecriture', 'e.date_ecriture', 'e.libelle',
                'e.compte_debit', 'cd.libelle as libelle_debit',
                'e.compte_credit', 'cc.libelle as libelle_credit',
                DB::raw('e.montant_debit::text as montant'),
                'e.piece_comptable', 'e.valide'
            )
            ->when($this->search, fn($q) =>
                $q->where('e.libelle', 'ilike', "%{$this->search}%")
                  ->orWhere('e.code_ecriture', 'ilike', "%{$this->search}%")
            )
            ->when($this->filtreCompte, fn($q) =>
                $q->where('e.compte_debit', $this->filtreCompte)
                  ->orWhere('e.compte_credit', $this->filtreCompte)
            )
            ->when($this->filtreDateDeb, fn($q) => $q->where('e.date_ecriture', '>=', $this->filtreDateDeb))
            ->when($this->filtreDateFin, fn($q) => $q->where('e.date_ecriture', '<=', $this->filtreDateFin))
            ->orderByDesc('e.date_ecriture')
            ->orderByDesc('e.id')
            ->paginate(20);

        // Grand livre — soldes par compte
        $grandLivre = DB::table('comptes')
            ->select('code_compte', 'libelle', 'type_compte',
                     DB::raw('solde_debit::text as solde_debit'),
                     DB::raw('solde_credit::text as solde_credit'))
            ->orderBy('code_compte')
            ->get()
            ->map(function ($c) {
                $c->solde_net = (float)$c->solde_debit - (float)$c->solde_credit;
                return $c;
            });

        // Compte de résultat
        $charges  = $grandLivre->filter(fn($c) => $c->type_compte === 'charge');
        $produits = $grandLivre->filter(fn($c) => $c->type_compte === 'produit');
        $totalCharges  = $charges->sum(fn($c) => (float)$c->solde_debit);
        $totalProduits = $produits->sum(fn($c) => (float)$c->solde_credit);
        $resultat      = $totalProduits - $totalCharges;

        // Balance
        $totalDebit  = $ecritures->sum(fn($e) => (float)$e->montant) + 
                       DB::table('ecritures_comptables')
                           ->when($this->filtreDateDeb, fn($q) => $q->where('date_ecriture', '>=', $this->filtreDateDeb))
                           ->when($this->filtreDateFin, fn($q) => $q->where('date_ecriture', '<=', $this->filtreDateFin))
                           ->sum('montant_debit');

        return view('livewire.comptabilite.index', compact(
            'ecritures', 'comptes', 'pieces', 'grandLivre',
            'charges', 'produits', 'totalCharges', 'totalProduits', 'resultat'
        ))->layout('layouts.app');
    }
}