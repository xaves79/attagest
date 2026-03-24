<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\JournalActivite;

class NouvelDecorticage extends Component
{
    // Formulaire lancement
    public mixed   $lot_riz_etuve_id         = null;
    public mixed   $agent_id                 = null;
    public string  $quantite_paddy_entree_kg = '';
    public string  $date_debut_decorticage   = '';
    public float   $stockDisponible          = 0;
    public ?int    $varieteId                = null;

    // Clôture
    public bool    $showCloture              = false;
    public ?int    $decorticageACloturer     = null;
    public string  $codeDecorticageCloture   = '';
    public string  $quantite_riz_blanc_kg    = '';
    public string  $quantite_brise_kg        = '';
    public string  $quantite_rejet_kg        = '0';
    public string  $date_fin_decorticage     = '';

    // Feedback
    public string  $successMessage = '';
    public string  $errorMessage   = '';

    public function mount(): void
    {
        $this->date_debut_decorticage = now()->format('Y-m-d\TH:i');
        $this->date_fin_decorticage   = now()->format('Y-m-d\TH:i');
    }

    public function updatedLotRizEtuveId(): void
    {
        if ($this->lot_riz_etuve_id && (int)$this->lot_riz_etuve_id > 0) {
            $lot = DB::table('lots_riz_etuve')->where('id', $this->lot_riz_etuve_id)->first();
            $this->stockDisponible          = (float)($lot?->quantite_restante_kg ?? 0);
            $this->quantite_paddy_entree_kg = (string)(int)$this->stockDisponible;
            $this->varieteId                = $lot?->variete_rice_id;
        } else {
            $this->stockDisponible = 0;
            $this->varieteId       = null;
        }
    }

    // Calcul son automatique
    public function getSonKgAttribute(): float
    {
        $entree = (float)$this->quantite_paddy_entree_kg;
        $blanc  = (float)$this->quantite_riz_blanc_kg;
        $brise  = (float)$this->quantite_brise_kg;
        $rejet  = (float)$this->quantite_rejet_kg;
        return max(0, round($entree - $blanc - $brise - $rejet, 2));
    }

    public function lancer(): void
    {
        $this->errorMessage   = '';
        $this->successMessage = '';

        if (!$this->lot_riz_etuve_id || (int)$this->lot_riz_etuve_id <= 0) {
            $this->errorMessage = 'Veuillez sélectionner un lot de riz étuvé.'; return;
        }
        if ((float)$this->quantite_paddy_entree_kg <= 0) {
            $this->errorMessage = 'Quantité invalide.'; return;
        }
        if ((float)$this->quantite_paddy_entree_kg > $this->stockDisponible) {
            $this->errorMessage = "Quantité supérieure au stock disponible ({$this->stockDisponible} kg)."; return;
        }
        if (!$this->date_debut_decorticage) {
            $this->errorMessage = 'Date de début requise.'; return;
        }

        try {
            DB::transaction(function () {
                $prefix = 'DEC-' . now()->format('Y') . '-';
                $last   = DB::table('decorticages')
                    ->where('code_decorticage', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')->lockForUpdate()->value('code_decorticage');
                $num              = $last ? ((int)substr($last, -4) + 1) : 1;
                $codeDecorticage  = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

                // INSERT decorticage — trigger débite lots_riz_etuve
                DB::table('decorticages')->insert([
                    'code_decorticage'         => $codeDecorticage,
                    'lot_riz_etuve_id'         => (int)$this->lot_riz_etuve_id,
                    'lot_paddy_id'             => DB::table('lots_riz_etuve as lre')
                        ->join('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
                        ->where('lre.id', (int)$this->lot_riz_etuve_id)
                        ->value('e.lot_paddy_id'),
                    'agent_id'                 => $this->agent_id ? (int)$this->agent_id : null,
                    'variete_rice_id'          => $this->varieteId,
                    'quantite_paddy_entree_kg' => (float)$this->quantite_paddy_entree_kg,
                    'quantite_riz_blanc_kg'    => 0,
                    'quantite_son_kg'          => 0,
                    'quantite_brise_kg'        => 0,
                    'quantite_rejet_kg'        => 0,
                    'taux_rendement'           => 0,
                    'statut'                   => 'en_cours',
                    'date_debut_decorticage'   => $this->date_debut_decorticage,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                $this->successMessage = "Décorticage {$codeDecorticage} lancé.";
				JournalActivite::creation('decorticages', "Lancement décorticage : {$codeDecorticage} — {$this->quantite_paddy_entree_kg} kg");
			});
						
            $this->resetLancement();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    public function ouvrirCloture(int $id): void
    {
        $dec = DB::table('decorticages')->where('id', $id)->first();
        if (!$dec) return;

        $this->decorticageACloturer   = $id;
        $this->codeDecorticageCloture = $dec->code_decorticage;
        $this->date_fin_decorticage   = now()->format('Y-m-d\TH:i');

        // Pré-remplir avec taux de rendement paramétré
        $taux = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_decorticage')->value('valeur') ?? 65);
        $this->quantite_riz_blanc_kg  = (string)round((float)$dec->quantite_paddy_entree_kg * $taux / 100, 1);
        $this->quantite_brise_kg      = '0';
        $this->quantite_rejet_kg      = '0';

        $this->showCloture    = true;
        $this->errorMessage   = '';
        $this->successMessage = '';
    }

    public function fermerCloture(): void
    {
        $this->showCloture             = false;
        $this->decorticageACloturer    = null;
        $this->quantite_riz_blanc_kg   = '';
        $this->quantite_brise_kg       = '';
        $this->quantite_rejet_kg       = '0';
        $this->errorMessage            = '';
    }

    public function cloturer(): void
    {
        $this->errorMessage = '';

        $dec    = DB::table('decorticages')->where('id', $this->decorticageACloturer)->first();
        if (!$dec) { $this->errorMessage = 'Décorticage introuvable.'; return; }

        $entree = (float)$dec->quantite_paddy_entree_kg;
        $blanc  = (float)$this->quantite_riz_blanc_kg;
        $brise  = (float)$this->quantite_brise_kg;
        $rejet  = (float)$this->quantite_rejet_kg;
        $son    = max(0, $entree - $blanc - $brise - $rejet);

        if ($blanc <= 0) { $this->errorMessage = 'Quantité riz blanc invalide.'; return; }
        if ($blanc + $brise + $rejet > $entree) {
            $this->errorMessage = 'La somme riz blanc + brisures + rejets dépasse la quantité engagée.'; return;
        }

        $rendement = $entree > 0 ? round($blanc / $entree * 100, 2) : 0;

        $tauxRef = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_decorticage')->value('valeur') ?? 65);
        $alerte  = $rendement < ($tauxRef - 10);

        try {
            DB::transaction(function () use ($dec, $blanc, $son, $brise, $rejet, $rendement) {
                // Mettre à jour le décorticage
                DB::table('decorticages')->where('id', $this->decorticageACloturer)->update([
                    'quantite_riz_blanc_kg'  => $blanc,
                    'quantite_son_kg'        => $son,
                    'quantite_brise_kg'      => $brise,
                    'quantite_rejet_kg'      => $rejet,
                    'taux_rendement'         => $rendement,
                    'statut'                 => 'termine',
                    'date_fin_decorticage'   => $this->date_fin_decorticage,
                    'date_terminaison'       => now(),
                    'updated_at'             => now(),
                ]);

                $prefixS = 'SPF-' . now()->format('Y') . '-';
                $lastS   = DB::table('stocks_produits_finis')
                    ->where('code_stock', 'like', $prefixS . '%')
                    ->orderBy('id', 'desc')->lockForUpdate()->value('code_stock');
                $numS = $lastS ? ((int)substr($lastS, -4) + 1) : 1;

                $produits = [
                    ['type' => 'riz_blanc', 'qte' => $blanc],
                    ['type' => 'son',       'qte' => $son],
                    ['type' => 'brisures',  'qte' => $brise],
                    ['type' => 'rejet',     'qte' => $rejet],
                ];

                foreach ($produits as $p) {
                    if ($p['qte'] <= 0) continue;
                    DB::table('stocks_produits_finis')->insert([
                        'code_stock'      => $prefixS . str_pad($numS++, 4, '0', STR_PAD_LEFT),
                        'decorticage_id'  => $this->decorticageACloturer,
                        'agent_id'        => $dec->agent_id,
                        'variete_rice_id' => $dec->variete_rice_id,
                        'lot_paddy_id'    => $dec->lot_paddy_id,
                        'etuvage_id'      => DB::table('lots_riz_etuve')->where('id', $dec->lot_riz_etuve_id)->value('provenance_etuvage_id'),
                        'type_produit'    => $p['type'],
                        'quantite_kg'     => $p['qte'],
                        'statut'          => 'disponible',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }

                $this->successMessage = "✅ Décorticage clôturé · Riz blanc : {$blanc} kg · Son : {$son} kg · Brisures : {$brise} kg · Rejets : {$rejet} kg · Rendement : {$rendement}%";
				JournalActivite::modification('decorticages', "Clôture décorticage : {$dec->code_decorticage} — Riz blanc : {$blanc} kg — Son : {$son} kg — Rdt : {$rendement}%");
			});
						
            if ($alerte) {
                $this->successMessage .= " ⚠️ Rendement inférieur au seuil attendu !";
            }

            $this->fermerCloture();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    private function resetLancement(): void
    {
        $this->lot_riz_etuve_id         = null;
        $this->agent_id                 = null;
        $this->quantite_paddy_entree_kg = '';
        $this->date_debut_decorticage   = now()->format('Y-m-d\TH:i');
        $this->stockDisponible          = 0;
        $this->varieteId                = null;
    }

    public function render()
    {
        $lots = DB::table('lots_riz_etuve as lre')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'lre.variete_rice_id')
            ->leftJoin('etuvages as e', 'e.id', '=', 'lre.provenance_etuvage_id')
            ->select('lre.id', 'lre.code_lot', 'lre.quantite_restante_kg', 'v.nom as variete_nom', 'e.code_etuvage')
            ->where('lre.quantite_restante_kg', '>', 0)
            ->orderByDesc('lre.date_production')
            ->get();

        $agents = DB::table('agents')->orderBy('nom')->select('id', 'nom', 'prenom')->get();

        $decorticagesEnCours = DB::table('decorticages as d')
            ->leftJoin('lots_riz_etuve as lre', 'lre.id', '=', 'd.lot_riz_etuve_id')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'd.variete_rice_id')
            ->leftJoin('agents as a', 'a.id', '=', 'd.agent_id')
            ->select(
                'd.id', 'd.code_decorticage', 'd.quantite_paddy_entree_kg', 'd.date_debut_decorticage',
                'lre.code_lot as code_lot_etuve', 'v.nom as variete_nom',
                'a.prenom as agent_prenom', 'a.nom as agent_nom'
            )
            ->where('d.statut', 'en_cours')
            ->orderBy('d.date_debut_decorticage')
            ->get();

        $tauxRef    = (float)(DB::table('parametres_app')->where('cle', 'taux_rendement_decorticage')->value('valeur') ?? 65);
        $sonCalc    = $this->getSonKgAttribute();

        return view('livewire.transformation.nouvel-decorticage', compact(
            'lots', 'agents', 'decorticagesEnCours', 'tauxRef', 'sonCalc'
        ))->layout('layouts.app');
    }
}