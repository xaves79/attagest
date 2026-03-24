<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PointVente;
use App\Models\Client;
use App\Models\Agent;
use App\Models\Article;
use App\Models\FactureClient;
use App\Models\LigneFacture;
use App\Models\Vente;
use App\Models\StockSac;
use App\Models\SacProduitFini;
use Illuminate\Support\Facades\DB;

class Ventes extends Component
{
    public $showModal = false;
    public $viewMode = false;
    public $facturesRecentes = [];

    public $form = [
        'point_vente_id' => '',
        'client_id' => '',
        'agent_id' => '',
        'date_vente' => '',
        'montant_total' => 0,
        'statut' => 'credit',
    ];

    public $lignes = [];
    public $stocksParArticle = [];
    public $pointsVente = [];
    public $clients = [];
    public $agents = [];
    public $articles = [];

    public function mount()
    {
        $this->chargerDonnees();
        $this->chargerFacturesRecentes();
        $this->resetForm();
    }
    
    protected function getListeners()
    {
        return [
            'factures-mises-a-jour' => 'rafraichirFactures',
        ];
    }

    public function rafraichirFactures()
    {
        $this->chargerFacturesRecentes();
    }

    protected function chargerDonnees()
    {
        $this->pointsVente = PointVente::where('actif', true)->orderBy('nom')->get();
        $this->clients = Client::orderBy('nom')->get();
        $this->agents = Agent::where('actif', true)->orderBy('nom')->get();
        $this->articles = Article::whereIn('type_produit', ['riz_blanc', 'brisures', 'rejets', 'son'])
            ->orderBy('nom')->get();
    }

    protected function chargerFacturesRecentes()
    {
        $this->facturesRecentes = FactureClient::with(['client', 'pointVente'])
            ->orderBy('date_facture', 'desc')
            ->limit(10)
            ->get();
    }

    protected function resetForm()
    {
        $this->form = [
            'point_vente_id' => '',
            'client_id' => '',
            'agent_id' => '',
            'date_vente' => now()->format('Y-m-d'),
            'montant_total' => 0,
            'statut' => 'credit',
        ];
        $this->lignes = [];
        $this->stocksParArticle = [];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
        $this->addLigne();
    }

    public function calculerStocks()
    {
        if (!$this->form['point_vente_id']) {
            $this->stocksParArticle = [];
            return;
        }

        $stocks = DB::table('stocks_sacs')
            ->join('sacs_produits_finis', 'stocks_sacs.sac_id', '=', 'sacs_produits_finis.id')
            ->where('stocks_sacs.point_vente_id', $this->form['point_vente_id'])
            ->whereNotNull('sacs_produits_finis.article_id')
            ->select('sacs_produits_finis.article_id', DB::raw('SUM(stocks_sacs.quantite) as total'))
            ->groupBy('sacs_produits_finis.article_id')
            ->pluck('total', 'article_id')
            ->toArray();

        $this->stocksParArticle = $stocks;
    }

    public function getStockRestantProperty()
    {
        if (!$this->form['point_vente_id']) return [];

        $restant = $this->stocksParArticle;
        foreach ($this->lignes as $ligne) {
            $articleId = $ligne['article_id'] ?? null;
            $qte = (int) ($ligne['quantite'] ?? 0);
            if ($articleId && isset($restant[$articleId])) {
                $restant[$articleId] -= $qte;
            }
        }
        return $restant;
    }

    public function updatedFormPointVenteId()
    {
        $this->lignes = [];
        $this->calculerStocks();
    }

    public function addLigne()
    {
        $this->lignes[] = [
            'article_id' => '',
            'quantite' => 1,
            'prix_unitaire' => 0,
            'montant' => 0,
        ];
    }

    public function removeLigne($index)
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
        $this->calculerMontantTotal();
    }

    public function updatedLignes($value, $field)
    {
        $parts = explode('.', $field);
        if (count($parts) < 2) return;
        
        $index = (int) $parts[0];
        $key = $parts[1];

        if (!isset($this->lignes[$index])) return;

        // ✅ STOCK CHECK article_id
        if ($key === 'article_id' && $value) {
            $article = Article::find($value);
            $stockDispo = $this->stockRestant[$value] ?? 0;
            
            if ($stockDispo <= 0) {
                $this->lignes[$index]['article_id'] = '';
                $this->addError("lignes.$index.article_id", "❌ Stock épuisé !");
                return;
            }
            
            $this->lignes[$index]['prix_unitaire'] = (int) $article->prix_unitaire;
            $this->lignes[$index]['quantite'] = 1; // Reset quantité
        }
        
        // ✅ STOCK CHECK quantité
        $articleId = $this->lignes[$index]['article_id'] ?? null;
        $qte = (int) ($this->lignes[$index]['quantite'] ?? 1);
        if ($articleId && $qte > 0) {
            $stockDispo = $this->stockRestant[$articleId] ?? 0;
            if ($qte > $stockDispo) {
                $this->lignes[$index]['quantite'] = $stockDispo;
                $this->addError("lignes.$index.quantite", "Stock max: $stockDispo sacs");
            }
        }
        
        $prix = (int) ($this->lignes[$index]['prix_unitaire'] ?? 0);
        $this->lignes[$index]['montant'] = $qte * $prix;
        
        $this->lignes = $this->lignes; // Trigger reactivity
        $this->calculerMontantTotal();
    }

    public function calculerMontantTotal()
    {
        $total = 0;
        foreach ($this->lignes as $ligne) {
            $total += (int) ($ligne['montant'] ?? 0);
        }
        $this->form['montant_total'] = $total;
    }

    public function save()
    {
        // ✅ VALIDATION STOCK AVANT SAUVEGARDE
        foreach ($this->lignes as $index => $ligne) {
            $articleId = $ligne['article_id'];
            $qte = (int) $ligne['quantite'];
            
            $stockActuel = $this->stocksParArticle[$articleId] ?? 0;
            $stockDejaReserve = collect($this->lignes)->where('article_id', $articleId)->sum('quantite') - $qte;
            $stockRestant = $stockActuel - $stockDejaReserve;
            
            if ($qte > $stockRestant) {
                $this->addError("lignes.$index.quantite", "Stock insuffisant ! Maximum: $stockRestant sacs");
                return;
            }
        }

        $rules = [
            'form.point_vente_id' => 'required|exists:points_vente,id',
            'form.client_id' => 'required|exists:clients,id',
            'form.date_vente' => 'required|date',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|integer|min:0',
        ];

        $this->validate($rules);

        $numeroFacture = $this->genererNumeroFacture();

        DB::transaction(function () use ($numeroFacture) {
            $facture = FactureClient::create([
                'numero_facture' => $numeroFacture,
                'client_id' => $this->form['client_id'],
                'date_facture' => $this->form['date_vente'],
                'montant_total' => $this->form['montant_total'],
                'montant_paye' => 0,
                'solde_restant' => $this->form['montant_total'],
                'statut' => 'credit',
                'point_vente_id' => $this->form['point_vente_id'],
                'agent_id' => $this->form['agent_id'],
            ]);

            foreach ($this->lignes as $ligne) {
                $article = Article::find($ligne['article_id']);
                if (!$article) throw new \Exception("Article introuvable");

                $taille = (int) $article->taille_sac;
                $sac = SacProduitFini::where('type_sac', $article->type_produit)
                    ->where('poids_sac_kg', $taille)
                    ->where('statut', 'disponible')
                    ->first();

                if (!$sac) throw new \Exception("Aucun sac disponible pour {$article->nom}");

                LigneFacture::create([
                    'facture_id' => $facture->id,
                    'article_id' => $ligne['article_id'],
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'montant' => $ligne['montant'],
                ]);

                $poidsTotalKg = $ligne['quantite'] * $sac->poids_sac_kg;

                Vente::create([
                    'code_vente' => $this->genererCodeVente(),
                    'client_id' => $this->form['client_id'],
                    'agent_id' => $this->form['agent_id'],
                    'point_vente_id' => $this->form['point_vente_id'],
                    'quantite_vendue_kg' => $poidsTotalKg,
                    'prix_vente_unitaire_fcfa' => $ligne['prix_unitaire'],
                    'montant_vente_total_fcfa' => $ligne['montant'],
                    'type_produit' => $article->type_produit,
                    'date_vente' => $this->form['date_vente'],
                ]);

                $stock = StockSac::where('point_vente_id', $this->form['point_vente_id'])
                    ->where('sac_id', $sac->id)
                    ->first();

                if (!$stock || $stock->quantite < $ligne['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$article->nom}");
                }

                $stock->quantite -= $ligne['quantite'];
                $stock->save();
            }
        });

        session()->flash('message', "✅ Facture créée n° $numeroFacture - Montant: " . number_format($this->form['montant_total'], 0, ',', ' ') . " FCFA (crédit)");
        $this->showModal = false;
        $this->resetForm();
        $this->chargerFacturesRecentes();
    }

    protected function genererNumeroFacture()
    {
        $prefix = 'FACT-' . now()->format('Ymd') . '-';
        $last = FactureClient::where('numero_facture', 'like', $prefix . '%')
            ->orderBy('numero_facture', 'desc')->first();
        $num = $last ? (int) substr($last->numero_facture, -4) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    protected function genererCodeVente()
    {
        $prefix = 'VTE-' . now()->format('Ymd') . '-';
        $last = Vente::where('code_vente', 'like', $prefix . '%')
            ->orderBy('code_vente', 'desc')->first();
        $num = $last ? (int) substr($last->code_vente, -4) + 1 : 1;
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function render()
	{
		$this->chargerFacturesRecentes(); // ← AJOUTEZ CETTE LIGNE

		return view('livewire.ventes.index', [
			'pointsVente' => $this->pointsVente,
			'clients' => $this->clients,
			'agents' => $this->agents,
			'articles' => $this->articles,
			'stocksParArticle' => $this->stocksParArticle,
			'stockRestant' => $this->stockRestant,
			'facturesRecentes' => $this->facturesRecentes,
		]);
	}
}