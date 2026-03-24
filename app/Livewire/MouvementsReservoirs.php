<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class MouvementsReservoirs extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $viewMode = false;

    public $form = [
        'id' => null,
        'reservoir_id' => '',
        'stock_id' => '',
        'type_stock' => 'riz_blanc',
        'quantite_kg' => '',
        'type_mouvement' => 'entree',
        'agent_id' => '',
        'decorticage_id' => '',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFormTypeMouvement($value)
    {
        // Chargé dans render()
    }

    public function render()
    {
        $query = DB::table('mouvements_reservoirs as mr')
            ->leftJoin('reservoirs as r', 'r.id', '=', 'mr.reservoir_id')
            ->leftJoin('agents as a', 'a.id', '=', 'mr.agent_id')
            ->select('mr.*', 'r.nom_reservoir', 'a.nom as agent_nom', 'a.prenom as agent_prenom')
            ->when($this->search, fn($q) =>
                $q->where('r.nom_reservoir', 'ilike', "%{$this->search}%")
                  ->orWhere('mr.type_mouvement', 'ilike', "%{$this->search}%")
            )
            ->orderByDesc('mr.created_at');

        $mouvements = $query->paginate(10);

        $reservoirs       = DB::table('reservoirs')->orderBy('nom_reservoir')->get();
        $agents           = DB::table('agents')->orderBy('nom')->get();
        $stocksDisponibles = $this->form['type_mouvement'] === 'entree'
            ? DB::table('stocks_produits_finis')->where('quantite_kg', '>', 0)->get()
            : collect();

        return view('livewire.mouvements-reservoirs.index', compact(
            'mouvements', 'agents', 'reservoirs', 'stocksDisponibles'
        ));
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->viewMode  = false;
    }

    public function edit($id)
    {
        $mouvement = DB::table('mouvements_reservoirs')->where('id', $id)->first();
        if ($mouvement) $this->form = (array)$mouvement;
        $this->showModal = true;
        $this->viewMode  = false;
    }

    public function show($id)
    {
        $mouvement = DB::table('mouvements_reservoirs')->where('id', $id)->first();
        if ($mouvement) $this->form = (array)$mouvement;
        $this->showModal = true;
        $this->viewMode  = true;
    }

    public function delete($id)
    {
        session()->flash('error', 'La suppression n\'est pas autorisée. Utilisez un mouvement inverse.');
    }

    public function save()
    {
        $reservoir = DB::table('reservoirs')->where('id', $this->form['reservoir_id'])->first();
        if (!$reservoir) { $this->addError('form.reservoir_id', 'Réservoir introuvable.'); return; }

        $quantite = (float)$this->form['quantite_kg'];

        if ($this->form['type_mouvement'] === 'entree') {
            if ($reservoir->quantite_actuelle_kg + $quantite > $reservoir->capacite_max_kg) {
                session()->flash('error', 'Capacité maximale dépassée.'); return;
            }
        } else {
            if ($reservoir->quantite_actuelle_kg < $quantite) {
                session()->flash('error', 'Stock insuffisant dans le réservoir.'); return;
            }
        }

        if ($this->form['type_mouvement'] === 'entree' && !empty($this->form['stock_id'])) {
            $stock = DB::table('stocks_produits_finis')->where('id', $this->form['stock_id'])->first();
            if ($stock && $stock->quantite_kg < $quantite) {
                session()->flash('error', 'Stock de production insuffisant.'); return;
            }
            DB::table('stocks_produits_finis')->where('id', $this->form['stock_id'])
                ->decrement('quantite_kg', $quantite);
        }

        $data = [
            'reservoir_id'   => $this->form['reservoir_id'],
            'stock_id'       => $this->form['stock_id'] ?: null,
            'type_stock'     => $this->form['type_stock'] ?? null,
            'quantite_kg'    => $quantite,
            'type_mouvement' => $this->form['type_mouvement'],
            'agent_id'       => $this->form['agent_id'] ?: null,
            'decorticage_id' => $this->form['decorticage_id'] ?: null,
            'updated_at'     => now(),
        ];

        if (empty($this->form['id'])) {
            $data['created_at'] = now();
            DB::table('mouvements_reservoirs')->insert($data);
        } else {
            DB::table('mouvements_reservoirs')->where('id', $this->form['id'])->update($data);
        }

        $delta = $this->form['type_mouvement'] === 'entree' ? $quantite : -$quantite;
        DB::table('reservoirs')->where('id', $this->form['reservoir_id'])
            ->increment('quantite_actuelle_kg', $delta);

        session()->flash('message', 'Mouvement enregistré.');
        $this->showModal = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->form = [
            'id'             => null,
            'reservoir_id'   => '',
            'stock_id'       => '',
            'type_stock'     => 'riz_blanc',
            'quantite_kg'    => '',
            'type_mouvement' => 'entree',
            'agent_id'       => '',
            'decorticage_id' => '',
        ];
    }
}