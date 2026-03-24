<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class TraitementsClients extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filtreStatut = '';

    // Formulaire — TOUT en string pour éviter les conversions Livewire
    public bool   $showModal = false;
    public bool   $viewMode  = false;

    public ?int   $formId                    = null;
    public string $formCode                  = '';
    public mixed  $formClientId              = '';
    public mixed  $formAgentId               = '';
    public mixed  $formVarieteId             = '';
    public mixed  $formLocaliteId            = '';
    public string $formQuantitePaddy         = '';
    public string $formDateReception         = '';
    public string $formQuantiteRizBlanc      = '';
    public string $formQuantiteSon           = '';
    public string $formTauxRendement         = '';
    public string $formPrixParKg             = '';
    public string $formMontantTraitement     = '';
    public string $formStatut                = 'en_attente';
    public string $formObservations          = '';

    // Paiements
    public bool   $showPaiement              = false;
    public ?int   $paiementTraitementId      = null;
    public string $paiementMontant           = '';
    public string $paiementMode              = 'especes';
    public string $paiementDate              = '';
    public string $paiementDescription       = '';

    public string $successMessage = '';
    public string $errorMessage   = '';

    public function mount(): void
    {
        $this->formDateReception = now()->format('Y-m-d');
        $this->paiementDate      = now()->format('Y-m-d');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    // ── Calculs auto ─────────────────────────────────────────────────
    public function updatedFormQuantitePaddy(): void    { $this->recalc(); }
    public function updatedFormQuantiteRizBlanc(): void { $this->recalc(); }
    public function updatedFormPrixParKg(): void        { $this->recalc(); }

    private function n(string $v): float
    {
        // Nettoyer : espace normal, insécable (U+00A0), fine (U+202F), virgule → point
        $v = preg_replace('/[\x20\xc2\xa0\xe2\x80\xaf\xe2\x80\x89]+/u', '', $v);
        $v = str_replace(',', '.', $v);
        return (float)$v;
    }

    private function recalc(): void
    {
        $paddy = $this->n($this->formQuantitePaddy);
        $blanc = $this->n($this->formQuantiteRizBlanc);
        $prix  = $this->n($this->formPrixParKg);

        if ($paddy > 0) {
            $this->formQuantiteSon     = (string)max(0, round($paddy - $blanc, 2));
            $this->formTauxRendement   = $blanc > 0 ? (string)round($blanc / $paddy * 100, 2) : '';
            $this->formMontantTraitement = $prix > 0 ? (string)round($paddy * $prix) : '';
        }
    }

    // ── CRUD ─────────────────────────────────────────────────────────
    public function create(): void
    {
        $this->resetModalForm();
        $date   = now()->format('Ymd');
        $prefix = "TRT-{$date}-";
        $last   = DB::table('traitements_client')
            ->where('code_traitement', 'like', "{$prefix}%")
            ->orderBy('code_traitement', 'desc')
            ->value('code_traitement');
        $num = $last ? ((int)substr($last, -3) + 1) : 1;
        $this->formCode    = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->showModal   = true;
        $this->viewMode    = false;
        $this->errorMessage = '';
    }

    public function edit(int $id): void
    {
        $this->charger($id);
        $this->showModal  = true;
        $this->viewMode   = false;
        $this->errorMessage = '';
    }

    public function show(int $id): void
    {
        $this->charger($id);
        $this->showModal = true;
        $this->viewMode  = true;
    }

    private function charger(int $id): void
    {
        $t = DB::selectOne("
            SELECT id, code_traitement, client_id, agent_id, variete_id, localite_id,
                   quantite_paddy_kg::text        AS qte_paddy,
                   date_reception::text           AS date_rec,
                   quantite_riz_blanc_kg::text    AS qte_blanc,
                   quantite_son_kg::text          AS qte_son,
                   taux_rendement::text           AS taux,
                   prix_traitement_par_kg::text   AS prix,
                   montant_traitement_fcfa::text  AS montant,
                   statut, observations
            FROM traitements_client WHERE id = ?
        ", [$id]);
        if (!$t) return;

        $this->formId                = $t->id;
        $this->formCode              = $t->code_traitement;
        $this->formClientId          = $t->client_id;
        $this->formAgentId           = $t->agent_id;
        $this->formVarieteId         = $t->variete_id;
        $this->formLocaliteId        = $t->localite_id;
        $this->formQuantitePaddy     = rtrim(rtrim($t->qte_paddy, '0'), '.');
        $this->formDateReception     = $t->date_rec ?? '';
        $this->formQuantiteRizBlanc  = rtrim(rtrim($t->qte_blanc ?? '', '0'), '.');
        $this->formQuantiteSon       = rtrim(rtrim($t->qte_son ?? '', '0'), '.');
        $this->formTauxRendement     = rtrim(rtrim($t->taux ?? '', '0'), '.');
        $this->formPrixParKg         = rtrim(rtrim($t->prix ?? '', '0'), '.');
        $this->formMontantTraitement = rtrim(rtrim($t->montant ?? '', '0'), '.');
        $this->formStatut            = $t->statut;
        $this->formObservations      = $t->observations ?? '';
    }

    public function save(): void
    {
        $this->errorMessage = '';

        if (!$this->formClientId) { $this->errorMessage = 'Client requis.'; return; }
        if (!$this->formDateReception) { $this->errorMessage = 'Date requise.'; return; }

        $paddy = $this->n($this->formQuantitePaddy);
        if ($paddy <= 0) { $this->errorMessage = 'Quantité paddy invalide.'; return; }

        $this->recalc();

        $data = [
            'code_traitement'         => $this->formCode,
            'client_id'               => $this->formClientId ?: null,
            'agent_id'                => $this->formAgentId ?: null,
            'variete_id'              => $this->formVarieteId ?: null,
            'localite_id'             => $this->formLocaliteId ?: null,
            'quantite_paddy_kg'       => $this->n($this->formQuantitePaddy),
            'date_reception'          => $this->formDateReception,
            'quantite_riz_blanc_kg'   => $this->n($this->formQuantiteRizBlanc) ?: null,
            'quantite_son_kg'         => $this->n($this->formQuantiteSon) ?: null,
            'taux_rendement'          => $this->n($this->formTauxRendement) ?: null,
            'prix_traitement_par_kg'  => $this->n($this->formPrixParKg) ?: null,
            'montant_traitement_fcfa' => $this->n($this->formMontantTraitement) ?: null,
            'statut'                  => $this->formStatut,
            'observations'            => $this->formObservations ?: null,
        ];

        if ($this->formId) {
            DB::table('traitements_client')->where('id', $this->formId)->update($data);
            $this->successMessage = 'Traitement mis à jour.';
        } else {
            DB::table('traitements_client')->insert(array_merge($data, ['created_at' => now()]));
            $this->successMessage = 'Traitement créé.';
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        DB::table('traitements_client')->where('id', $id)->delete();
        $this->successMessage = 'Traitement supprimé.';
        $this->resetPage();
    }

    public function facturer(int $id): void
    {
        $t = DB::table('traitements_client')->where('id', $id)->first();
        if (!$t) return;
        if ($t->facture_client_id) { $this->errorMessage = 'Déjà facturé.'; return; }
        if ($t->statut !== 'termine') { $this->errorMessage = 'Seuls les traitements terminés peuvent être facturés.'; return; }
        if (!$t->montant_traitement_fcfa || $t->montant_traitement_fcfa <= 0) { $this->errorMessage = 'Montant invalide.'; return; }

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
            $this->successMessage = 'Facture créée.';
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    // ── Paiements ────────────────────────────────────────────────────
    public function ouvrirPaiement(int $id): void
    {
        $this->paiementTraitementId = $id;
        $this->paiementMontant      = '';
        $this->paiementMode         = 'especes';
        $this->paiementDate         = now()->format('Y-m-d');
        $this->paiementDescription  = '';
        $this->showPaiement         = true;
        $this->errorMessage         = '';
    }

    public function enregistrerPaiement(): void
    {
        $montant = $this->n($this->paiementMontant);
        if ($montant <= 0) { $this->errorMessage = 'Montant invalide.'; return; }

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

        $this->successMessage = "Paiement de " . number_format($montant, 0, ',', ' ') . " FCFA enregistré.";
        $this->showPaiement   = false;
    }

    private function resetModalForm(): void
    {
        $this->formId                = null;
        $this->formCode              = '';
        $this->formClientId          = '';
        $this->formAgentId           = '';
        $this->formVarieteId         = '';
        $this->formLocaliteId        = '';
        $this->formQuantitePaddy     = '';
        $this->formDateReception     = now()->format('Y-m-d');
        $this->formQuantiteRizBlanc  = '';
        $this->formQuantiteSon       = '';
        $this->formTauxRendement     = '';
        $this->formPrixParKg         = '';
        $this->formMontantTraitement = '';
        $this->formStatut            = 'en_attente';
        $this->formObservations      = '';
    }

    public function render()
    {
        $traitements = DB::table('traitements_client as t')
            ->leftJoin('clients as c', 'c.id', '=', 't.client_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 't.variete_id')
            ->leftJoin('agents as a', 'a.id', '=', 't.agent_id')
            ->select(
                't.id', 't.code_traitement', 't.statut', 't.date_reception',
                't.facture_client_id',
                DB::raw('t.quantite_paddy_kg::text as qte_paddy'),
                DB::raw('t.montant_traitement_fcfa::text as montant'),
                'c.nom as client_nom', 'c.raison_sociale',
                'v.nom as variete_nom',
                'a.nom as agent_nom'
            )
            ->when($this->search, fn($q) =>
                $q->where('t.code_traitement', 'ilike', "%{$this->search}%")
                  ->orWhere('c.nom', 'ilike', "%{$this->search}%")
            )
            ->when($this->filtreStatut, fn($q) => $q->where('t.statut', $this->filtreStatut))
            ->orderByDesc('t.created_at')
            ->paginate(10);

        $clients   = DB::table('clients')->orderBy('nom')->get();
        $varietes  = DB::table('varietes_rice')->orderBy('nom')->get();
        $agents    = DB::table('agents')->orderBy('nom')->get();
        $localites = DB::table('localites')->orderBy('nom')->get();

        return view('livewire.traitements-clients.index', compact(
            'traitements', 'clients', 'varietes', 'agents', 'localites'
        ));
    }
}