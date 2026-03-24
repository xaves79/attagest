<?php

// app/Livewire/AchatsCreate.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Achat;
use App\Services\EcritureComptableService;

class AchatsCreate extends Component
{
    public $fournisseur_id;
    public $montant_ttc;
    public $code_facture;
    public $date_achat;

    protected $rules = [
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'montant_ttc'    => 'required|numeric|min:0.01',
        'code_facture'   => 'required|string|unique:achats,code_facture',
        'date_achat'     => 'required|date',
    ];

    public function save()
    {
        $this->validate();

        $achat = Achat::create([
            'fournisseur_id' => $this->fournisseur_id,
            'montant_ttc'    => $this->montant_ttc,
            'code_facture'   => $this->code_facture,
            'date_achat'     => $this->date_achat,
        ]);

        $service = new EcritureComptableService();
        $service->createEcriture(
            libelle: "Achat paddy fournisseur {$achat->fournisseur->nom}",
            compteDebit: '601100', // Achats paddy
            montantDebit: $achat->montant_ttc,
            compteCredit: '401',   // Fournisseurs
            montantCredit: $achat->montant_ttc,
            pieceComptable: $achat->code_facture,
            dateEcriture: $achat->date_achat,
            valide: true
        );

        session()->flash('message', 'Achat et écriture comptable enregistrés.');

        return redirect()->route('achats.index');
    }

    public function render()
    {
        return view('livewire.achats-create');
    }
}


namespace App\Livewire;

use Livewire\Component;
use App\Models\Achat;
use App\Services\EcritureComptableService;

class AchatCreate extends Component
{
    public $fournisseur_id;
    public $montant_ttc;
    public $code_facture;
    public $date_achat;

    public function save()
    {
        $this->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_ttc'    => 'required|numeric|min:0.01',
            'code_facture'   => 'required|string|unique:achats,code_facture',
            'date_achat'     => 'required|date',
        ]);

        $achat = Achat::create([
            'fournisseur_id' => $this->fournisseur_id,
            'montant_ttc'    => $this->montant_ttc,
            'code_facture'   => $this->code_facture,
            'date_achat'     => $this->date_achat,
        ]);

        $service = new EcritureComptableService();
        $service->createEcriture(
            libelle: "Achat paddy fournisseur {$achat->fournisseur->nom}",
            compteDebit: '601100', // Achats paddy
            montantDebit: $achat->montant_ttc,
            compteCredit: '401',   // Fournisseurs
            montantCredit: $achat->montant_ttc,
            pieceComptable: $achat->code_facture,
            dateEcriture: $achat->date_achat,
            valide: true
        );

        session()->flash('message', 'Achat et écriture comptable enregistrés.');

        return redirect()->route('achats.index');
    }

    public function render()
    {
        return view('livewire.achat-create');
    }
}
