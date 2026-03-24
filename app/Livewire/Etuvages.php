<?php

namespace App\Livewire;

use App\Models\Etuvage;
use App\Models\Agent;
use App\Models\StockPaddy;
use App\Models\AchatPaddy;
use App\Models\LotRizEtuve;
use Livewire\Component;
use Livewire\WithPagination;

class Etuvages extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $form = [
        'id'                    => null,
        'code_etuvage'          => '',
        'agent_id'              => '',
        'masse_entree_kg'       => '',
        'date_debut_etuvage'    => '',
        'temperature_etuvage'   => '',
        'duree_etuvage_minutes' => '',
        'date_fin_etuvage'      => '',
        'statut'                => 'en_cours',
        'stock_paddy_id'        => '',
        'achat_paddy_id'        => '',
        'masse_sortie_kg'       => '',
    ];

    public $showModal = false;
    public $viewMode = false;
    public $agents = [];
    public $stocks_paddy = [];
    public $achats_paddy = [];

    public function mount()
    {
        $this->agents = Agent::where('actif', true)->orderBy('nom')->get();
        $this->refreshStocks();
        $this->achats_paddy = AchatPaddy::orderBy('code_lot')->get();
        $this->resetForm();
    }

    // Recharge les stocks disponibles (quantité > 0)
    public function refreshStocks()
    {
        $this->stocks_paddy = StockPaddy::with('achat')
            ->where('quantite_restante_kg', '>', 0)
            ->orderBy('code_stock')
            ->get();
    }

    public function render()
    {
        set_time_limit(60); // Évite le timeout

        // Calcul du stock total de paddy disponible
        $stockPaddyTotal = StockPaddy::sum('quantite_restante_kg');

        $query = Etuvage::with(['agent', 'stockPaddy', 'achatPaddy']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code_etuvage', 'like', "%{$this->search}%")
                  ->orWhere('statut', 'like', "%{$this->search}%");
            })->orWhereHas('agent', fn($q) => $q->where('nom', 'like', "%{$this->search}%"));
        }

        $etuvages = $query->paginate(10);

        return view('livewire.etuvages.index', [
            'etuvages' => $etuvages,
            'stockPaddyTotal' => $stockPaddyTotal,
        ])->layout('layouts.app');
    }

    public function resetForm()
    {
        $this->form = [
            'id'                    => null,
            'code_etuvage'          => 'ETV-' . now()->format('Ymd-His'),
            'agent_id'              => '',
            'masse_entree_kg'       => '',
            'date_debut_etuvage'    => now()->format('Y-m-d H:i'),
            'temperature_etuvage'   => '',
            'duree_etuvage_minutes' => '',
            'date_fin_etuvage'      => '',
            'statut'                => 'en_cours',
            'stock_paddy_id'        => '',
            'achat_paddy_id'        => '',
            'masse_sortie_kg'       => '',
        ];
        $this->showModal = false;
        $this->viewMode = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function show($id)
    {
        $etuvage = Etuvage::with(['agent', 'stockPaddy', 'achatPaddy'])->findOrFail($id);
        $this->form = $etuvage->toArray();
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function terminer($id)
    {
        $etuvage = Etuvage::with(['stockPaddy.achat'])->findOrFail($id);

        $this->validate([
            'form.masse_sortie_kg' => 'required|numeric|min:0',
        ]);

        $etuvage->update([
            'statut'           => 'termine',
            'date_fin_etuvage' => now(),
        ]);

        $lot = LotRizEtuve::create([
            'quantite_entree_kg'      => $etuvage->masse_entree_kg,
            'quantite_restante_kg'    => $etuvage->masse_sortie_kg,
            'masse_apres_kg'          => $etuvage->masse_sortie_kg,
            'provenance_etuvage_id'   => $etuvage->id,
            'variete_rice_id'         => $etuvage->stockPaddy?->achat?->variete_rice_id,
        ]);

        session()->flash('message', '✅ Étuvage terminé et lot riz étuvé créé : ' . $lot->code_lot);
        $this->dispatch('$refresh');
    }

    public function edit($id)
    {
        $etuvage = Etuvage::with(['agent', 'stockPaddy', 'achatPaddy'])->findOrFail($id);
        $this->form = $etuvage->toArray();
        $this->showModal = true;
        $this->viewMode = false;
    }

    // Lorsque le stock paddy change, on déduit automatiquement l'achat associé
    public function updatedFormStockPaddyId($value)
    {
        if ($value) {
            $stock = StockPaddy::with('achat')->find($value);
            $this->form['achat_paddy_id'] = $stock->achat->id ?? null;
        } else {
            $this->form['achat_paddy_id'] = null;
        }
    }

    public function save()
    {
        $rules = [
            'form.code_etuvage'        => 'required|string|max:50',
            'form.agent_id'            => 'required|integer|exists:agents,id',
            'form.masse_entree_kg'     => 'required|numeric|min:0',
            'form.date_debut_etuvage'  => 'required|date',
            'form.temperature_etuvage' => 'required|integer|min:0',
            'form.duree_etuvage_minutes' => 'required|integer|min:0',
            'form.date_fin_etuvage'    => 'required|date',
            'form.statut'              => 'required|string',
            'form.stock_paddy_id'      => 'required|integer|exists:stocks_paddy,id',
            'form.achat_paddy_id'      => 'nullable|integer|exists:achats_paddy,id',
            'form.masse_sortie_kg'     => 'nullable|numeric|min:0',
        ];

        $this->validate($rules);

        // Si achat_paddy_id est vide, on le met à null
        if (empty($this->form['achat_paddy_id'])) {
            $this->form['achat_paddy_id'] = null;
        }

        Etuvage::updateOrCreate(
            ['id' => $this->form['id']],
            $this->form
        );

        // Recharger la liste des stocks disponibles (car le stock utilisé a diminué)
        $this->refreshStocks();

        $this->resetForm();
        session()->flash('message', '✅ Étuvage enregistré !');
        $this->dispatch('$refresh');
    }

    public function delete($id)
    {
        Etuvage::findOrFail($id)->delete();

        // Recharger les stocks (car la suppression doit remettre la quantité)
        $this->refreshStocks();

        session()->flash('message', '✅ Étuvage supprimé !');
        $this->dispatch('$refresh');
    }
}