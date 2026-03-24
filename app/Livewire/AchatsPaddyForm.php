<?php

// app/Livewire/AchatsPaddyForm.php
namespace App\Livewire;

use Livewire\Component;

class AchatsPaddyForm extends Component
{
    public function render()
    {
        return view('livewire.achats-paddy-form')
            ->layout('layouts.app');
    }
}
