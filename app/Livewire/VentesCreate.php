<?php

// app/Livewire/VentesCreate.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vente;
use App\Services\EcritureComptableService;

class VentesCreate extends Component
{
    public $client_id;
    public $montant_vente_total_fcfa;
    public $code_vente;
    public $date_vente;

    protected $rules = [
        'client_id'                    => 'required|exists:clients,id',
        'montant_vente_total_fcfa'     => 'required|integer|min:1',
        'code_vente'                   => 'required|string|unique:ventes,code_vente',
        'date_vente'                   => 'required|date',
    ];

    public function save()
    {
        $this->validate();

        $vente = Vente::create([
            'code_vente'                    => $this->code_vente,
            'client_id'                     => $this->client_id,
            'montant_vente_total_fcfa'      => $this->montant_vente_total_fcfa,
            'date_vente'                    => $this->date_vente,
        ]);

        $service = new EcritureComptableService();
        $service->createEcritureFromVente($vente);

        session()->flash('message', 'Vente et écriture comptable enregistrées.');

        return redirect()->route('ventes.index');
    }

    public function render()
    {
        return view('livewire.ventes-create');
    }
}
