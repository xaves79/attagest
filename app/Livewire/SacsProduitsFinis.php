<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\SacProduitFini;
use App\Models\Reservoir;
use App\Models\Agent;
use App\Models\VarieteRice;
use App\Models\StockProduitFini;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class SacsProduitsFinis extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;
    public $poidsOptions = [];
    public $filterTypeSac = '';
    public $filterStatut = '';
    public $filterVariete = '';

    public $form = [
        'id' => null,
        'code_sac' => '',
        'stock_produit_fini_id' => '',
        'type_sac' => 'riz_blanc',
        'poids_sac_kg' => '',
        'quantite_totale' => '',
        'variete_code' => '',
        'date_emballage' => '',
        'agent_id' => '',
        'statut' => 'disponible',
        'provenance_decorticage' => 'Interne',  // ✅ FIX NOT NULL
        'prix_unitaire' => 0,                   // ✅ FIX champs manquants
    ];

    public $stocksDisponibles = [];
    public $nombre_sacs_calcule = 0;

    public function mount()
    {
        $this->resetForm();
        $this->loadPoidsOptions();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function loadPoidsOptions()
    {
        $this->poidsOptions = [1, 5, 10, 25, 50, 100];
    }

    public function updatedFormTypeSac($value)
    {
        $this->form['stock_produit_fini_id'] = '';
        if ($value) {
            $this->stocksDisponibles = StockProduitFini::with('varieteRice')
                ->where('type_produit', $value)
                ->where('quantite_kg', '>', 0)
                ->get()
                ->map(function ($stock) {
                    $stock->display = $stock->code_stock . ' (' . number_format($stock->quantite_kg, 2) . ' kg) - ' . ($stock->varieteRice?->code_variete ?? '');
                    return $stock;
                });
        } else {
            $this->stocksDisponibles = [];
        }
    }

    public function updatedFormStockProduitFiniId($value)
    {
        if ($value) {
            $stock = StockProduitFini::with('varieteRice')->find($value);
            if ($stock && $stock->varieteRice) {
                $this->form['variete_code'] = $stock->varieteRice->code_variete;
            } else {
                $this->form['variete_code'] = '';
            }
        } else {
            $this->form['variete_code'] = '';
        }
    }

    public function updatedFormQuantiteTotale()
    {
        $this->calculerNombreSacs();
    }

    public function updatedFormPoidsSacKg()
    {
        $this->calculerNombreSacs();
    }

    protected function calculerNombreSacs()
    {
        $poids = (float) ($this->form['poids_sac_kg'] ?? 0);
        $total = (float) ($this->form['quantite_totale'] ?? 0);
        if ($poids > 0 && $total > 0) {
            $this->nombre_sacs_calcule = floor($total / $poids);
        } else {
            $this->nombre_sacs_calcule = 0;
        }
    }

    public function render()
    {
        set_time_limit(60);

        $query = SacProduitFini::with(['stockProduitFini.varieteRice', 'agent'])
            ->where('nombre_sacs', '>', 0);

        if ($this->search) {
            $query->where('code_sac', 'like', '%' . $this->search . '%')
                  ->orWhereHas('stockProduitFini', fn ($q) => $q->where('code_stock', 'like', '%' . $this->search . '%'));
        }

        if ($this->filterTypeSac) {
            $query->where('type_sac', $this->filterTypeSac);
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }

        if ($this->filterVariete) {
            $query->where('variete_code', $this->filterVariete);
        }

        $sacsParVariete = SacProduitFini::select(
                DB::raw('COALESCE(variete_code, \'Non renseigné\') as variete'),
                DB::raw('SUM(nombre_sacs) as total')
            )
            ->where('nombre_sacs', '>', 0)
            ->groupBy(DB::raw('COALESCE(variete_code, \'Non renseigné\')'))
            ->pluck('total', 'variete')
            ->toArray();

        $sacsParPoids = SacProduitFini::selectRaw('poids_sac_kg, SUM(nombre_sacs) as total')
            ->where('nombre_sacs', '>', 0)
            ->groupBy('poids_sac_kg')
            ->pluck('total', 'poids_sac_kg')
            ->toArray();

        $sacsParType = SacProduitFini::selectRaw('type_sac, SUM(nombre_sacs) as total')
            ->where('nombre_sacs', '>', 0)
            ->groupBy('type_sac')
            ->pluck('total', 'type_sac')
            ->toArray();

        $sacs = $query->paginate(10);
        $varietes = VarieteRice::select('id', 'code_variete', 'nom')->get();
        $agents = Agent::all();

        return view('livewire.sacs-produits-finis.index', [
            'sacs'            => $sacs,
            'agents'          => $agents,
            'sacsParVariete'  => $sacsParVariete,
            'sacsParPoids'    => $sacsParPoids,
            'sacsParType'     => $sacsParType,
            'varietes'        => $varietes,
            'stocksDisponibles' => $this->stocksDisponibles,
            'nombre_sacs_calcule' => $this->nombre_sacs_calcule,
        ]);
    }

    private function generateCodeSac()
    {
        $prefix = 'SAC-';
        $date = now()->format('Ymd');
        $suffix = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $code = $prefix . $date . '-' . $suffix;

        while (SacProduitFini::where('code_sac', $code)->exists()) {
            $suffix = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $code = $prefix . $date . '-' . $suffix;
        }

        $this->form['code_sac'] = $code;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
        $this->generateCodeSac();
        $this->updatedFormTypeSac($this->form['type_sac']);
    }

    public function edit($id)
    {
        $sac = SacProduitFini::with('stockProduitFini')->findOrFail($id);
        $this->form = $sac->toArray();
        $this->form['quantite_totale'] = $sac->poids_sac_kg * $sac->nombre_sacs;
        $this->calculerNombreSacs();
        $this->updatedFormTypeSac($sac->type_sac);
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $sac = SacProduitFini::findOrFail($id);
        $this->form = $sac->toArray();
        $this->form['quantite_totale'] = $sac->poids_sac_kg * $sac->nombre_sacs;
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function delete($id)
    {
        $sac = SacProduitFini::findOrFail($id);
        $sac->delete();
        session()->flash('message', 'Sac supprimé avec succès.');
        $this->resetPage();
    }

    public function save()
    {
        $this->validate([
            'form.code_sac' => 'required|unique:sacs_produits_finis,code_sac,' . ($this->form['id'] ?? 'null'),
            'form.stock_produit_fini_id' => 'required|exists:stocks_produits_finis,id',
            'form.type_sac' => 'required|in:riz_blanc,brisures,rejets,son',
            'form.poids_sac_kg' => 'required|numeric|min:0.1',
            'form.quantite_totale' => 'required|numeric|min:0.1',
            'form.variete_code' => 'required|string|max:50',
            'form.date_emballage' => 'required|date',
            'form.agent_id' => 'nullable|exists:agents,id',
            'form.statut' => 'required|in:disponible,en_transfert,transfere',
            'form.provenance_decorticage' => 'required|string|max:100',  // ✅ Validation ajoutée
            'form.prix_unitaire' => 'nullable|numeric|min:0',            // ✅ Validation ajoutée
        ]);

        $this->calculerNombreSacs();
        $nombreSacs = $this->nombre_sacs_calcule;

        $stock = StockProduitFini::find($this->form['stock_produit_fini_id']);
        if (!$stock) {
            session()->flash('error', 'Stock introuvable.');
            return;
        }

        if ($this->form['quantite_totale'] > $stock->quantite_kg) {
            session()->flash('error', "La quantité totale ({$this->form['quantite_totale']} kg) dépasse le stock disponible ({$stock->quantite_kg} kg).");
            return;
        }

        $data = $this->form;
        $data['nombre_sacs'] = $nombreSacs;
        unset($data['quantite_totale']);

        // ✅ TOUS les champs critiques sécurisés
        $data['provenance_decorticage'] = $data['provenance_decorticage'] ?? 'Interne';
        $data['prix_unitaire'] = $data['prix_unitaire'] ?? 0;

        SacProduitFini::updateOrCreate(
            ['id' => $this->form['id']],
            $data
        );

        $stock->quantite_kg -= $this->form['quantite_totale'];
        $stock->save();

        session()->flash('message', $this->form['id'] ? '✅ Sac mis à jour avec succès.' : '✅ Sac créé avec succès.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'code_sac' => '',
            'stock_produit_fini_id' => '',
            'type_sac' => 'riz_blanc',
            'poids_sac_kg' => '',
            'quantite_totale' => '',
            'variete_code' => '',
            'date_emballage' => now()->format('Y-m-d'),
            'agent_id' => '',
            'statut' => 'disponible',
            'provenance_decorticage' => 'Interne',  // ✅
            'prix_unitaire' => 0,                   // ✅
        ];
        $this->stocksDisponibles = [];
        $this->nombre_sacs_calcule = 0;
    }
}