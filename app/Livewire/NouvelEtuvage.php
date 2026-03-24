<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\JournalActivite;

class NouvelEtuvage extends Component
{
    public mixed   $lot_paddy_id             = null;
    public mixed   $agent_id                 = null;
    public string  $quantite_paddy_entree_kg = '';
    public string  $date_debut_etuvage       = '';
    public string  $temperature_etuvage      = '';
    public string  $duree_etuvage_minutes    = '';
    public float   $stockDisponible          = 0;

    public bool    $showCloture       = false;
    public ?int    $etuvageACloturer  = null;
    public string  $masse_apres_kg    = '';
    public string  $date_fin_etuvage  = '';
    public string  $codeEtuvageCloture = '';

    public string  $successMessage = '';
    public string  $errorMessage   = '';

    public function mount(): void
    {
        $this->date_debut_etuvage = now()->format('Y-m-d\TH:i');
        $this->date_fin_etuvage   = now()->format('Y-m-d\TH:i');
    }

    public function updatedLotPaddyId(): void
    {
        if ($this->lot_paddy_id && (int)$this->lot_paddy_id > 0) {
            $lot = DB::table('lots_paddy')->where('id', $this->lot_paddy_id)->first();
            $this->stockDisponible          = (float)($lot?->quantite_restante_kg ?? 0);
            $this->quantite_paddy_entree_kg = (string)(int)$this->stockDisponible;
        } else {
            $this->stockDisponible = 0;
        }
    }

    public function lancer(): void
    {
        $this->errorMessage   = '';
        $this->successMessage = '';

        if (!$this->lot_paddy_id || (int)$this->lot_paddy_id <= 0) {
            $this->errorMessage = 'Veuillez sélectionner un lot paddy.'; return;
        }
        if ((float)$this->quantite_paddy_entree_kg <= 0) {
            $this->errorMessage = 'Quantité invalide.'; return;
        }
        if ((float)$this->quantite_paddy_entree_kg > $this->stockDisponible) {
            $this->errorMessage = "Quantité supérieure au stock disponible ({$this->stockDisponible} kg)."; return;
        }
        if (!$this->date_debut_etuvage) {
            $this->errorMessage = 'Date de début requise.'; return;
        }

        try {
            DB::transaction(function () {
                $prefix = 'ETV-' . now()->format('Y') . '-';
                $last   = DB::table('etuvages')
                    ->where('code_etuvage', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')->lockForUpdate()->value('code_etuvage');
                $num         = $last ? ((int)substr($last, -4) + 1) : 1;
                $codeEtuvage = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

                DB::table('etuvages')->insert([
                    'code_etuvage'             => $codeEtuvage,
                    'lot_paddy_id'             => (int)$this->lot_paddy_id,
                    'agent_id'                 => $this->agent_id ? (int)$this->agent_id : null,
                    'quantite_paddy_entree_kg' => (float)$this->quantite_paddy_entree_kg,
                    'date_debut_etuvage'       => $this->date_debut_etuvage,
                    'temperature_etuvage'      => $this->temperature_etuvage ?: null,
                    'duree_etuvage_minutes'    => $this->duree_etuvage_minutes ?: null,
                    'statut'                   => 'en_cours',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                $this->successMessage = "Étuvage {$codeEtuvage} lancé — en attente de pesée après séchage.";
				JournalActivite::creation('etuvages', "Lancement étuvage : {$codeEtuvage} — {$this->quantite_paddy_entree_kg} kg paddy");
			});
						
            $this->resetLancement();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function ouvrirCloture(int $id): void
    {
        $etuvage = DB::table('etuvages')->where('id', $id)->first();
        if (!$etuvage) return;

        $this->etuvageACloturer   = $id;
        $this->codeEtuvageCloture = $etuvage->code_etuvage;
        $this->date_fin_etuvage   = now()->format('Y-m-d\TH:i');

        $taux = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_etuvage')->value('valeur') ?? 95);
        $this->masse_apres_kg = (string)round((float)$etuvage->quantite_paddy_entree_kg * $taux / 100, 1);

        $this->showCloture    = true;
        $this->errorMessage   = '';
        $this->successMessage = '';
    }

    public function fermerCloture(): void
    {
        $this->showCloture      = false;
        $this->etuvageACloturer = null;
        $this->masse_apres_kg   = '';
        $this->errorMessage     = '';
    }

    public function cloturer(): void
    {
        $this->errorMessage = '';

        $etuvage = DB::table('etuvages')->where('id', $this->etuvageACloturer)->first();
        if (!$etuvage) { $this->errorMessage = 'Étuvage introuvable.'; return; }

        $sortie = (float)$this->masse_apres_kg;
        $entree = (float)$etuvage->quantite_paddy_entree_kg;

        if ($sortie <= 0) { $this->errorMessage = 'Masse après étuvage invalide.'; return; }
        if ($sortie > $entree) { $this->errorMessage = 'La masse après ne peut pas dépasser la masse entrée.'; return; }

        $rendement = round($sortie / $entree * 100, 2);
        $perte     = round($entree - $sortie, 2);

        $tauxRef = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_etuvage')->value('valeur') ?? 95);
        $alerte  = $rendement < ($tauxRef - 10);

        try {
            DB::transaction(function () use ($etuvage, $sortie, $entree, $rendement, $perte) {
                DB::table('etuvages')->where('id', $this->etuvageACloturer)->update([
                    'statut'           => 'termine',
                    'date_fin_etuvage' => $this->date_fin_etuvage,
                    'updated_at'       => now(),
                ]);

                $lot = DB::table('lots_paddy')->where('id', $etuvage->lot_paddy_id)->first();

                $prefixL = 'LRE-' . now()->format('Y') . '-';
                $lastL   = DB::table('lots_riz_etuve')
                    ->where('code_lot', 'like', $prefixL . '%')
                    ->orderBy('id', 'desc')->lockForUpdate()->value('code_lot');
                $numL         = $lastL ? ((int)substr($lastL, -4) + 1) : 1;
                $codeLotEtuve = $prefixL . str_pad($numL, 4, '0', STR_PAD_LEFT);

                DB::table('lots_riz_etuve')->insert([
                    'code_lot'              => $codeLotEtuve,
                    'provenance_etuvage_id' => $this->etuvageACloturer,
                    'variete_rice_id'       => $lot->variete_id,
                    'quantite_entree_kg'    => $sortie,
                    'quantite_restante_kg'  => $sortie,
                    'masse_apres_kg'        => $sortie,
                    'date_production'       => $this->date_fin_etuvage ?: now(),
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

                $this->successMessage = "✅ Étuvage clôturé — Lot {$codeLotEtuve} créé · Rendement réel : {$rendement}%";
				JournalActivite::modification('etuvages', "Clôture étuvage : {$etuvage->code_etuvage} — {$sortie} kg riz étuvé — Rdt : {$rendement}%");
			});
						
            if ($alerte) {
                $this->successMessage .= " ⚠️ Rendement inférieur au seuil attendu !";
            }

            $this->showCloture      = false;
            $this->etuvageACloturer = null;
            $this->masse_apres_kg   = '';

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    private function resetLancement(): void
    {
        $this->lot_paddy_id             = null;
        $this->agent_id                 = null;
        $this->quantite_paddy_entree_kg = '';
        $this->date_debut_etuvage       = now()->format('Y-m-d\TH:i');
        $this->temperature_etuvage      = '';
        $this->duree_etuvage_minutes    = '';
        $this->stockDisponible          = 0;
    }

    public function render()
    {
        $lots = DB::table('lots_paddy as lp')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->select('lp.id', 'lp.code_lot', 'lp.quantite_restante_kg', 'f.nom as fournisseur_nom', 'v.nom as variete_nom')
            ->where('lp.quantite_restante_kg', '>', 0)
            ->whereNotIn('lp.statut', ['complet', 'epuise'])
            ->orderByDesc('lp.date_achat')
            ->get();

        $agents = DB::table('agents')->orderBy('nom')->select('id', 'nom', 'prenom')->get();

        $etuvagesEnCours = DB::table('etuvages as e')
            ->leftJoin('lots_paddy as lp', 'lp.id', '=', 'e.lot_paddy_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lp.variete_id')
            ->leftJoin('fournisseurs as f', 'f.id', '=', 'lp.fournisseur_id')
            ->leftJoin('agents as a', 'a.id', '=', 'e.agent_id')
            ->select(
                'e.id', 'e.code_etuvage', 'e.quantite_paddy_entree_kg',
                'e.date_debut_etuvage', 'e.temperature_etuvage', 'e.duree_etuvage_minutes',
                'lp.code_lot', 'v.nom as variete_nom', 'f.nom as fournisseur_nom',
                'a.prenom as agent_prenom', 'a.nom as agent_nom'
            )
            ->where('e.statut', 'en_cours')
            ->orderBy('e.date_debut_etuvage')
            ->get();

        $tauxRef = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_etuvage')->value('valeur') ?? 95);

        return view('livewire.transformation.nouvel-etuvage', compact(
            'lots', 'agents', 'etuvagesEnCours', 'tauxRef'
        ))->layout('layouts.app');
    }
}