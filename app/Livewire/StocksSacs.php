<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockSac;
use App\Models\PointVente;
use App\Models\SacProduitFini;

class StocksSacs extends Component
{
    use WithPagination;

    public $search = '';
    public $point_vente_id = '';
    public $filterType = '';
    public $filterPoids = '';
    public $filterVariete = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'point_vente_id' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterPoids' => ['except' => ''],
        'filterVariete' => ['except' => ''],
    ];

    public function resetFilters()
    {
        $this->reset(['search', 'point_vente_id', 'filterType', 'filterPoids', 'filterVariete']);
        $this->resetPage();
    }

    public function render()
    {
        set_time_limit(60); // Évite le timeout

        // Récupérer tous les stocks positifs pour construire les listes de filtres
        $stocksPositifs = StockSac::with('sac')
            ->where('quantite', '>', 0)
            ->get();

        $typesDisponibles = $stocksPositifs->pluck('sac.type_sac')->unique()->values();
        $poidsDisponibles = $stocksPositifs->pluck('sac.poids_sac_kg')->unique()->sort()->values();
        $varietesDisponibles = $stocksPositifs->pluck('sac.variete_code')->filter()->unique()->values();

        // Requête paginée pour l'affichage
        $query = StockSac::with(['pointVente', 'sac'])
            ->where('quantite', '>', 0); // Seulement les stocks positifs

        if ($this->point_vente_id) {
            $query->where('point_vente_id', $this->point_vente_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('pointVente', fn ($qv) => $qv->where('nom', 'like', "%{$this->search}%"))
                  ->orWhereHas('sac', fn ($qs) => $qs->where('code_sac', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterType) {
            $query->whereHas('sac', fn ($q) => $q->where('type_sac', $this->filterType));
        }

        if ($this->filterPoids) {
            $query->whereHas('sac', fn ($q) => $q->where('poids_sac_kg', $this->filterPoids));
        }

        if ($this->filterVariete) {
            $query->whereHas('sac', fn ($q) => $q->where('variete_code', $this->filterVariete));
        }

        $stocks = $query->orderBy('point_vente_id')->orderBy('sac_id')->paginate(10);

        $pointsVente = PointVente::orderBy('nom')->get();

        return view('livewire.stocks-sacs', [
            'stocks' => $stocks,
            'pointsVente' => $pointsVente,
            'typesDisponibles' => $typesDisponibles,
            'poidsDisponibles' => $poidsDisponibles,
            'varietesDisponibles' => $varietesDisponibles,
        ]);
    }
}