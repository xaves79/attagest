<?php

// app/Livewire/DecorticageShow.php

namespace App\Livewire;

use App\Models\Decorticage;
use Livewire\Component;

class DecorticageShow extends Component
{
    public $decorticage;

    protected $listeners = ['show-decorticage' => 'show'];

    public function show($id)
    {
        $this->decorticage = Decorticage::with([
            'etuvage',
            'lotRizEtuve',
            'agent',
            'achatPaddy',
            'varieteRice',
        ])->findOrFail($id);

        $this->dispatch('open-decorticage-show');
    }

    public function render()
    {
        return view('livewire.decorticage-show');
    }
}
