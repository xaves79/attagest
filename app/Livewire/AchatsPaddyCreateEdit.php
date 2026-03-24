<?php

// app/Livewire/AchatsPaddyCreateEdit.php
namespace App\Livewire;

use Livewire\Component;

class AchatsPaddyCreateEdit extends Component
{
    public $id;
    public $mode = 'create';

    public function render()
    {
        return view('livewire.achats-paddy-create-edit')
            ->layout('layouts.app');
    }
}
