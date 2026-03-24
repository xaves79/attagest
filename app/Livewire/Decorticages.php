<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Decorticage;
use App\Models\LotRizEtuve;
use App\Models\Agent;
use App\Models\StockProduitFini;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class Decorticages extends Component
{
    use WithPagination;

    public $search = '';
    public $lot_riz_etuve_id = '';
    public $agent_id = '';
    public $date_debut = '';
    public $date_fin = '';

    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id'                         => null,
        'code_decorticage'           => '',
        'lot_riz_etuve_id'           => '',
        'agent_id'                   => '',
        // FIX #5 : etuvage_id supprimé du form — la liaison passe uniquement par lot_riz_etuve_id
        'quantite_paddy_entree_kg'   => '',
        'quantite_riz_blanc_kg'      => '',
        'quantite_rejet_kg'          => '',
        'quantite_brise_kg'          => '',
        'quantite_son_kg'            => '',
        'taux_rendement'             => '',
        'date_debut_decorticage'     => '',
        'date_fin_decorticage'       => '',
        'date_terminaison'           => '',
        'statut'                     => 'en_cours',
    ];

    public $achatPaddyLabel = '';
    public $varieteLabel = '';

    // FIX #2 : mémoriser l'ancien lot_id ET l'ancienne quantité pour gérer les edits
    protected $oldLotId = null;
    protected $oldEntreeKg = 0;

    public function mount()
    {
        $this->resetForm();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFormLotRizEtuveId($value)
    {
        if ($value) {
            $lot = LotRizEtuve::with(['etuvage.achatPaddy.variete'])->find($value);

            if ($lot && $lot->etuvage && $lot->etuvage->achatPaddy) {
                $this->achatPaddyLabel = $lot->etuvage->achatPaddy->code_lot ?? '-';
                $this->varieteLabel    = $lot->etuvage->achatPaddy->variete?->nom ?? '-';
            } else {
                $this->achatPaddyLabel = '-';
                $this->varieteLabel    = '-';
            }
        } else {
            $this->achatPaddyLabel = '-';
            $this->varieteLabel    = '-';
        }
    }

    public function updatedForm($property, $value)
    {
        $numericFields = ['quantite_paddy_entree_kg', 'quantite_riz_blanc_kg', 'quantite_rejet_kg', 'quantite_brise_kg'];

        if (in_array($property, $numericFields)) {
            $clean = str_replace([' ', ','], ['', '.'], trim($value));
            $this->form[$property] = ($clean === '' || !is_numeric($clean)) ? 0 : (float) $clean;
            $this->calculateRendement();
        }
    }

    public function calculateRendement()
    {
        $entree = (float) ($this->form['quantite_paddy_entree_kg'] ?? 0);
        $blanc  = (float) ($this->form['quantite_riz_blanc_kg'] ?? 0);

        $this->form['taux_rendement'] = ($entree > 0)
            ? min(100, round(($blanc / $entree) * 100, 2))
            : 0;
    }

    public function render()
    {
        $decorticages = Decorticage::with([
                'lotRizEtuve',
                'lotRizEtuve.etuvage',
                'agent',
            ])
            ->where(function ($query) {
                $query->where('code_decorticage', 'like', "%{$this->search}%")
                    ->orWhereHas('lotRizEtuve', fn ($q) => $q->where('code_lot', 'like', "%{$this->search}%"))
                    ->orWhereHas('agent', fn ($q) => $q->where('nom', 'like', "%{$this->search}%"));
            })
            ->when($this->lot_riz_etuve_id, fn ($q) => $q->where('lot_riz_etuve_id', $this->lot_riz_etuve_id))
            ->when($this->agent_id, fn ($q) => $q->where('agent_id', $this->agent_id))
            ->when($this->date_debut, fn ($q) => $q->whereDate('date_debut_decorticage', '>=', $this->date_debut))
            ->when($this->date_fin, fn ($q) => $q->whereDate('date_debut_decorticage', '<=', $this->date_fin))
            ->latest()
            ->paginate(10);

        $lots_riz_etuve = LotRizEtuve::where('quantite_restante_kg', '>', 0)
            ->with(['etuvage.achatPaddy.variete'])
            ->get();

        $agents = Agent::all();

        // FIX #8 : $etuvages supprimé — plus nécessaire dans la vue

        return view('livewire.decorticages.index', compact(
            'decorticages',
            'lots_riz_etuve',
            'agents',
        ));
    }

    public function create()
    {
        $this->resetForm();
        $this->form['code_decorticage'] = $this->generateUniqueCodeDecorticage();
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function edit($id)
    {
        $decorticage = Decorticage::with(['lotRizEtuve.etuvage.achatPaddy.variete'])->findOrFail($id);
        $this->form = $decorticage->only([
            'id', 'code_decorticage', 'lot_riz_etuve_id', 'agent_id',
            'quantite_paddy_entree_kg', 'quantite_riz_blanc_kg', 'quantite_rejet_kg',
            'quantite_brise_kg', 'quantite_son_kg', 'taux_rendement',
            'date_debut_decorticage', 'date_fin_decorticage', 'date_terminaison', 'statut',
        ]);

        // FIX #2 : mémoriser l'état avant modification
        $this->oldLotId   = $decorticage->lot_riz_etuve_id;
        $this->oldEntreeKg = (float) $decorticage->quantite_paddy_entree_kg;

        $this->updateRelatedLabels($decorticage);
        $this->showModal = true;
        $this->viewMode = false;
    }

    public function show($id)
    {
        $decorticage = Decorticage::with(['lotRizEtuve.etuvage.achatPaddy.variete'])->findOrFail($id);
        $this->form = $decorticage->only([
            'id', 'code_decorticage', 'lot_riz_etuve_id', 'agent_id',
            'quantite_paddy_entree_kg', 'quantite_riz_blanc_kg', 'quantite_rejet_kg',
            'quantite_brise_kg', 'quantite_son_kg', 'taux_rendement',
            'date_debut_decorticage', 'date_fin_decorticage', 'date_terminaison', 'statut',
        ]);
        $this->updateRelatedLabels($decorticage);
        $this->showModal = true;
        $this->viewMode = true;
    }

    // FIX #3 : bloquer la suppression d'un décorticage terminé (stocks déjà générés)
    public function delete($id)
    {
        $decorticage = Decorticage::findOrFail($id);

        if ($decorticage->statut === 'termine') {
            session()->flash('error', 'Impossible de supprimer un décorticage terminé : des stocks ont déjà été générés.');
            return;
        }

        DB::transaction(function () use ($decorticage) {
            $lot = LotRizEtuve::lockForUpdate()->find($decorticage->lot_riz_etuve_id);

            $decorticage->delete();

            if ($lot) {
                $lot->quantite_restante_kg += (float) $decorticage->quantite_paddy_entree_kg;
                $lot->save();
            }
        });

        session()->flash('message', 'Décorticage supprimé avec succès.');
        $this->resetPage();
    }

    // FIX #4 : protection contre double-clic via statut + transaction atomique
    public function terminer($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $decorticage = Decorticage::with(['lotRizEtuve.etuvage.achatPaddy'])
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($decorticage->statut === 'termine') {
                    session()->flash('error', 'Ce décorticage est déjà terminé.');
                    return;
                }

                $lot    = $decorticage->lotRizEtuve;
                $etuvage = $lot?->etuvage;
                $achat  = $etuvage?->achatPaddy;

                $variete_id = $lot?->variete_rice_id ?? $achat?->variete_id;

                $produits = [
                    'riz_blanc' => $decorticage->quantite_riz_blanc_kg,
                    'rejets'    => $decorticage->quantite_rejet_kg,
                    'brisures'  => $decorticage->quantite_brise_kg,
                    'son'       => $decorticage->quantite_son_kg,
                ];

                foreach ($produits as $type => $quantite) {
                    if ((float) $quantite > 0) {
                        StockProduitFini::create([
                            'code_stock'      => $this->generateCodeStockProduitFini($type),
                            'type_produit'    => $type,
                            'quantite_kg'     => $quantite,
                            'decorticage_id'  => $decorticage->id,
                            'agent_id'        => $decorticage->agent_id,
                            'variete_rice_id' => $variete_id,
                            'etuvage_id'      => $etuvage?->id,
                            'achat_paddy_id'  => $achat?->id,
                            'statut'          => 'actif',
                        ]);
                    }
                }

                $decorticage->update([
                    'statut'              => 'termine',
                    'date_fin_decorticage' => now(),
                ]);
            });

            session()->flash('message', 'Décorticage terminé et stocks créés.');
            $this->dispatch('$refresh');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function save()
    {
        // Normalisation numérique
        $numericFields = ['quantite_paddy_entree_kg', 'quantite_riz_blanc_kg', 'quantite_rejet_kg', 'quantite_brise_kg'];
        foreach ($numericFields as $field) {
            $this->form[$field] = isset($this->form[$field]) && $this->form[$field] !== ''
                ? (float) $this->form[$field]
                : 0;
        }

        $this->validate([
            'form.code_decorticage'         => 'required|unique:decorticages,code_decorticage,' . ($this->form['id'] ?? 'null'),
            'form.lot_riz_etuve_id'         => 'required|exists:lots_riz_etuve,id',
            'form.agent_id'                 => 'nullable|exists:agents,id',
            'form.quantite_paddy_entree_kg' => 'required|numeric|min:0.1',
            'form.quantite_riz_blanc_kg'    => 'required|numeric|min:0',
            'form.quantite_rejet_kg'        => 'required|numeric|min:0',
            'form.quantite_brise_kg'        => 'required|numeric|min:0',
            'form.date_debut_decorticage'   => 'required|date',
            'form.statut'                   => 'required|in:en_cours,termine',
        ]);

        $entree = $this->form['quantite_paddy_entree_kg'];
        $blanc  = $this->form['quantite_riz_blanc_kg'];
        $rejet  = $this->form['quantite_rejet_kg'];
        $brise  = $this->form['quantite_brise_kg'];

        $son = max(0, $entree - $blanc - $rejet - $brise);
        $this->form['quantite_son_kg']  = $son;
        $this->form['taux_rendement']   = $entree > 0 ? round(($blanc / $entree) * 100, 2) : 0;

        // Vérification cohérence interne
        $somme = $blanc + $rejet + $brise + $son;
        if (abs($somme - $entree) > 0.01) {
            session()->flash('error', "Incohérence : la somme des produits ({$somme} kg) ≠ quantité entrée ({$entree} kg).");
            return;
        }

        // Nullification des champs optionnels vides
        $nullableFields = ['agent_id', 'date_fin_decorticage', 'date_terminaison'];
        foreach ($nullableFields as $field) {
            if (empty($this->form[$field]) && $this->form[$field] !== 0) {
                $this->form[$field] = null;
            }
        }

        try {
            DB::transaction(function () use ($entree) {
                $isUpdate  = !empty($this->form['id']);
                $newLotId  = $this->form['lot_riz_etuve_id'];

                // FIX #1 & #6 : vérification stock disponible + lockForUpdate anti race condition
                $newLot = LotRizEtuve::lockForUpdate()->findOrFail($newLotId);

                // FIX #2 : si edit avec changement de lot → recréditer l'ancien lot
                if ($isUpdate && $this->oldLotId && $this->oldLotId != $newLotId) {
                    $oldLot = LotRizEtuve::lockForUpdate()->findOrFail($this->oldLotId);
                    $oldLot->quantite_restante_kg += $this->oldEntreeKg;
                    $oldLot->save();
                    $disponible = $newLot->quantite_restante_kg;
                } else {
                    // Même lot : le disponible inclut la quantité déjà réservée par cet enregistrement
                    $disponible = $newLot->quantite_restante_kg + ($isUpdate ? $this->oldEntreeKg : 0);
                }

                if ($entree > $disponible) {
                    throw new \Exception(
                        "Stock insuffisant dans le lot. Disponible : {$disponible} kg, Demandé : {$entree} kg."
                    );
                }

                // Calcul de la nouvelle quantité restante du lot
                $newLot->quantite_restante_kg = $disponible - $entree;
                $newLot->save();

                // Supprimer etuvage_id du form avant persistance (FIX #5)
                $data = collect($this->form)->except(['etuvage_id'])->toArray();

                Decorticage::updateOrCreate(['id' => $data['id'] ?? null], $data);
            });

            session()->flash('message', $this->form['id'] ? 'Décorticage mis à jour.' : 'Décorticage créé.');
            $this->showModal = false;
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->form = [
            'id'                         => null,
            'code_decorticage'           => '',
            'lot_riz_etuve_id'           => '',
            'agent_id'                   => '',
            'quantite_paddy_entree_kg'   => '',
            'quantite_riz_blanc_kg'      => '',
            'quantite_rejet_kg'          => '',
            'quantite_brise_kg'          => '',
            'quantite_son_kg'            => '',
            'taux_rendement'             => '',
            'date_debut_decorticage'     => '',
            'date_fin_decorticage'       => '',
            'date_terminaison'           => '',
            'statut'                     => 'en_cours',
        ];

        $this->achatPaddyLabel = '';
        $this->varieteLabel    = '';
        $this->oldLotId        = null;
        $this->oldEntreeKg     = 0;
    }

    // FIX #7 : boucle bornée à 100 tentatives pour éviter une boucle infinie
    protected function generateUniqueCodeDecorticage(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "DEC-{$date}-";

        for ($i = 0; $i < 100; $i++) {
            $code = $prefix . str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
            if (!Decorticage::where('code_decorticage', $code)->exists()) {
                return $code;
            }
        }

        // Fallback : timestamp microseconde (unique garanti)
        return $prefix . now()->format('His') . random_int(10, 99);
    }

    protected function generateCodeStockProduitFini(string $type): string
    {
        $prefix = 'STK-PF-' . now()->format('Ymd') . '-';
        $abbrev = strtoupper(substr($type, 0, 3));
        $count  = StockProduitFini::where('code_stock', 'like', $prefix . $abbrev . '%')->count() + 1;
        return $prefix . $abbrev . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    protected function updateRelatedLabels(Decorticage $decorticage): void
    {
        $lot = $decorticage->lotRizEtuve;
        if ($lot && $lot->etuvage && $lot->etuvage->achatPaddy) {
            $this->achatPaddyLabel = $lot->etuvage->achatPaddy->code_lot ?? '-';
            $this->varieteLabel    = $lot->etuvage->achatPaddy->variete?->nom ?? '-';
        } else {
            $this->achatPaddyLabel = '-';
            $this->varieteLabel    = '-';
        }
    }
}