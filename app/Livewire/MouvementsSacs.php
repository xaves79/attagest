<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MouvementSac;
use App\Models\StockSac;
use App\Models\PointVente;
use App\Models\SacProduitFini;
use App\Models\Agent;
use Illuminate\Support\Facades\Log;

class MouvementsSacs extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id' => null,
        'point_vente_id' => '',
        'sac_id' => '',
        'quantite' => '',
        'type_mouvement' => 'entree',
        'agent_id' => '',
        'notes' => '',
    ];

    public $stocksDisponibles = [];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedFormPointVenteId($value)
    {
        $this->form['sac_id'] = '';
        $this->stocksDisponibles = [];

        if (!$value) return;

        if ($this->form['type_mouvement'] === 'sortie') {
            // Pour une sortie : afficher les stocks disponibles (sacs déjà présents)
            $this->stocksDisponibles = StockSac::with('sac')
                ->where('point_vente_id', $value)
                ->where('quantite', '>', 0)
                ->get()
                ->map(function ($stock) {
                    $typeLabel = $this->getTypeLabel($stock->sac?->type_sac);
                    $stock->display = $stock->sac?->code_sac
                        . ' - ' . $typeLabel
                        . ' (' . $stock->sac?->poids_sac_kg . ' kg)'
                        . ' - Stock: ' . $stock->quantite . ' sacs';
                    return $stock;
                });
        } else {
            // Pour une entrée : afficher tous les lots disponibles (stock > 0)
            $this->stocksDisponibles = SacProduitFini::where('nombre_sacs', '>', 0)
                ->orderBy('code_sac')
                ->get()
                ->map(function ($sac) {
                    $typeLabel = $this->getTypeLabel($sac->type_sac);
                    $sac->display = $sac->code_sac
                        . ' - ' . $typeLabel
                        . ' (' . $sac->poids_sac_kg . ' kg)'
                        . ' - Disponible: ' . $sac->nombre_sacs . ' sacs';
                    return $sac;
                });
        }
    }

    public function updatedFormTypeMouvement($value)
    {
        $this->form['sac_id'] = '';
        $this->stocksDisponibles = [];

        if (!$this->form['point_vente_id']) return;

        if ($value === 'sortie') {
            // Pour une sortie
            $this->stocksDisponibles = StockSac::with('sac')
                ->where('point_vente_id', $this->form['point_vente_id'])
                ->where('quantite', '>', 0)
                ->get()
                ->map(function ($stock) {
                    $typeLabel = $this->getTypeLabel($stock->sac?->type_sac);
                    $stock->display = $stock->sac?->code_sac
                        . ' - ' . $typeLabel
                        . ' (' . $stock->sac?->poids_sac_kg . ' kg)'
                        . ' - Stock: ' . $stock->quantite . ' sacs';
                    return $stock;
                });
        } else {
            // Pour une entrée : tous les lots disponibles
            $this->stocksDisponibles = SacProduitFini::where('nombre_sacs', '>', 0)
                ->orderBy('code_sac')
                ->get()
                ->map(function ($sac) {
                    $typeLabel = $this->getTypeLabel($sac->type_sac);
                    $sac->display = $sac->code_sac
                        . ' - ' . $typeLabel
                        . ' (' . $sac->poids_sac_kg . ' kg)'
                        . ' - Disponible: ' . $sac->nombre_sacs . ' sacs';
                    return $sac;
                });
        }
    }

    private function getTypeLabel($type)
    {
        return match($type) {
            'riz_blanc' => 'Riz blanc',
            'brisures'  => 'Brisures',
            'rejets'    => 'Rejets',
            'son'       => 'Son',
            default     => $type,
        };
    }

    public function render()
    {
        set_time_limit(60);
        $mouvements = MouvementSac::with(['stockSac.pointVente', 'stockSac.sac', 'agent'])
            ->when($this->search, function ($q) {
                $q->whereHas('stockSac.pointVente', fn ($qv) => $qv->where('nom', 'like', "%{$this->search}%"))
                  ->orWhereHas('stockSac.sac', fn ($qs) => $qs->where('code_sac', 'like', "%{$this->search}%"));
            })
            ->orderBy('date_mouvement', 'desc')
            ->paginate(10);

        $pointsVente = PointVente::orderBy('nom')->get();
        $sacs = SacProduitFini::orderBy('code_sac')->get();
        $agents = Agent::all();

        return view('livewire.mouvements-sacs', [
            'mouvements' => $mouvements,
            'pointsVente' => $pointsVente,
            'sacs' => $sacs,
            'agents' => $agents,
            'stocksDisponibles' => $this->stocksDisponibles,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $mouvement = MouvementSac::with('stockSac')->findOrFail($id);
        $this->form = [
            'id' => $mouvement->id,
            'point_vente_id' => $mouvement->stockSac?->point_vente_id,
            'sac_id' => $mouvement->stockSac?->sac_id,
            'quantite' => $mouvement->quantite,
            'type_mouvement' => $mouvement->type_mouvement,
            'agent_id' => $mouvement->agent_id,
            'notes' => $mouvement->notes,
        ];
        $this->showModal = true;
        $this->viewMode = true;
    }

    public function edit($id)
    {
        session()->flash('error', 'Modification non autorisée.');
        return;
    }

    public function delete($id)
    {
        Log::info('Début suppression mouvement', ['id' => $id]);

        $mouvement = MouvementSac::with('stockSac')->findOrFail($id);
        Log::info('Mouvement trouvé', $mouvement->toArray());

        $stock = $mouvement->stockSac;
        Log::info('Stock associé', $stock->toArray());

        if ($mouvement->type_mouvement === 'entree') {
            if ($stock->quantite < $mouvement->quantite) {
                Log::error('Stock insuffisant dans point de vente', [
                    'stock_quantite' => $stock->quantite,
                    'mouvement_quantite' => $mouvement->quantite
                ]);
                session()->flash('error', 'Incohérence : le stock du point de vente est inférieur à la quantité du mouvement.');
                return;
            }
            $stock->quantite -= $mouvement->quantite;
            $stock->save();
            Log::info('Stock point de vente décrémenté', ['nouvelle_quantite' => $stock->quantite]);

            $sac = SacProduitFini::find($stock->sac_id);
            if ($sac) {
                Log::info('Lot trouvé', $sac->toArray());
                $sac->nombre_sacs += $mouvement->quantite;
                $sac->save();
                Log::info('Lot recrédité', ['nouveau_nombre' => $sac->nombre_sacs]);
            } else {
                Log::warning('Lot non trouvé pour sac_id', ['sac_id' => $stock->sac_id]);
            }
        } else {
            $stock->quantite += $mouvement->quantite;
            $stock->save();
            Log::info('Stock point de vente recrédité (sortie)', ['nouvelle_quantite' => $stock->quantite]);
        }

        $mouvement->delete();
        Log::info('Mouvement supprimé');

        session()->flash('message', 'Mouvement supprimé avec succès.');
        $this->resetPage();
    }

    public function save()
    {
        Log::info('Données reçues pour sauvegarde', $this->form);

        $this->validate([
            'form.point_vente_id' => 'required|exists:points_vente,id',
            'form.sac_id' => 'required|exists:sacs_produits_finis,id',
            'form.quantite' => 'required|integer|min:1',
            'form.type_mouvement' => 'required|in:entree,sortie',
            'form.agent_id' => 'nullable|exists:agents,id',
        ]);

        $sac = SacProduitFini::find($this->form['sac_id']);

        if ($this->form['type_mouvement'] === 'entree') {
            // Vérifier que le lot a assez de stock
            if ($this->form['quantite'] > $sac->nombre_sacs) {
                session()->flash('error', "La quantité demandée dépasse le stock disponible ({$sac->nombre_sacs} sacs).");
                return;
            }

            // Chercher si un stock existe déjà pour ce point de vente et ce sac
            $stock = StockSac::where('point_vente_id', $this->form['point_vente_id'])
                ->where('sac_id', $this->form['sac_id'])
                ->first();

            if ($stock) {
                // Si existe, on ajoute la quantité
                $stock->quantite += $this->form['quantite'];
            } else {
                // Sinon, on crée
                $stock = new StockSac([
                    'point_vente_id' => $this->form['point_vente_id'],
                    'sac_id' => $this->form['sac_id'],
                ]);
                $stock->quantite = $this->form['quantite'];
            }
            $stock->save();

            // Décrémenter le lot
            $sac->nombre_sacs -= $this->form['quantite'];
            $sac->save();

        } else {
            // Sortie
            $stock = StockSac::where('point_vente_id', $this->form['point_vente_id'])
                ->where('sac_id', $this->form['sac_id'])
                ->first();
            if (!$stock || $stock->quantite < $this->form['quantite']) {
                session()->flash('error', 'Stock insuffisant pour ce sac dans ce point de vente.');
                return;
            }
            $stock->quantite -= $this->form['quantite'];
            $stock->save();
        }

        MouvementSac::create([
            'stock_sac_id' => $stock->id,
            'quantite' => $this->form['quantite'],
            'type_mouvement' => $this->form['type_mouvement'],
            'agent_id' => $this->form['agent_id'],
            'notes' => $this->form['notes'],
            'date_mouvement' => now(),
        ]);

        session()->flash('message', 'Mouvement enregistré avec succès.');
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id' => null,
            'point_vente_id' => '',
            'sac_id' => '',
            'quantite' => '',
            'type_mouvement' => 'entree',
            'agent_id' => '',
            'notes' => '',
        ];
        $this->stocksDisponibles = [];
    }
}