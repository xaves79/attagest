<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PaiementsTraitement extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filtreStatut = '';

    // Modal paiement
    public bool   $showPaiement          = false;
    public ?int   $paiementTraitementId  = null;
    public string $paiementCode          = '';
    public string $paiementMontant       = '';
    public string $paiementMode          = 'especes';
    public string $paiementDate          = '';
    public string $paiementDescription   = '';
    public float  $soldeDu               = 0;

    // Modal historique
    public bool   $showHistorique        = false;
    public ?int   $historiqueId          = null;

    public string $successMessage = '';
    public string $errorMessage   = '';

    public function mount(): void
    {
        $this->paiementDate = now()->format('Y-m-d');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function ouvrirPaiement(int $id): void
    {
        $t = DB::table('traitements_client')->where('id', $id)->first();
        if (!$t) return;

        $dejaPaye = DB::table('paiements_traitements')
            ->where('traitement_id', $id)
            ->where('statut', 'paye')
            ->sum('montant_paye');

        $this->paiementTraitementId = $id;
        $this->paiementCode         = $t->code_traitement;
        $this->soldeDu              = max(0, (float)$t->montant_traitement_fcfa - (float)$dejaPaye);
        $this->paiementMontant      = (string)(int)$this->soldeDu;
        $this->paiementMode         = 'especes';
        $this->paiementDate         = now()->format('Y-m-d');
        $this->paiementDescription  = '';
        $this->showPaiement         = true;
        $this->errorMessage         = '';
    }

    public function enregistrerPaiement(): void
    {
        $this->errorMessage = '';
        $montant = (float)str_replace([' ', ','], ['', '.'], $this->paiementMontant);

        if ($montant <= 0) { $this->errorMessage = 'Montant invalide.'; return; }
        if ($montant > $this->soldeDu) {
            $this->errorMessage = "Montant ({$montant}) supérieur au solde ({$this->soldeDu})."; return;
        }

        $nextId  = (DB::table('paiements_traitements')->max('id') ?? 0) + 1;
        $numero  = 'PTRT-' . now()->format('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        DB::table('paiements_traitements')->insert([
            'numero_paiement' => $numero,
            'traitement_id'   => $this->paiementTraitementId,
            'montant_paye'    => $montant,
            'date_paiement'   => $this->paiementDate,
            'mode_paiement'   => $this->paiementMode,
            'description'     => $this->paiementDescription ?: null,
            'statut'          => 'paye',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Mettre à jour le statut du traitement si soldé
        $nouveauSolde = $this->soldeDu - $montant;
        if ($nouveauSolde <= 0) {
            DB::table('traitements_client')
                ->where('id', $this->paiementTraitementId)
                ->update(['statut' => 'termine']);
        }

        $this->successMessage = "Paiement {$numero} — " . number_format($montant, 0, ',', ' ') . " FCFA enregistré.";
        $this->showPaiement   = false;
        $this->resetPage();
    }

    public function facturer(int $id): void
    {
        $t = DB::table('traitements_client')->where('id', $id)->first();
        if (!$t) return;
        if ($t->facture_client_id) { $this->errorMessage = 'Déjà facturé.'; return; }
        if ($t->statut !== 'termine') { $this->errorMessage = 'Seuls les traitements terminés peuvent être facturés.'; return; }

        try {
            DB::transaction(function () use ($t) {
                $nextNum = (DB::table('factures_clients')->max('auto_numero') ?? 0) + 1;
                $numero  = 'FAC-' . now()->format('Y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                $fId = DB::table('factures_clients')->insertGetId([
                    'numero_facture' => $numero,
                    'auto_numero'    => $nextNum,
                    'client_id'      => $t->client_id,
                    'date_facture'   => now()->format('Y-m-d'),
                    'montant_total'  => $t->montant_traitement_fcfa,
                    'montant_paye'   => 0,
                    'solde_restant'  => $t->montant_traitement_fcfa,
                    'statut'         => 'credit',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                DB::table('traitements_client')->where('id', $t->id)->update(['facture_client_id' => $fId]);
            });
            $this->successMessage = 'Facture créée avec succès.';
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function ouvrirHistorique(int $id): void
    {
        $this->historiqueId    = $id;
        $this->showHistorique  = true;
    }

    public function render()
    {
        $traitements = DB::table('traitements_client as t')
            ->leftJoin('clients as c', 'c.id', '=', 't.client_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 't.variete_id')
            ->select(
                't.id', 't.code_traitement', 't.statut', 't.date_reception',
                't.facture_client_id',
                DB::raw('t.quantite_paddy_kg::text as qte_paddy'),
                DB::raw('t.montant_traitement_fcfa::text as montant'),
                'c.nom as client_nom', 'c.raison_sociale'
            )
            ->when($this->search, fn($q) =>
                $q->where('t.code_traitement', 'ilike', "%{$this->search}%")
                  ->orWhere('c.nom', 'ilike', "%{$this->search}%")
            )
            ->when($this->filtreStatut, fn($q) => $q->where('t.statut', $this->filtreStatut))
            ->orderByDesc('t.created_at')
            ->paginate(15);

        // Historique si modal ouvert
        $historique = collect();
        if ($this->showHistorique && $this->historiqueId) {
            $historique = DB::table('paiements_traitements')
                ->where('traitement_id', $this->historiqueId)
                ->orderByDesc('date_paiement')
                ->get();
        }

        $clients = DB::table('clients')->orderBy('nom')->select('id', 'nom', 'raison_sociale')->get();

        return view('livewire.paiements-traitement.index', compact('traitements', 'historique', 'clients'))
            ->layout('layouts.app');
    }
}