<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\JournalActivite;
use App\Models\AchatPaddy;

class NouvelAchat extends Component
{
    // ----------------------------------------------------------------
    // Étapes
    // ----------------------------------------------------------------
    public int $etape = 1; // 1 = Infos lot, 2 = Reçu fournisseur, 3 = Confirmation

    // ----------------------------------------------------------------
    // Étape 1 — Lot paddy
    // ----------------------------------------------------------------
    public ?int    $fournisseur_id           = null;
    public ?int    $agent_id                 = null;
    public ?int    $variete_id               = null;
    public ?int    $localite_id              = null;
    public string  $date_achat               = '';
    public bool    $est_anticipe             = false;
    public string  $date_livraison_prevue    = '';
    public string  $quantite_achat_kg        = '';
    public string  $prix_achat_unitaire_fcfa = '';

    // ----------------------------------------------------------------
    // Étape 2 — Reçu fournisseur
    // ----------------------------------------------------------------
    public string  $mode_paiement        = 'espece';
    public string  $acompte              = '0';
    public string  $date_limite_paiement = '';
    public int     $jours_credit         = 0;
    public string  $reference_entreprise = '';
    public bool    $generer_recu         = true;

    // ----------------------------------------------------------------
    // Feedback
    // ----------------------------------------------------------------
    public string  $successMessage = '';
    public string  $errorMessage   = '';
    public ?string $codeLot        = null;
    public ?string $numeroRecu     = null;

    // ----------------------------------------------------------------
    // Mount
    // ----------------------------------------------------------------
    public function mount(): void
    {
        $this->date_achat = now()->format('Y-m-d');
    }

    // ----------------------------------------------------------------
    // Calcul montant total
    // ----------------------------------------------------------------
    public function calculMontant(): int
    {
        return (int) ((float)$this->quantite_achat_kg * (float)$this->prix_achat_unitaire_fcfa);
    }

    public function calculSolde(): int
    {
        return max(0, $this->calculMontant() - (int)$this->acompte);
    }

    // Compat alias pour la vue
    public function getMontantTotalAttribute(): int { return $this->calculMontant(); }
    public function getSoldeAttribute(): int { return $this->calculSolde(); }

    // ----------------------------------------------------------------
    // Navigation
    // ----------------------------------------------------------------
    public function allerEtape2(): void
    {
        $this->errorMessage = '';

        if (!$this->fournisseur_id) {
            $this->errorMessage = 'Veuillez sélectionner un fournisseur.';
            return;
        }
        if (!$this->variete_id) {
            $this->errorMessage = 'Veuillez sélectionner une variété.';
            return;
        }
        if (!$this->date_achat) {
            $this->errorMessage = 'Veuillez saisir la date d\'achat.';
            return;
        }
        if ((float)$this->quantite_achat_kg <= 0) {
            $this->errorMessage = 'La quantité doit être supérieure à 0.';
            return;
        }
        if ((float)$this->prix_achat_unitaire_fcfa <= 0) {
            $this->errorMessage = 'Le prix doit être supérieur à 0.';
            return;
        }

        // Pré-remplir acompte selon mode : crédit = 0, sinon montant total
        $this->acompte = $this->mode_paiement === 'credit'
            ? '0'
            : (string) $this->getMontantTotalAttribute();
        $this->etape   = 2;
    }

    public function allerEtape3(): void
    {
        $this->errorMessage = '';
        $this->etape = 3;
    }

    public function updatedModePaiement(): void
    {
        if ($this->mode_paiement === 'credit') {
            $this->acompte = '0';
        } else {
            $this->acompte = (string) $this->calculMontant();
        }
    }

    public function retourEtape(int $n): void
    {
        $this->etape        = $n;
        $this->errorMessage = '';
    }

    // ----------------------------------------------------------------
    // Enregistrement
    // ----------------------------------------------------------------
    public function enregistrer(): void
	{
		$this->errorMessage  = '';
		$this->successMessage = '';

		try {
			DB::transaction(function () {
				$entreprise = DB::table('entreprises')->first();
				$montant    = $this->getMontantTotalAttribute();
				$acompte    = (int)$this->acompte;

				// 1. Générer code lot
				$prefix = 'LP-' . now()->format('y') . '-';
				$last   = DB::table('lots_paddy')
					->where('code_lot', 'like', $prefix . '%')
					->orderBy('id', 'desc')
					->lockForUpdate()
					->value('code_lot');
				$num     = $last ? ((int) substr($last, -4) + 1) : 1;
				$codeLot = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

				// 2. Créer le lot paddy (trigger initialise stock auto)
				$lot = AchatPaddy::create([
					'code_lot'                => $codeLot,
					'fournisseur_id'          => $this->fournisseur_id,
					'agent_id'                => $this->agent_id,
					'variete_id'              => $this->variete_id,
					'localite_id'             => $this->localite_id ?: null,
					'entreprise_id'           => $entreprise?->id,
					'date_achat'              => $this->date_achat,
					'quantite_achat_kg'       => (float)$this->quantite_achat_kg,
					'prix_achat_unitaire_fcfa'=> (float)$this->prix_achat_unitaire_fcfa,
					'montant_achat_total_fcfa'=> $montant,
					'quantite_restante_kg'    => (float)$this->quantite_achat_kg,
					'statut'                  => $this->est_anticipe ? 'anticipe' : 'disponible',
				]);

				// 2b. Créer le stock paddy associé
				$codeStock = 'STK-' . $codeLot;
				DB::table('stocks_paddy')->insert([
					'code_stock'            => $codeStock,
					'lot_paddy_id'          => $lot->id,
					'agent_id'              => $this->agent_id,
					'quantite_stock_kg'     => (float)$this->quantite_achat_kg,
					'quantite_restante_kg'  => (float)$this->quantite_achat_kg,
					'emplacement'           => 'Entrepôt Central',
					'created_at'            => now(),
					'updated_at'            => now(),
				]);

				// 3. Créer le reçu fournisseur
				if ($this->generer_recu) {
					$prefixR    = 'REC-' . now()->format('Y') . '-';
					$lastR      = DB::table('recus_fournisseurs')
						->where('numero_recu', 'like', $prefixR . '%')
						->orderBy('id', 'desc')
						->lockForUpdate()
						->value('numero_recu');
					$numR       = $lastR ? ((int) substr($lastR, -4) + 1) : 1;
					$numeroRecu = $prefixR . str_pad($numR, 4, '0', STR_PAD_LEFT);

					$solde = max(0, $montant - $acompte);

					DB::table('recus_fournisseurs')->insert([
						'numero_recu'          => $numeroRecu,
						'fournisseur_id'       => $this->fournisseur_id,
						'variete_rice_id'      => $this->variete_id,
						'entreprise_id'        => $entreprise?->id,
						'achat_paddy_id'       => $lot->id,
						'date_recu'            => $this->date_achat,
						'montant_total'        => $montant,
						'mode_paiement'        => $this->mode_paiement,
						'acompte'              => $acompte,
						'solde_du'             => $solde,
						'paye'                 => $solde <= 0,
						'jours_credit'         => $this->jours_credit ?: null,
						'date_limite_paiement' => $this->date_limite_paiement ?: null,
						'reference_entreprise' => $this->reference_entreprise ?: null,
						'created_at'           => now(),
						'updated_at'           => now(),
					]);

					$this->numeroRecu = $numeroRecu;
				}

				$this->codeLot = $codeLot;
			});

			$this->successMessage = "Lot {$this->codeLot} enregistré avec succès !";

			// Journal d'activité — AVANT resetForm()
			$fournisseur = DB::table('fournisseurs')->where('id', $this->fournisseur_id)->value('nom');
			JournalActivite::creation('achats',
				"Nouvel achat paddy : {$this->codeLot} — {$this->quantite_achat_kg} kg — {$fournisseur}"
			);

			$this->resetForm();

		} catch (\Exception $e) {
			$this->errorMessage = 'Erreur : ' . $e->getMessage();
		}
	}

    private function resetForm(): void
    {
        $this->etape                    = 1;
        $this->fournisseur_id           = null;
        $this->agent_id                 = null;
        $this->variete_id               = null;
        $this->localite_id              = null;
        $this->date_achat               = now()->format('Y-m-d');
        $this->est_anticipe             = false;
        $this->date_livraison_prevue    = '';
        $this->quantite_achat_kg        = '';
        $this->prix_achat_unitaire_fcfa = '';
        $this->mode_paiement            = 'espece';
        $this->acompte                  = '0';
        $this->date_limite_paiement     = '';
        $this->jours_credit             = 0;
        $this->reference_entreprise     = '';
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------
    public function render()
    {
        return view('livewire.achats.nouvel-achat', [
            'fournisseurs'  => DB::table('fournisseurs')->orderBy('nom')->select('id','nom','prenom')->get(),
            'agents'        => DB::table('agents')->orderBy('nom')->select('id','nom','prenom')->get(),
            'varietes'      => DB::table('varietes_rice')->orderBy('nom')->select('id','nom')->get(),
            'localites'     => DB::table('localites')->orderBy('nom')->select('id','nom')->get(),
            'montantTotal'  => $this->calculMontant(),
            'soldeRestant'  => $this->calculSolde(),
        ])->layout('layouts.app');
    }
}