<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\JournalActivite;

class Ensachage extends Component
{
    // Formulaire
    public mixed   $stock_produit_fini_id = null;
    public mixed   $article_id            = null;
    public mixed   $agent_id              = null;
    public string  $masse_a_ensacher      = '';
    public string  $nombre_sacs           = '';
    public string  $date_emballage        = '';
    public float   $stockDisponible       = 0;
    public float   $poidsArticle          = 0;
    public string  $typeStock             = '';

    // Feedback
    public string  $successMessage = '';
    public string  $errorMessage   = '';

    public function mount(): void
    {
        $this->date_emballage = now()->format('Y-m-d');
    }

    public function updatedStockProduitFiniId(): void
    {
        if ($this->stock_produit_fini_id && (int)$this->stock_produit_fini_id > 0) {
            $stock = DB::table('stocks_produits_finis')->where('id', $this->stock_produit_fini_id)->first();
            $this->stockDisponible  = (float)($stock?->quantite_kg ?? 0);
            $this->typeStock        = $stock?->type_produit ?? '';
            $this->article_id       = null;
            $this->nombre_sacs      = '';
            $this->masse_a_ensacher = (string)(int)$this->stockDisponible;
        } else {
            $this->stockDisponible = 0;
            $this->typeStock       = '';
        }
    }

    public function updatedArticleId(): void
    {
        if ($this->article_id && (int)$this->article_id > 0) {
            $article = DB::table('articles')->where('id', $this->article_id)->first();
            $this->poidsArticle = (float)($article?->taille_sac ?? 0);
            $this->calculerNombreSacs();
        } else {
            $this->poidsArticle = 0;
            $this->nombre_sacs  = '';
        }
    }

    public function updatedMasseAEnsacher(): void
    {
        $this->calculerNombreSacs();
    }

    private function calculerNombreSacs(): void
    {
        $masse = (float)$this->masse_a_ensacher;
        $poids = $this->poidsArticle;
        if ($masse > 0 && $poids > 0) {
            $this->nombre_sacs = (string)(int)floor($masse / $poids);
        } else {
            $this->nombre_sacs = '';
        }
    }

    public function getNombreSacsMaxAttribute(): int
    {
        if ($this->poidsArticle <= 0) return 0;
        return (int)floor($this->stockDisponible / $this->poidsArticle);
    }

    public function getPoidsTotalAttribute(): float
    {
        return (int)$this->nombre_sacs * $this->poidsArticle;
    }

    public function enregistrer(): void
    {
        $this->errorMessage   = '';
        $this->successMessage = '';

        if (!$this->stock_produit_fini_id || (int)$this->stock_produit_fini_id <= 0) {
            $this->errorMessage = 'Veuillez sélectionner un stock produit fini.'; return;
        }
        if (!$this->article_id || (int)$this->article_id <= 0) {
            $this->errorMessage = 'Veuillez sélectionner un type de sac.'; return;
        }
        $masse  = (float)$this->masse_a_ensacher;
        $nombre = (int)$this->nombre_sacs;
        if ($masse <= 0) {
            $this->errorMessage = 'Veuillez saisir la masse à ensacher.'; return;
        }
        if ($nombre <= 0) {
            $this->errorMessage = 'Nombre de sacs invalide — vérifiez la masse et la capacité du sac.'; return;
        }
        $poidsTotal = $nombre * $this->poidsArticle;
        if ($masse > $this->stockDisponible) {
            $this->errorMessage = "Masse ({$masse} kg) supérieure au stock disponible ({$this->stockDisponible} kg)."; return;
        }

        try {
            DB::transaction(function () use ($nombre, $poidsTotal) {
                $stock   = DB::table('stocks_produits_finis')->where('id', $this->stock_produit_fini_id)->first();
                $article = DB::table('articles')->where('id', $this->article_id)->first();
                $dec     = DB::table('decorticages')->where('id', $stock->decorticage_id)->first();

                // Générer code sac
                $prefix = 'SAC-' . now()->format('Y') . '-';
                $last   = DB::table('sacs_produits_finis')
                    ->where('code_sac', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')->lockForUpdate()->value('code_sac');
                $num     = $last ? ((int)substr($last, -4) + 1) : 1;
                $codeSac = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

                // Récupérer variété
                $variete = DB::table('varietes_rice')->where('id', $stock->variete_rice_id)->first();

                // Créer le sac
                $sacId = DB::table('sacs_produits_finis')->insertGetId([
                    'code_sac'               => $codeSac,
                    'stock_produit_fini_id'  => (int)$this->stock_produit_fini_id,
                    'article_id'             => (int)$this->article_id,
                    'type_sac'               => $stock->type_produit,
                    'poids_sac_kg'           => $this->poidsArticle,
                    'nombre_sacs'            => $nombre,
                    'variete_code'           => $variete?->nom ?? '',
                    'provenance_decorticage' => $dec?->code_decorticage ?? '',
                    'provenance_etuvage'     => DB::table('etuvages')->where('id', $dec?->agent_id)->value('code_etuvage') ?? '',
                    'provenance_paddy'       => DB::table('lots_paddy')->where('id', $dec?->lot_paddy_id)->value('code_lot') ?? '',
                    'date_emballage'         => $this->date_emballage,
                    'agent_id'               => $this->agent_id ? (int)$this->agent_id : null,
                    'statut'                 => 'disponible',
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);

                // Déduire du stock produit fini
                DB::table('stocks_produits_finis')
                    ->where('id', $this->stock_produit_fini_id)
                    ->decrement('quantite_kg', $poidsTotal);

                // Marquer épuisé si stock = 0
                $newQte = (float)DB::table('stocks_produits_finis')->where('id', $this->stock_produit_fini_id)->value('quantite_kg');
                if ($newQte <= 0) {
                    DB::table('stocks_produits_finis')->where('id', $this->stock_produit_fini_id)
                        ->update(['statut' => 'epuise', 'updated_at' => now()]);
                }

                // Créer stock_sac pour le point de vente par défaut (Bouaké = id 1)
                // L'utilisateur pourra transférer vers d'autres points de vente
                $pointVenteDefault = DB::table('points_vente')->orderBy('id')->value('id');
                DB::table('stocks_sacs')->insert([
                    'sac_id'       => $sacId,
                    'point_vente_id' => $pointVenteDefault,
                    'quantite'     => $nombre,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $this->successMessage = "✅ {$nombre} sac(s) {$article->nom} créés ({$poidsTotal} kg) — Code : {$codeSac}";
				JournalActivite::creation('ensachage', "Ensachage : {$codeSac} — {$nombre} sac(s) × {$this->poidsArticle} kg = {$poidsTotal} kg");
			});
						
            $this->resetForm();

        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur : ' . $e->getMessage();
        }
    }

    private function resetForm(): void
    {
        $this->stock_produit_fini_id = null;
        $this->article_id            = null;
        $this->agent_id              = null;
        $this->masse_a_ensacher      = '';
        $this->nombre_sacs           = '';
        $this->date_emballage        = now()->format('Y-m-d');
        $this->stockDisponible       = 0;
        $this->poidsArticle          = 0;
        $this->typeStock             = '';
    }

    public function render()
    {
        $stocks = DB::table('stocks_produits_finis as spf')
            ->leftJoin('varietes_rice as v', 'v.id', '=', 'spf.variete_rice_id')
            ->leftJoin('decorticages as d', 'd.id', '=', 'spf.decorticage_id')
            ->select('spf.id', 'spf.type_produit', 'spf.quantite_kg', 'spf.code_stock',
                     'v.nom as variete_nom', 'd.code_decorticage')
            ->where('spf.statut', 'disponible')
            ->where('spf.quantite_kg', '>', 0)
            ->orderBy('spf.type_produit')
            ->get();

        $articles = DB::table('articles')
            ->when($this->typeStock, fn($q) => $q->where('type_produit', $this->typeStock))
            ->orderBy('taille_sac')
            ->get();

        $agents = DB::table('agents')->orderBy('nom')->select('id', 'nom', 'prenom')->get();

        // Historique des ensachages récents
        $historique = DB::table('sacs_produits_finis as s')
            ->leftJoin('articles as a', 'a.id', '=', 's.article_id')
            ->leftJoin('agents as ag', 'ag.id', '=', 's.agent_id')
            ->select('s.code_sac', 's.type_sac', 's.nombre_sacs', 's.poids_total_kg',
                     's.poids_sac_kg', 's.date_emballage', 's.variete_code',
                     'a.nom as article_nom', 'ag.nom as agent_nom')
            ->orderByDesc('s.created_at')
            ->limit(10)
            ->get();

        $poidsTotal = (int)$this->nombre_sacs * $this->poidsArticle;
        $nbMax      = $this->getNombreSacsMaxAttribute();

        return view('livewire.transformation.ensachage', compact(
            'stocks', 'articles', 'agents', 'historique', 'poidsTotal', 'nbMax'
        ))->layout('layouts.app');
    }
}