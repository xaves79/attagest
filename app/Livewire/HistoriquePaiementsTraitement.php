<?php
// app/Livewire/HistoriquePaiementsTraitement.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaiementTraitement;
use App\Models\TraitementClient;

class HistoriquePaiementsTraitement extends Component
{
    public $traitement_id;
    public $paiements = [];

    public function mount($traitement_id)
    {
        $this->traitement_id = $traitement_id;
        $this->loadPaiements();
    }

    public function loadPaiements()
    {
        $this->paiements = PaiementTraitement::where('traitement_id', $this->traitement_id)
            ->with('traitement.client:id,nom')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.historique-paiements-traitement');
    }
}
