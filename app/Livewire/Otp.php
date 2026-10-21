<?php

namespace App\Livewire;

use Livewire\Component;

class Otp extends Component
{
    public function render()
    {
        return view('livewire.otp')->layout('layouts.livewire-auth');
    }
}
